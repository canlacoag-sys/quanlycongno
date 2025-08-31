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

    public function add() {
        if($this->input->post()) {
            $khachhang_id = $this->input->post('khachhang_id');
            $tongtien = $this->input->post('tongtien');
            $datra = $this->input->post('datra');
            $conno = $this->input->post('conno');
            $ngaylap = date('Y-m-d H:i:s');

            $donhang_id = $this->Donhang_model->insert([
                'khachhang_id' => $khachhang_id,
                'tongtien' => $tongtien,
                'datra' => $datra,
                'conno' => $conno,
                'ngaylap' => $ngaylap
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
            redirect('donhang');
        }
        // Nếu muốn render form riêng, có thể gọi $this->render('donhang/add', $data);
        redirect('donhang');
    }
}