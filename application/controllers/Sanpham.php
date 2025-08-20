<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sanpham extends CI_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->database();
		$this->load->library('session');
		$this->load->helper('url');
		$this->load->library('pagination');
		if(!$this->session->userdata('user_id')) {
			redirect('auth/login');
		}
	}

	// Danh sách sản phẩm
	public function index()
	{
		$keyword = $this->input->get('keyword'); // Lấy từ ô tìm kiếm
		$this->load->library('pagination');
	
		// Đếm tổng dòng (có search)
		if ($keyword) {
			$this->db->like('ten_sp', $keyword);
			$this->db->or_like('ma_sp', $keyword);
		}
		$total = $this->db->count_all_results('sanpham');
	
		// Phân trang
		$config['base_url'] = site_url('sanpham/index');
		$config['total_rows'] = $total;
		$config['per_page'] = 30;
		$config['page_query_string'] = TRUE;
		$config['query_string_segment'] = 'per_page';
	
		// Bootstrap 4 style
		$config['full_tag_open'] = '<nav><ul class="pagination justify-content-center">';
		$config['full_tag_close'] = '</ul></nav>';
		$config['num_tag_open'] = '<li class="page-item">';
		$config['num_tag_close'] = '</li>';
		$config['cur_tag_open'] = '<li class="page-item active"><span class="page-link">';
		$config['cur_tag_close'] = '</span></li>';
		$config['next_tag_open'] = '<li class="page-item">';
		$config['next_tag_close'] = '</li>';
		$config['prev_tag_open'] = '<li class="page-item">';
		$config['prev_tag_close'] = '</li>';
		$config['first_tag_open'] = '<li class="page-item">';
		$config['first_tag_close'] = '</li>';
		$config['last_tag_open'] = '<li class="page-item">';
		$config['last_tag_close'] = '</li>';
		$config['attributes'] = array('class' => 'page-link');
	
		$this->pagination->initialize($config);
	
		$offset = ($this->input->get('per_page')) ? $this->input->get('per_page') : 0;
	
		// Lấy dữ liệu (có search)
		if ($keyword) {
			$this->db->like('ten_sp', $keyword);
			$this->db->or_like('ma_sp', $keyword);
		}
		$data['list'] = $this->db->limit($config['per_page'], $offset)->get('sanpham')->result();
		$data['pagination'] = $this->pagination->create_links();
		$data['keyword'] = $keyword;
	
		$this->load->view('template/header');
		$this->load->view('template/topnav');
		$this->load->view('sanpham/index', $data);
		$this->load->view('template/footer');
	}



	// Thêm sản phẩm
	public function add() {
		if($this->input->post()) {
			$this->db->insert('sanpham', [
				'ma_sp'  => $this->input->post('ma_sp'),
				'ten_sp' => $this->input->post('ten_sp'),
				'gia'    => $this->input->post('gia'),
			]);
			redirect('sanpham');
		}
		$this->load->view('template/header');
		$this->load->view('template/topnav');
		$this->load->view('sanpham/add');
		$this->load->view('template/footer');
	}
	// Sửa sản phẩm
	public function edit($id = 0) {
		$sp = $this->db->get_where('sanpham', ['id'=>$id])->row();
		if(!$sp) show_404();
	
		if($this->input->post()) {
			$this->db->where('id', $id)->update('sanpham', [
				'ma_sp'  => $this->input->post('ma_sp'),
				'ten_sp' => $this->input->post('ten_sp'),
				'gia'    => $this->input->post('gia'),
			]);
			redirect('sanpham');
		}
	
		$data['sp'] = $sp;
		$this->load->view('template/header');
		$this->load->view('template/topnav');
		$this->load->view('sanpham/edit', $data);
		$this->load->view('template/footer');
	}
	
	// Xoá sản phẩm
	public function delete($id = 0) {
		$this->db->where('id', $id)->delete('sanpham');
		redirect('sanpham');
	}

}
