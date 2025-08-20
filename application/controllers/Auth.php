<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->database();
		$this->load->library('session');
		$this->load->helper('url');
	}

	public function login() {
		if($this->input->post()) {
			$username = $this->input->post('username');
			$password = md5($this->input->post('password'));

			$user = $this->db->get_where('users', ['username' => $username, 'password' => $password])->row();
			if($user) {
				$this->session->set_userdata('user_id', $user->id);
				redirect('dashboard');
			} else {
				$data['error'] = "Sai tài khoản hoặc mật khẩu!";
			}
		}
		$this->load->view('auth/login', isset($data) ? $data : []);
	}

	public function logout() {
		$this->session->unset_userdata('user_id');
		redirect('auth/login');
	}
}
