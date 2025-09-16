<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @property Sanpham_model $Sanpham_model
 * @property CI_Session $session
 * @property CI_Input $input
 * @property CI_Pagination $pagination
 * @property CI_Output $output
 * @property CI_DB_query_builder $db
 * @property Actionlog_model $Actionlog_model
 */
class Sanpham extends CI_Controller
{
    public function __construct() {
        parent::__construct();
        $this->load->model('Sanpham_model');
        $this->load->library(['session', 'pagination']);
        $this->load->helper('url');
        if(!$this->session->userdata('user_id')) {
            redirect('auth/login');
        }
    }

    private function render(string $view, array $data = []) {
        $data['title']  = $data['title']  ?? 'MENU BÁNH TRUNG THU';
        $data['active'] = $data['active'] ?? 'sanpham';
        $this->load->view('templates/header',  $data);
        $this->load->view('templates/navbar',  $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view($view,               $data);
        $this->load->view('templates/footer');
    }

    // Danh sách sản phẩm
    public function index() {
        $keyword = $this->input->get('keyword', true);
        $chietkhau = $this->input->get('chietkhau', true);
        $perPage = 20;
        $offset = (int)$this->input->get('per_page');

        $total = $this->Sanpham_model->count_all($keyword, $chietkhau);

        $config = [
            'base_url'             => site_url('sanpham/index'),
            'total_rows'           => $total,
            'per_page'             => $perPage,
            'page_query_string'    => true,
            'query_string_segment' => 'per_page',
            'reuse_query_string'   => true,
            'full_tag_open'  => '<nav><ul class="pagination justify-content-center">',
            'full_tag_close' => '</ul></nav>',
            'num_tag_open'   => '<li class="page-item">',
            'num_tag_close'  => '</li>',
            'cur_tag_open'   => '<li class="page-item active"><span class="page-link">',
            'cur_tag_close'  => '</span></li>',
            'next_tag_open'  => '<li class="page-item">',
            'next_tag_close' => '</li>',
            'prev_tag_open'  => '<li class="page-item">',
            'prev_tag_close' => '</li>',
            'first_tag_open' => '<li class="page-item">',
            'first_tag_close'=> '</li>',
            'last_tag_open'  => '<li class="page-item">',
            'last_tag_close' => '</li>',
            'attributes'     => ['class' => 'page-link'],
        ];
        $this->pagination->initialize($config);

        $list = $this->Sanpham_model->get_all($keyword, $chietkhau, $perPage, $offset);

        $user_id = $this->session->userdata('user_id');
        $user_role = null;
        if ($user_id) {
            $user = $this->db->get_where('users', ['id' => $user_id])->row();
            $user_role = $user ? $user->role : null;
        }
        $data = [
            'title'      => 'Danh sách sản phẩm',
            'active'     => 'sanpham',
            'list'       => $list,
            'pagination' => $this->pagination->create_links(),
            'keyword'    => $keyword,
            'chietkhau'  => $chietkhau,
            'user_role'  => $user_role,
            'offset'     => $offset,
        ];

        $this->render('sanpham/index', $data);
    }

    // Thêm sản phẩm (AJAX)
    public function ajax_add() {
        if (!$this->input->is_ajax_request() || $this->input->method() !== 'post') show_404();
        $this->output->set_content_type('application/json');

        $ma_sp  = strtoupper(trim($this->input->post('ma_sp', true))); // chuyển in hoa
        $ten_sp = trim($this->input->post('ten_sp', true));
        $gia    = (int)$this->input->post('gia', true);
        $co_chiet_khau = (int)$this->input->post('co_chiet_khau', true);
        $combo = (int)$this->input->post('combo', true);

        if ($ma_sp === '' || $ten_sp === '') {
            echo json_encode(['success'=>false, 'msg'=>'Vui lòng nhập đầy đủ thông tin!']); return;
        }

        // Kiểm tra trùng mã sản phẩm
        if ($this->Sanpham_model->ma_sp_exists($ma_sp)) {
            echo json_encode(['success'=>false, 'msg'=>'Mã sản phẩm đã tồn tại!']); return;
        }

            $data_new = [
                'ma_sp'  => $ma_sp,
                'ten_sp' => $ten_sp,
                'gia'    => $gia,
                'co_chiet_khau' => $co_chiet_khau,
                'combo' => $combo,
            ];
            $id = $this->Sanpham_model->insert($data_new);
            $this->load->model('Actionlog_model');
            $user_id = $this->session->userdata('user_id');
            $this->Actionlog_model->log($user_id, 'add', 'sanpham', $id, null, json_encode($data_new, JSON_UNESCAPED_UNICODE));
            echo json_encode(['success'=>true, 'msg'=>'Đã thêm sản phẩm!']);
    }

    // Sửa sản phẩm (AJAX)
    public function ajax_edit() {
        $user_id = $this->session->userdata('user_id');
        $user = $this->db->get_where('users', ['id' => $user_id])->row();
        if (!$user || $user->role !== 'admin') {
            $this->output->set_content_type('application/json');
            echo json_encode(['success'=>false,'msg'=>'Bạn không có quyền sửa sản phẩm!']);
            return;
        }
        if (!$this->input->is_ajax_request() || $this->input->method() !== 'post') show_404();
        $this->output->set_content_type('application/json');

        $id     = (int)$this->input->post('id', true);
        $ma_sp  = trim($this->input->post('ma_sp', true));
        $ten_sp = trim($this->input->post('ten_sp', true));
        $gia    = (int)$this->input->post('gia', true);
        $combo = (int)$this->input->post('combo', true);
        $co_chiet_khau = (int)$this->input->post('co_chiet_khau', true);
       

        if ($id <= 0 || $ma_sp === '' || $ten_sp === '') {
            echo json_encode(['success'=>false, 'msg'=>'Thiếu thông tin!']); return;
        }

        // Kiểm tra trùng mã sản phẩm (trừ chính nó)
        if ($this->Sanpham_model->ma_sp_exists($ma_sp, $id)) {
            echo json_encode(['success'=>false, 'msg'=>'Mã sản phẩm đã tồn tại!']); return;
        }

            $row_before = $this->Sanpham_model->get_by_id($id);
            $data_new = [
                'ma_sp'  => $ma_sp,
                'ten_sp' => $ten_sp,
                'gia'    => $gia,
                'combo' => $combo,
                'co_chiet_khau' => $co_chiet_khau,
            ];
            $this->Sanpham_model->update($id, $data_new);
            $row_after = $this->Sanpham_model->get_by_id($id);
            $this->load->model('Actionlog_model');
            $user_id = $this->session->userdata('user_id');
            $this->Actionlog_model->log($user_id, 'edit', 'sanpham', $id, json_encode($row_before, JSON_UNESCAPED_UNICODE), json_encode($row_after, JSON_UNESCAPED_UNICODE));
            echo json_encode(['success'=>true, 'msg'=>'Đã cập nhật sản phẩm!']);
    }

    // Xoá sản phẩm (AJAX)
    public function ajax_delete() {
        $user_id = $this->session->userdata('user_id');
        $user = $this->db->get_where('users', ['id' => $user_id])->row();
        if (!$user || $user->role !== 'admin') {
            $this->output->set_content_type('application/json');
            echo json_encode(['success'=>false,'msg'=>'Bạn không có quyền xoá sản phẩm!']);
            return;
        }
        if (!$this->input->is_ajax_request() || $this->input->method() !== 'post') show_404();
        $this->output->set_content_type('application/json');

        $id = (int)$this->input->post('id', true);
        if ($id <= 0) {
            echo json_encode(['success'=>false, 'msg'=>'Thiếu ID sản phẩm!']); return;
        }

            $row_before = $this->Sanpham_model->get_by_id($id);
            $this->Sanpham_model->delete($id);
            $this->load->model('Actionlog_model');
            $user_id = $this->session->userdata('user_id');
            $this->Actionlog_model->log($user_id, 'delete', 'sanpham', $id, json_encode($row_before, JSON_UNESCAPED_UNICODE), null);
            echo json_encode(['success'=>true, 'msg'=>'Đã xoá sản phẩm!']);
    }

    // API kiểm tra trùng mã sản phẩm (AJAX)
    public function check_ma_sp() {
        if (!$this->input->is_ajax_request()) show_404();
        $this->output->set_content_type('application/json');

        $ma_sp = trim($this->input->get_post('ma_sp', true));
        $id    = (int)$this->input->get_post('id', true); // dùng cho edit

        if ($ma_sp === '') {
            echo json_encode(['exists'=>false]); return;
        }

        $exists = $this->Sanpham_model->ma_sp_exists($ma_sp, $id);
        echo json_encode(['exists'=>$exists]);
    }

    // API lấy 1 sản phẩm (AJAX)
    public function get($id = 0)
    {
        if (!$this->input->is_ajax_request()) show_404();
        $id = (int)$id;
        $row = $this->Sanpham_model->get_by_id($id);
        if (!$row) {
            echo json_encode(['success' => false, 'msg' => 'Không tìm thấy sản phẩm']);
            return;
        }
        echo json_encode([
            'success' => true,
            'data' => [
                'id'             => (int)$row->id,
                'ma_sp'          => $row->ma_sp,
                'ten_sp'         => $row->ten_sp,
                'gia'            => $row->gia,
                'combo'          => (int)$row->combo,
                'co_chiet_khau'  => (int)$row->co_chiet_khau,
            ]
        ]);
    }
}