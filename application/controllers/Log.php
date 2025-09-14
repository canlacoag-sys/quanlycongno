<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Log extends CI_Controller {
    public function index() {
        $this->load->model('Actionlog_model');
        $logs = $this->Actionlog_model->get_all_logs();

        // Lấy danh sách user để hiển thị tên thay vì user_id
        $users = [];
        $user_rows = $this->db->get('users')->result();
        foreach ($user_rows as $u) {
            $users[$u->id] = $u->username;
        }

        // Chuẩn bị dữ liệu cho view
        foreach ($logs as &$log) {
            $log['username'] = isset($users[$log['user_id']]) ? $users[$log['user_id']] : $log['user_id'];
        }

        $data = [
            'logs' => $logs
        ];

        $data['title']  = 'QuẢN LÝ NHẬT KÝ';
		$data['active'] = 'log';
        
        $this->load->view('templates/header', $data);
		$this->load->view('templates/navbar', $data);   // ✅ nhớ include navbar
		$this->load->view('templates/sidebar', $data);
		$this->load->view('log/index', $data);
		$this->load->view('templates/footer');
        
    }
}
