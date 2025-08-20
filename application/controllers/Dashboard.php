<?php
defined('BASEPATH') OR exit('No direct script access allowed');

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
		$this->load->view('template/header');
		$this->load->view('template/topnav');  // hoặc sidebar nếu bạn dùng layout sidebar
		$this->load->view('dashboard/index');
		$this->load->view('template/footer');
	}
}
