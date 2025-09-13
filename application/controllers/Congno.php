<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Congno extends CI_Controller
{    // Action lập phiếu kết sổ nợ
    public function ketso($khachhang_id)
    {
        if ($this->input->method() !== 'post') {
            show_404();
            return;
        }

        $items = $this->input->post('items');
        if (!is_array($items)) {
            $items = [];
        }

        // Normalize each posted item to ensure numeric fields and co_chiet_khau are reliable
        foreach ($items as $k => $it) {
            $ma = $it['ma_sp'] ?? null;
            $sp_obj = null;
            if ($ma) {
                $sp_obj = $this->Sanpham_model->get_by_ma_sp($ma);
            }

            $co_chiet_khau = null;
            if (isset($it['co_chiet_khau'])) {
                // value may be '1' or '0' or 'on'
                $co_chiet_khau = intval($it['co_chiet_khau']);
            } else {
                $co_chiet_khau = $sp_obj ? (isset($sp_obj->co_chiet_khau) ? intval($sp_obj->co_chiet_khau) : 0) : 0;
            }

            $don_gia = isset($it['don_gia']) ? intval($it['don_gia']) : 0;
            $so_luong = isset($it['so_luong']) ? intval($it['so_luong']) : 0;
            $thanh_tien = isset($it['thanh_tien']) ? intval($it['thanh_tien']) : ($don_gia * $so_luong);

            $items[$k]['ma_sp'] = $ma;
            $items[$k]['co_chiet_khau'] = $co_chiet_khau;
            $items[$k]['don_gia'] = $don_gia;
            $items[$k]['so_luong'] = $so_luong;
            $items[$k]['thanh_tien'] = $thanh_tien;
        }

        // Now compute subtotals from normalized items
        $subtotal_chiet = 0;
        $subtotal_non = 0;
        foreach ($items as $it) {
            $thanh = isset($it['thanh_tien']) ? intval($it['thanh_tien']) : 0;
            if (!empty($it['co_chiet_khau'])) {
                $subtotal_chiet += $thanh;
            } else {
                $subtotal_non += $thanh;
            }
        }

        // Read discount percent (prefer named field chietkhau_percent)
        $pct = $this->input->post('chietkhau_percent');
        if ($pct === null) {
            $pct = $this->input->post('discountPercent');
        }
        $pct = $pct !== null ? floatval($pct) : 0.0;
        if ($pct < 0) $pct = 0.0;
        if ($pct > 100) $pct = 100.0;

        $chietkhau_amount = (int) round($subtotal_chiet * ($pct / 100.0));
        $tong_chietkhau_truoc = $subtotal_chiet;
        $tong_chietkhau_sau = $subtotal_chiet - $chietkhau_amount;
        $tong_khong_chiet = $subtotal_non;
        $tong_cong = $tong_chietkhau_sau + $tong_khong_chiet;

        $data_json = json_encode(array_values($items), JSON_UNESCAPED_UNICODE);
        $ghichu = $this->input->post('ghichu') ?? '';

        $insert_data = [
            'khachhang_id' => $khachhang_id,
            'ngaylap' => date('Y-m-d H:i:s'),
            'tong_tien' => $tong_cong,
            'ghichu' => $ghichu,
            'data_json' => $data_json,
            // Persist computed breakdowns
            'tong_chietkhau_truoc' => $tong_chietkhau_truoc,
            'chietkhau_percent' => $pct,
            'chietkhau_amount' => $chietkhau_amount,
            'tong_chietkhau_sau' => $tong_chietkhau_sau,
            'tong_khong_chiet' => $tong_khong_chiet,
            'tong_cong' => $tong_cong
        ];

        $this->db->insert('congno', $insert_data);
        $congno_id = $this->db->insert_id();
        redirect('congno/inphieu/' . $congno_id);
    }

    // Trang in phiếu POS khổ A4
    public function inphieu($congno_id)
    {
        $congno = $this->db->get_where('congno', ['id' => $congno_id])->row();
        if (!$congno) show_404();
        $khachhang = $this->Khachhang_model->get_by_id($congno->khachhang_id);
        $items = json_decode($congno->data_json, true);
        $page_data = [
            'congno' => $congno,
            'khachhang' => $khachhang,
            'items' => $items
        ];
        $this->load->view('congno/inphieu', $page_data);
    }

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library('session');
        $this->load->model('Khachhang_model');
        $this->load->model('Donhang_model');
        $this->load->model('Sanpham_model');
        $this->load->helper('url');
    }

    // Hiển thị danh sách công nợ khách hàng
    public function index()
    {
        $khachhangs = $this->Khachhang_model->get_all();
        $data = [];
        foreach ($khachhangs as $kh) {
            $donhangs = $this->Donhang_model->get_by_khachhang($kh->id);
            $tong_tien = 0;
            $tong_so_luong = 0;
            $sanpham_chitiet = [];
            foreach ($donhangs as $dh) {
                $chitiet = $this->Donhang_model->get_chitiet($dh->id);
                foreach ($chitiet as $ct) {
                    $tong_tien += $ct->thanh_tien;
                    $tong_so_luong += $ct->so_luong;
                    // Gộp sản phẩm theo mã, lấy tên từ bảng sản phẩm nếu chưa có
                    $ma_sp = $ct->ma_sp;
                    $sp_obj = $this->Sanpham_model->get_by_ma_sp($ma_sp);
                        $co_chiet_khau = $sp_obj ? $sp_obj->co_chiet_khau : 0;
                    if (!isset($sanpham_chitiet[$ma_sp])) {
                        // Lấy tên sản phẩm từ bảng sản phẩm nếu ct->ten_sp rỗng
                        $ten_sp = $ct->ten_sp ?? '';
                        if (!$ten_sp) {
                            $sp_obj = $this->Sanpham_model->get_by_ma_sp($ma_sp);
                            $ten_sp = $sp_obj ? $sp_obj->ten_sp : '';
                        }
                        $sanpham_chitiet[$ma_sp] = [
                            'ma_sp' => $ma_sp,
                            'co_chiet_khau' => $co_chiet_khau,
                            'so_luong' => 0,
                            'thanh_tien' => 0
                        ];
                    }
                    $sanpham_chitiet[$ma_sp]['so_luong'] += $ct->so_luong;
                    $sanpham_chitiet[$ma_sp]['thanh_tien'] += $ct->thanh_tien;
                }
            }
            $data[] = [
                'khachhang' => $kh,
                'tong_tien' => $tong_tien,
                'tong_so_luong' => $tong_so_luong,
                'sanpham_chitiet' => array_values($sanpham_chitiet)
            ];
        }
        $user_id = $this->session->userdata('user_id');
        $user_role = null;
        if ($user_id) {
            $user = $this->db->get_where('users', ['id' => $user_id])->row();
            $user_role = $user ? $user->role : null;
        }
        $page_data = [
            'title' => 'Công nợ khách hàng',
            'data' => $data,
            'active' => 'congno',
            'user_role' => $user_role
        ];
        $this->load->view('templates/header', $page_data);
        $this->load->view('templates/navbar', $page_data);
        $this->load->view('templates/sidebar', $page_data);
        $this->load->view('congno/index', $page_data);
        $this->load->view('templates/footer');
    }

    // Thêm công nợ (ví dụ: thêm ghi chú hoặc phiếu thu)
    public function add()
    {
        $this->load->model('Congno_model');
        if ($this->input->method() === 'post') {
            $khachhang_id = $this->input->post('khachhang_id');
            $ghichu = $this->input->post('ghichu');
            $sotien = $this->input->post('sotien');
                $data_new = [
                    'khachhang_id' => $khachhang_id,
                    'ghichu' => $ghichu,
                    'sotien' => $sotien,
                    'ngaytao' => date('Y-m-d H:i:s')
                ];
                $id = $this->Congno_model->insert($data_new);
                // Log thao tác thêm
                $this->load->model('Actionlog_model');
                $user_id = $this->session->userdata('user_id');
                $this->Actionlog_model->log($user_id, 'add', 'congno', $id, null, json_encode($data_new, JSON_UNESCAPED_UNICODE));
            redirect('congno');
        }
        $data['khachhangs'] = $this->Khachhang_model->get_all();
        $this->load->view('congno/add', $data);
    }

    // Xóa công nợ (theo id công nợ)
    public function del($id)
    {
        $this->load->model('Congno_model');
        if ($this->input->method() === 'post') {
                // Lấy dữ liệu trước khi xoá
                $row_before = $this->db->get_where('congno', ['id' => $id])->row_array();
                $this->Congno_model->delete($id);
                // Log thao tác xoá
                $this->load->model('Actionlog_model');
                $user_id = $this->session->userdata('user_id');
                $this->Actionlog_model->log($user_id, 'delete', 'congno', $id, json_encode($row_before, JSON_UNESCAPED_UNICODE), null);
            redirect('congno');
        }
        $data['id'] = $id;
        $this->load->view('congno/del', $data);
    }

    public function detail($khachhang_id)
{
    $this->load->model('Khachhang_model');
    $this->load->model('Donhang_model');
    $this->load->model('Sanpham_model');
    $kh = $this->Khachhang_model->get_by_id($khachhang_id);
    if (!$kh) show_404();

    $donhangs = $this->Donhang_model->get_by_khachhang($khachhang_id);

    $tong_no = 0;
    $tong_don = count($donhangs);

    $sanpham_tonghop = [];
    $ma_banh_count = [];
    foreach ($donhangs as $dh) {
        $tong_no += $dh->tongtien;
        $chitiet = $this->Donhang_model->get_chitiet($dh->id);
        foreach ($chitiet as $ct) {
            $ma_sps = array_map('trim', explode(',', $ct->ma_sp));
            foreach ($ma_sps as $ma_sp) {
                if (!isset($ma_banh_count[$ma_sp])) $ma_banh_count[$ma_sp] = 0;
                $ma_banh_count[$ma_sp]++;
            }
            $counts = array_count_values($ma_sps);
            foreach ($counts as $ma_sp => $count) {
                $sp_obj = $this->Sanpham_model->get_by_ma_sp($ma_sp);
                $ten_sp = $sp_obj ? $sp_obj->ten_sp : '';
                // Lấy đơn giá chuẩn từ bảng sản phẩm
                $don_gia = $sp_obj ? $sp_obj->gia : 0;
                $co_chiet_khau = ($sp_obj && isset($sp_obj->co_chiet_khau)) ? $sp_obj->co_chiet_khau : 0;
                $so_luong_thuc = $count * $ct->so_luong;
                if (!isset($sanpham_tonghop[$ma_sp])) {
                    $sanpham_tonghop[$ma_sp] = [
                        'ma_sp' => $ma_sp,
                        'ten_sp' => $ten_sp,
                        'don_gia' => $don_gia,
                        'co_chiet_khau' => $co_chiet_khau,
                        'so_luong' => 0,
                        'thanh_tien' => 0,
                        'so_lan_lap' => 0
                    ];
                }
                $sanpham_tonghop[$ma_sp]['so_luong'] += $so_luong_thuc;
                $sanpham_tonghop[$ma_sp]['so_lan_lap'] = $ma_banh_count[$ma_sp];
            }
        }
    }
    // Sau khi tổng hợp xong, cập nhật lại thành tiền = tổng số lượng x đơn giá chuẩn
    foreach ($sanpham_tonghop as $ma_sp => &$sp) {
        $sp['thanh_tien'] = $sp['so_luong'] * $sp['don_gia'];
    }

    $page_data = [
        'khachhang' => $kh,
        'donhangs' => $donhangs,
        'tong_no' => $tong_no,
        'tong_don' => $tong_don,
        'sanpham_tonghop' => array_values($sanpham_tonghop),
        'active' => 'congno'
    ];
    $this->load->view('templates/header', $page_data);
    $this->load->view('templates/navbar', $page_data);
    $this->load->view('templates/sidebar', $page_data);
    $this->load->view('congno/detail', $page_data);
    $this->load->view('templates/footer');
    }
}