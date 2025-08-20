<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Khachhang extends CI_Controller {
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

	// Danh sách khách hàng
	public function index()
	{
		$keyword = $this->input->get('keyword'); // Lấy từ ô tìm kiếm
		$this->load->library('pagination');
	
		// Đếm tổng dòng có search
		if ($keyword) {
			$this->db->like('ten', $keyword);
			$this->db->or_like('dienthoai', $keyword);
			$this->db->or_like('diachi', $keyword);
		}
		$total = $this->db->count_all_results('khachhang');
	
		// Phân trang
		$config['base_url'] = site_url('khachhang/index');
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
			$this->db->like('ten', $keyword);
			$this->db->or_like('dienthoai', $keyword);
			$this->db->or_like('diachi', $keyword);
		}
		$data['list'] = $this->db->limit($config['per_page'], $offset)->get('khachhang')->result();
		$data['pagination'] = $this->pagination->create_links();
		$data['keyword'] = $keyword;
	
		$this->load->view('template/header');
		$this->load->view('template/topnav');
		$this->load->view('khachhang/index', $data);
		$this->load->view('template/footer');
	}


	public function ajax_add()
	{
		// Chỉ chấp nhận AJAX
		if (!$this->input->is_ajax_request()) show_404();
		$ten = $this->input->post('ten');
		$dienthoai = $this->input->post('dienthoai');
		$diachi = $this->input->post('diachi');
		if(!$ten) {
			echo json_encode(['success'=>false, 'msg'=>'Chưa nhập tên khách hàng']); exit;
		}
		$this->db->insert('khachhang', [
			'ten'=>$ten,
			'dienthoai'=>$dienthoai,
			'diachi'=>$diachi
		]);
		$id = $this->db->insert_id();
		echo json_encode([
			'success'=>true,
			'msg'=>'Đã thêm khách hàng!',
			'id'=>$id,
			'ten'=>$ten,
			'dienthoai'=>$dienthoai
		]);
	}

	// Thêm khách hàng
	public function add() {
		if($this->input->post()) {
			$this->db->insert('khachhang', [
				'ten' => $this->input->post('ten'),
				'dienthoai' => $this->input->post('dienthoai'),
				'diachi' => $this->input->post('diachi'),
			]);
			redirect('khachhang');
		}
		$this->load->view('template/header');
		$this->load->view('template/topnav');
		$this->load->view('khachhang/add');
		$this->load->view('template/footer');
	}
	// Sửa khách hàng
	public function edit($id = 0) {
		$kh = $this->db->get_where('khachhang', ['id' => $id])->row();
		if(!$kh) show_404();
	
		if($this->input->post()) {
			$this->db->where('id', $id)->update('khachhang', [
				'ten' => $this->input->post('ten'),
				'dienthoai' => $this->input->post('dienthoai'),
				'diachi' => $this->input->post('diachi'),
			]);
			redirect('khachhang');
		}
	
		$data['kh'] = $kh;
		$this->load->view('template/header');
		$this->load->view('template/topnav'); // hoặc sidebar nếu dùng sidebar
		$this->load->view('khachhang/edit', $data);
		$this->load->view('template/footer');
	}
	
	// Xoá khách hàng
	public function delete($id = 0) {
		$this->db->where('id', $id)->delete('khachhang');
		redirect('khachhang');
	}

	public function autocomplete() {
		$term = $this->input->get('q');
		$this->db->like('ten', $term);
		$this->db->or_like('dienthoai', $term);
		$rs = $this->db->get('khachhang')->result();
		$data = [];
		foreach($rs as $row){
			$data[] = [
				'id' => $row->id,
				'ten' => $row->ten,
				'dienthoai' => $row->dienthoai
			];
		}
		echo json_encode($data);
	}


}
