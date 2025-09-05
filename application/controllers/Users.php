<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Users_model');
        $this->load->library('session');
        $this->load->helper(['url', 'form']);
        // Kiểm tra đăng nhập
        if (!$this->session->userdata('user_id')) {
            redirect('auth/login');
        }
        // Kiểm tra quyền admin
        $user = $this->Users_model->get_by_id($this->session->userdata('user_id'));
        if (!$user || $user->role !== 'admin') {
            show_error('Bạn không có quyền truy cập trang này.', 403, 'Không có quyền');
        }
        $this->current_user = $user;
    }

    private function render(string $view, array $data = []) {
        $data['title']  = $data['title']  ?? 'QUẢN LÝ TÀI KHOẢN';
        $data['active'] = $data['active'] ?? 'users';
        $this->load->view('templates/header',  $data);
        $this->load->view('templates/navbar',  $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view($view,               $data);
        $this->load->view('templates/footer');
    }

    public function index() {
        $list = $this->Users_model->get_all();
        $data = [
            'list' => $list,
            'current_user' => $this->current_user
        ];
        $this->render('users/index', $data);
    }

    public function add() {
        if ($this->input->method() === 'post') {
            $username = trim($this->input->post('username'));
            // Chuyển thành chữ thường, không dấu, viết liền
            $username = strtolower(iconv('UTF-8', 'ASCII//TRANSLIT', $username));
            $username = preg_replace('/[^a-z0-9]/', '', $username);
            $password = $this->input->post('password');
            $role = $this->input->post('role') ?? 'super';
            if ($this->Users_model->username_exists($username)) {
                echo json_encode(['success'=>false, 'msg'=>'Tài khoản đã tồn tại!']); return;
            }
            $id = $this->Users_model->insert([
                'username' => $username,
                'password' => md5($password),
                'role' => $role
            ]);
            echo json_encode(['success'=>true, 'msg'=>'Đã thêm tài khoản!', 'id'=>$id]);
        }
    }

    public function edit() {
        if ($this->input->method() === 'post') {
            $id = (int)$this->input->post('id');
            $user = $this->Users_model->get_by_id($id);
            if (!$user) {
                echo json_encode(['success'=>false, 'msg'=>'Không tìm thấy tài khoản!']); return;
            }
            $data = [];
            if ($this->input->post('password')) {
                $data['password'] = md5($this->input->post('password'));
            }
            if ($this->current_user->role === 'admin' && $this->input->post('role')) {
                $data['role'] = $this->input->post('role');
            }
            if (!empty($data)) {
                $this->Users_model->update($id, $data);
            }
            echo json_encode(['success'=>true, 'msg'=>'Đã cập nhật tài khoản!']);
        }
    }

    public function delete() {
        if ($this->input->method() === 'post') {
            $id = (int)$this->input->post('id');
            if ($id == $this->current_user->id) {
                echo json_encode(['success'=>false, 'msg'=>'Không thể xoá tài khoản của chính bạn!']); return;
            }
            $this->Users_model->delete($id);
            echo json_encode(['success'=>true, 'msg'=>'Đã xoá tài khoản!']);
        }
    }

    public function check_username() {
        $username = $this->input->get('username');
        $exists = $this->Users_model->username_exists($username);
        $this->output->set_content_type('application/json')->set_output(json_encode(['exists' => $exists]));
    }
}
