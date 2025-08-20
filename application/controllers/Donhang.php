<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Donhang extends CI_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->database();
		$this->load->library('session');
		$this->load->helper(['url', 'form']);
		if(!$this->session->userdata('user_id')) redirect('auth/login');
	}

	public function add() {
		// Lấy danh sách khách hàng và sản phẩm cho dropdown/select2
		$data['khachhang'] = $this->db->get('khachhang')->result();
		$data['sanpham'] = $this->db->get('sanpham')->result();

		if($this->input->post()) {
			$khachhang_id = $this->input->post('khachhang_id');
			$tongtien = $this->input->post('tongtien');
			$datra = $this->input->post('datra');
			$conno = $this->input->post('conno');
			$ngaylap = date('Y-m-d H:i:s');

			// Lưu đơn hàng
			$this->db->insert('donhang', [
				'khachhang_id' => $khachhang_id,
				'tongtien' => $tongtien,
				'datra' => $datra,
				'conno' => $conno,
				'ngaylap' => $ngaylap
			]);
			$donhang_id = $this->db->insert_id();

			// Lưu chi tiết đơn hàng (mảng các dòng sản phẩm)
			$ma_sp = $this->input->post('ma_sp');
			$so_luong = $this->input->post('so_luong');
			$don_gia = $this->input->post('don_gia');
			$thanh_tien = $this->input->post('thanh_tien');

			for($i=0;$i<count($ma_sp);$i++) {
				if(!empty($ma_sp[$i]) && $so_luong[$i] > 0) {
					$this->db->insert('chitiet_donhang', [
						'donhang_id' => $donhang_id,
						'ma_sp' => $ma_sp[$i],
						'so_luong' => $so_luong[$i],
						'don_gia' => $don_gia[$i],
						'thanh_tien' => $thanh_tien[$i]
					]);
				}
			}
			redirect('donhang/list'); // hoặc route về danh sách đơn hàng
		}
		$this->load->view('template/header');
		$this->load->view('template/topnav');
		$this->load->view('donhang/add', $data);
		$this->load->view('template/footer');
	}
}
