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
		$data['title']  = 'Bảng điều khiển';
		$data['active'] = 'dashboard';
		
		$this->load->view('templates/header', $data);
		$this->load->view('templates/navbar', $data);   // ✅ nhớ include navbar
		$this->load->view('templates/sidebar', $data);
		$this->load->view('dashboard/index', $data);
		$this->load->view('templates/footer');
	}
}
