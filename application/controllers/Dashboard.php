<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @property CI_DB_query_builder $db
 * @property CI_Session $session
 * @property CI_Input $input
 * @property CI_Pagination $pagination
 * @property CI_Output $output
 */
class Dashboard extends CI_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->library('session');
		$this->load->helper('url');
		if(!$this->session->userdata('user_id')) {
			redirect('auth/login');
		}
	}
	public function index() {
		$this->load->database();

		// Tổng tiền đơn lẻ
		$tong_tien_khachle = $this->db->select_sum('tongcong_tien')->get('khachle')->row()->tongcong_tien ?? 0;
		// Tổng số đơn lẻ
		$tong_don_khachle = $this->db->count_all('khachle');
		// Tổng khách hàng lẻ (đếm tên không trùng)
		$query_le = $this->db->select('ten')->group_by('ten')->get('khachle');
		$tong_khachhang_le = $query_le->num_rows();

		// Tổng tiền đơn sỉ
		$tong_tien_khachsi = $this->db->select_sum('tongtien')->get('donhang')->row()->tongtien ?? 0;
		// Tổng đơn sỉ
		$tong_don_khachsi = $this->db->count_all('donhang');
		// Tổng số khách sỉ (đếm id không trùng)
		$query_si = $this->db->select('khachhang_id')->group_by('khachhang_id')->get('donhang');
		$tong_khachhang_si = $query_si->num_rows();
		$khachle_moi = $this->db->order_by('id', 'DESC')->limit(5)->get('khachle')->result();

		// Đơn khách sỉ mới nhất (5 đơn) - lấy từ bảng donhang, join bảng khachhang để lấy tên khách
		$khachsi_moi = $this->db
			->select('donhang.madon_id, donhang.khachhang_id, donhang.tongtien, donhang.ngaylap, khachhang.ten as ten_khach')
			->from('donhang')
			->join('khachhang', 'donhang.khachhang_id = khachhang.id', 'left')
			->order_by('donhang.id', 'DESC')
			->limit(5)
			->get()
			->result();

		$data = [
			'tong_tien_khachle'   => $tong_tien_khachle,
			'tong_don_khachle'    => $tong_don_khachle,
			'tong_khachhang_le'   => $tong_khachhang_le,
			'tong_tien_khachsi'   => $tong_tien_khachsi,
			'tong_don_khachsi'    => $tong_don_khachsi,
			'tong_khachhang_si'   => $tong_khachhang_si,
			'khachle_moi' => $khachle_moi,
			'khachsi_moi' => $khachsi_moi,
		];
		$data['title']  = 'TỔNG QUAN';
		$data['active'] = 'dashboard';
		
		$this->load->view('templates/header', $data);
		$this->load->view('templates/navbar', $data);   // ✅ nhớ include navbar
		$this->load->view('templates/sidebar', $data);
		$this->load->view('dashboard/index', $data);
		$this->load->view('templates/footer');
	}
}
