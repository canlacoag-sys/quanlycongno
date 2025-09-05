<?php
// filepath: application/controllers/Donhang.php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @property Khachhang_model $Khachhang_model
 * @property CI_Session $session
 * @property CI_Input $input
 * @property CI_Pagination $pagination
 * @property CI_Output $output
 */
class Donhang extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Donhang_model');
        $this->load->helper(['url', 'form']);
        $this->load->library('session');
        if(!$this->session->userdata('user_id')) redirect('auth/login');
    }

    // Hàm render dùng chung cho mọi view
    private function render(string $view, array $data = []) {
        $data['title']  = $data['title']  ?? 'CHI TIẾT ĐƠN HÀNG';
        $data['active'] = $data['active'] ?? 'donhang';
        $this->load->view('templates/header',  $data);
        $this->load->view('templates/navbar',  $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view($view,               $data);
        $this->load->view('templates/footer');
    }

    public function index() {
        $this->load->model('Khachhang_model');
        $list = $this->Donhang_model->get_all();
        $data = [
            'list' => $list,
            'sanpham' => $this->db->get('sanpham')->result(),
            'khachhang' => $this->db->get('khachhang')->result(),
        ];
        $this->render('donhang/index', $data);
    }

    public function autocomplete_khachhang() {
        $term = $this->input->get('term');
        $this->load->model('Khachhang_model');
        $result = $this->Khachhang_model->autocomplete($term);
        $data = [];
        foreach ($result as $kh) {
            $data[] = [
                'id' => $kh->id,
                'label' => $kh->ten . ' (' . $kh->dienthoai . ')',
                'value' => $kh->ten,
                'dienthoai' => $kh->dienthoai,
                'diachi' => $kh->diachi
            ];
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function autocomplete_sanpham() {
        $term = $this->input->get('term');
        $chietkhau = $this->input->get('chietkhau');
        $this->load->model('Sanpham_model');
        $result = $this->Sanpham_model->autocomplete($term, $chietkhau);
        $data = [];
        foreach ($result as $sp) {
            $data[] = [
                'id' => $sp->id,
                'label' => $sp->ma_sp . ' - ' . $sp->ten_sp,
                'value' => $sp->ma_sp,
                'ten_sp' => $sp->ten_sp,
                'gia' => $sp->gia
            ];
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function add_khachhang_ajax() {
        $ten = $this->input->post('ten');
        $dienthoai = $this->input->post('dienthoai');
        $diachi = $this->input->post('diachi');
        $this->load->model('Khachhang_model');
        $id = $this->Khachhang_model->insert([
            'ten' => $ten,
            'dienthoai' => $dienthoai,
            'diachi' => $diachi
        ]);
        $kh = $this->Khachhang_model->get_by_id($id);
        $this->output->set_content_type('application/json')->set_output(json_encode($kh));
    }

    public function pos($id) {
        $this->load->model('Khachhang_model');
        $this->load->model('Sanpham_model');
        $dh = $this->Donhang_model->get_by_id($id);
        $ct = $this->Donhang_model->get_chitiet($id);
        $kh = $this->Khachhang_model->get_by_id($dh->khachhang_id);

        // Lấy danh sách sản phẩm dạng mảng ma_sp => object (để tra cứu nhanh trong pos.php)
        $sanpham_arr = [];
        $sanpham_db = $this->Sanpham_model->get_all();
        foreach ($sanpham_db as $sp) {
            $sanpham_arr[$sp->ma_sp] = $sp;
        }

        $this->load->view('donhang/pos', [
            'donhang' => $dh,
            'chitiet' => $ct,
            'khachhang' => $kh,
            'sanpham' => $sanpham_arr // truyền vào dạng mảng ma_sp => object
        ]);
    }

    public function add() {
        if($this->input->method() === 'post') {
            $khachhang_id = $this->input->post('khachhang_id');
            $tongtien = $this->input->post('tongtien');
            $ngaylap = $this->input->post('ngaylap');
            $giao_hang = $this->input->post('giao_hang');
            $nguoi_nhan = $this->input->post('nguoi_nhan');
            $ghi_chu = $this->input->post('ghi_chu');
            $co_chiet_khau = $this->input->post('co_chiet_khau') ?? null;
            if(!$ngaylap) $ngaylap = date('Y-m-d H:i:s');

            // Tạo mã đơn hàng: DH + chuỗi số từ ngày lập (YYYYMMDDHHIISS)
            $madon_id = 'DH' . date('YmdHis', strtotime($ngaylap));

            $donhang_id = $this->Donhang_model->insert([
                'madon_id' => $madon_id,
                'khachhang_id' => $khachhang_id,
                'tongtien' => $tongtien,
                'ngaylap' => $ngaylap,
                'giao_hang' => $giao_hang,
                'nguoi_nhan' => $nguoi_nhan,
                'ghi_chu' => $ghi_chu,
                'co_chiet_khau' => $co_chiet_khau
            ]);

            $ma_sp = $this->input->post('ma_sp');
            $so_luong = $this->input->post('so_luong');
            $don_gia = $this->input->post('don_gia');
            $thanh_tien = $this->input->post('thanh_tien');

            for($i=0;$i<count($ma_sp);$i++) {
                if(!empty($ma_sp[$i]) && $so_luong[$i] > 0) {
                    $this->Donhang_model->insert_chitiet([
                        'donhang_id' => $donhang_id,
                        'ma_sp' => $ma_sp[$i],
                        'so_luong' => $so_luong[$i],
                        'don_gia' => $don_gia[$i],
                        'thanh_tien' => $thanh_tien[$i]
                    ]);
                }
            }
            redirect('donhang/pos/'.$donhang_id);
        }
        $data = [
            'sanpham' => $this->db->get('sanpham')->result(),
        ];
        $this->render('donhang/add', $data);
    }

    public function danhsachdonhang() {
        $this->load->model('Khachhang_model');
        $list = $this->Donhang_model->get_all();
        $data = [
            'list' => $list,
            'sanpham' => $this->db->get('sanpham')->result(),
            'khachhang' => $this->db->get('khachhang')->result(),
        ];
        $this->render('donhang/danhsachdonhang', $data);
    }

    public function themdonhang() {
        $data = [
            'sanpham' => $this->db->get('sanpham')->result(),
        ];
        $this->render('donhang/themdonhang', $data);
    }

    public function edit($id) {
        $this->load->model('Khachhang_model');
        $donhang = $this->Donhang_model->get_by_id($id);
        $chitiet = $this->Donhang_model->get_chitiet($id);

        // Lấy đúng thông tin khách hàng cho autocomplete khi sửa
        $khachhang = null;
        if ($donhang && $donhang->khachhang_id) {
            $khachhang = $this->Khachhang_model->get_by_id($donhang->khachhang_id);
        }

        if ($this->input->method() === 'post') {
            $khachhang_id = $this->input->post('khachhang_id');
            $tongtien = $this->input->post('tongtien');
            $ngaylap = $this->input->post('ngaylap');
            $giao_hang = $this->input->post('giao_hang');
            $nguoi_nhan = $this->input->post('nguoi_nhan');
            $ghi_chu = $this->input->post('ghi_chu');
            if(!$ngaylap) $ngaylap = date('Y-m-d H:i:s');

            $this->Donhang_model->update($id, [
                'khachhang_id' => $khachhang_id,
                'tongtien' => $tongtien,
                'ngaylap' => $ngaylap,
                'giao_hang' => $giao_hang,
                'nguoi_nhan' => $nguoi_nhan,
                'ghi_chu' => $ghi_chu
            ]);

            $this->db->where('donhang_id', $id)->delete('chitiet_donhang');
            $ma_sp = $this->input->post('ma_sp');
            $so_luong = $this->input->post('so_luong');
            $don_gia = $this->input->post('don_gia');
            $thanh_tien = $this->input->post('thanh_tien');
            for($i=0;$i<count($ma_sp);$i++) {
                if(!empty($ma_sp[$i]) && $so_luong[$i] > 0) {
                    $this->Donhang_model->insert_chitiet([
                        'donhang_id' => $id,
                        'ma_sp' => $ma_sp[$i],
                        'so_luong' => $so_luong[$i],
                        'don_gia' => $don_gia[$i],
                        'thanh_tien' => $thanh_tien[$i]
                    ]);
                }
            }
            redirect('donhang/pos/'.$id);
        }

        $data = [
            'donhang' => $donhang,
            'chitiet' => $chitiet,
            'sanpham' => $this->db->get('sanpham')->result(),
            'khachhang' => $khachhang, // truyền đúng object khách hàng cho view
        ];
        $this->render('donhang/edit', $data);
    }

    public function addcochietkhau() {
        $this->load->helper('money');
        if($this->input->method() === 'post') {
            $khachhang_id = $this->input->post('khachhang_id');
            $tongtien = $this->input->post('tongtien');
            $ngaylap = $this->input->post('ngaylap');
            $giao_hang = $this->input->post('giao_hang');
            $nguoi_nhan = $this->input->post('nguoi_nhan');
            $ghi_chu = $this->input->post('ghi_chu');
            $co_chiet_khau = 1;
            if(!$ngaylap) $ngaylap = date('Y-m-d H:i:s');

            if ($tongtien === null || $tongtien === '' || !is_numeric($tongtien)) {
                $tongtien = 0;
            }

            // Tạo mã đơn hàng: DH + chuỗi số từ ngày lập (YYYYMMDDHHIISS)
            $madon_id = 'DH' . date('YmdHis', strtotime($ngaylap));

            $donhang_id = $this->Donhang_model->insert([
                'madon_id' => $madon_id,
                'khachhang_id' => $khachhang_id,
                'tongtien' => $tongtien,
                'ngaylap' => $ngaylap,
                'giao_hang' => $giao_hang,
                'nguoi_nhan' => $nguoi_nhan,
                'ghi_chu' => $ghi_chu,
                'co_chiet_khau' => $co_chiet_khau
            ]);

            $ma_sp = $this->input->post('ma_sp');
            $so_luong = $this->input->post('so_luong');
            $don_gia = $this->input->post('don_gia');
            $thanh_tien = $this->input->post('thanh_tien');

            for($i=0;$i<count($ma_sp);$i++) {
                if(!empty($ma_sp[$i]) && $so_luong[$i] > 0) {
                    $this->Donhang_model->insert_chitiet([
                        'donhang_id' => $donhang_id,
                        'ma_sp' => $ma_sp[$i],
                        'so_luong' => $so_luong[$i],
                        'don_gia' => $don_gia[$i],
                        'thanh_tien' => $thanh_tien[$i]
                    ]);
                }
            }
            redirect('donhang/pos/'.$donhang_id);
        }
        $data = [
            'sanpham' => $this->db->get('sanpham')->result(),
            'active' => 'donhang/addcochietkhau',
        ];
        $this->render('donhang/addcochietkhau', $data);
    }

    public function addkochietkhau() {
        $this->load->helper('money');
        if($this->input->method() === 'post') {
            $khachhang_id = $this->input->post('khachhang_id');
            $tongtien = $this->input->post('tongtien');
            $ngaylap = $this->input->post('ngaylap');
            $giao_hang = $this->input->post('giao_hang');
            $nguoi_nhan = $this->input->post('nguoi_nhan');
            $ghi_chu = $this->input->post('ghi_chu');
            $co_chiet_khau = 0;
            if(!$ngaylap) $ngaylap = date('Y-m-d H:i:s');

            // Tạo mã đơn hàng: DH + chuỗi số từ ngày lập (YYYYMMDDHHIISS)
            $madon_id = 'DH' . date('YmdHis', strtotime($ngaylap));

            $donhang_id = $this->Donhang_model->insert([
                'madon_id' => $madon_id,
                'khachhang_id' => $khachhang_id,
                'tongtien' => $tongtien,
                'ngaylap' => $ngaylap,
                'giao_hang' => $giao_hang,
                'nguoi_nhan' => $nguoi_nhan,
                'ghi_chu' => $ghi_chu,
                'co_chiet_khau' => $co_chiet_khau
            ]);

            $ma_sp = $this->input->post('ma_sp');
            $so_luong = $this->input->post('so_luong');
            $don_gia = $this->input->post('don_gia');
            $thanh_tien = $this->input->post('thanh_tien');

            for($i=0;$i<count($ma_sp);$i++) {
                if(!empty($ma_sp[$i]) && $so_luong[$i] > 0) {
                    $this->Donhang_model->insert_chitiet([
                        'donhang_id' => $donhang_id,
                        'ma_sp' => $ma_sp[$i],
                        'so_luong' => $so_luong[$i],
                        'don_gia' => $don_gia[$i],
                        'thanh_tien' => $thanh_tien[$i]
                    ]);
                }
            }
            redirect('donhang/pos/'.$donhang_id);
        }
        $data = [
            'sanpham' => $this->db->get('sanpham')->result(),
            'active' => 'donhang/addkochietkhau', // Thêm dòng này
        ];
        $this->render('donhang/addkochietkhau', $data);
    }
}