<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Congno extends CI_Controller
{    // Action lập phiếu kết sổ nợ
    public function ketso($khachhang_id)
    {
        if ($this->input->method() === 'post') {
            $items = $this->input->post('items');
            $tong_tien = 0;
            foreach ($items as $item) {
                $tong_tien += (int)$item['thanh_tien'];
            }
            $data_json = json_encode($items, JSON_UNESCAPED_UNICODE);
            $ghichu = $this->input->post('ghichu') ?? '';
            $insert_data = [
                'khachhang_id' => $khachhang_id,
                'ngaylap' => date('Y-m-d H:i:s'),
                'tong_tien' => $tong_tien,
                'ghichu' => $ghichu,
                'data_json' => $data_json
            ];
            $this->db->insert('congno', $insert_data);
            $congno_id = $this->db->insert_id();
            // Chuyển sang trang in phiếu POS khổ A4
            redirect('congno/inphieu/' . $congno_id);
        } else {
            show_404();
        }
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
                            'ten_sp' => $ten_sp,
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
        $page_data = [
            'title' => 'Công nợ khách hàng',
            'data' => $data,
            'active' => 'congno'
        ];
        $this->load->view('templates/header', $page_data);
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
            $this->Congno_model->insert([
                'khachhang_id' => $khachhang_id,
                'ghichu' => $ghichu,
                'sotien' => $sotien,
                'ngaytao' => date('Y-m-d H:i:s')
            ]);
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
            $this->Congno_model->delete($id);
            redirect('congno');
        }
        $data['id'] = $id;
        $this->load->view('congno/del', $data);
    }

    public function detail($khachhang_id)
    {
        $kh = $this->Khachhang_model->get_by_id($khachhang_id);
        if (!$kh) show_404();

        $donhangs = $this->Donhang_model->get_by_khachhang($khachhang_id);

        // Tổng nợ và tổng đơn hàng
        $tong_no = 0;
        $tong_don = count($donhangs);

        // Tổng hợp chi tiết sản phẩm, bổ sung co_chiet_khau
        $sanpham_tonghop = [];
        foreach ($donhangs as $dh) {
            $tong_no += $dh->tongtien;
            $chitiet = $this->Donhang_model->get_chitiet($dh->id);
            foreach ($chitiet as $ct) {
                $ma_sps = explode(',', $ct->ma_sp);
                foreach ($ma_sps as $ma_sp) {
                    $ma_sp = trim($ma_sp);
                    if (!isset($sanpham_tonghop[$ma_sp])) {
                        $sp_obj = $this->Sanpham_model->get_by_ma_sp($ma_sp);
                        $ten_sp = $sp_obj ? $sp_obj->ten_sp : '';
                        $don_gia = $ct->don_gia;
                        $co_chiet_khau = ($sp_obj && isset($sp_obj->co_chiet_khau)) ? $sp_obj->co_chiet_khau : 0;
                        $sanpham_tonghop[$ma_sp] = [
                            'ma_sp' => $ma_sp,
                            'ten_sp' => $ten_sp,
                            'don_gia' => $don_gia,
                            'co_chiet_khau' => $co_chiet_khau,
                            'so_luong' => 0,
                            'thanh_tien' => 0
                        ];
                    }
                    $sanpham_tonghop[$ma_sp]['so_luong'] += $ct->so_luong;
                    $sanpham_tonghop[$ma_sp]['thanh_tien'] += $ct->so_luong * $ct->don_gia;
                }
            }
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
        $this->load->view('templates/sidebar', $page_data);
        $this->load->view('congno/detail', $page_data);
        $this->load->view('templates/footer');
    }
}