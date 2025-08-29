<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @property CI_DB_query_builder $db
 * @property CI_Session $session
 * @property CI_Input $input
 * @property CI_Pagination $pagination
 * @property CI_Output $output
 */

class Sanpham extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->library(['session', 'pagination']);
        $this->load->helper('url');
        if(!$this->session->userdata('user_id')) {
            redirect('auth/login');
        }
    }

    // Hàm render giao diện chuẩn AdminLTE
    private function render(string $view, array $data = []) {
        $data['title']  = $data['title']  ?? 'Sản phẩm';
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

        // Đếm tổng số kết quả (áp dụng filter)
        if ($chietkhau !== '' && $chietkhau !== null) {
            $this->db->where('co_chiet_khau', (int)$chietkhau);
        }
        if ($keyword) {
            $this->db->group_start()
                ->like('ma_sp', $keyword)
                ->or_like('ten_sp', $keyword)
                ->group_end();
        }
        $total = $this->db->count_all_results('sanpham');

        // Phân trang
        $config = [
            'base_url'             => site_url('sanpham/index'),
            'total_rows'           => $total,
            'per_page'             => 50,
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

        $offset = (int)$this->input->get('per_page');

        // Lấy dữ liệu (áp dụng filter)
        if ($chietkhau !== '' && $chietkhau !== null) {
            $this->db->where('co_chiet_khau', (int)$chietkhau);
        }
        if ($keyword) {
            $this->db->group_start()
                ->like('ma_sp', $keyword)
                ->or_like('ten_sp', $keyword)
                ->group_end();
        }
        $list = $this->db->order_by('id', 'DESC')
                         ->limit($config['per_page'], $offset)
                         ->get('sanpham')->result();

        $data = [
            'title'      => 'Danh sách sản phẩm',
            'active'     => 'sanpham',
            'list'       => $list,
            'pagination' => $this->pagination->create_links(),
            'keyword'    => $keyword,
            'chietkhau'  => $chietkhau,
        ];

        $this->render('sanpham/index', $data);
    }

    /** Thêm sản phẩm (AJAX) */
    public function ajax_add() {
        if (!$this->input->is_ajax_request() || $this->input->method() !== 'post') show_404();
        $this->output->set_content_type('application/json');

        $ma_sp  = trim($this->input->post('ma_sp', true));
        $ten_sp = trim($this->input->post('ten_sp', true));
        $gia    = (int)$this->input->post('gia', true);
        $co_chiet_khau = (int)$this->input->post('co_chiet_khau', true);

        if ($ma_sp === '' || $ten_sp === '') {
            echo json_encode(['success'=>false, 'msg'=>'Vui lòng nhập đầy đủ thông tin!']); return;
        }

        // Kiểm tra trùng mã sản phẩm
        if ($this->db->where('ma_sp', $ma_sp)->count_all_results('sanpham') > 0) {
            echo json_encode(['success'=>false, 'msg'=>'Mã sản phẩm đã tồn tại!']); return;
        }

        $this->db->insert('sanpham', [
            'ma_sp'  => $ma_sp,
            'ten_sp' => $ten_sp,
            'gia'    => $gia,
            'co_chiet_khau' => $co_chiet_khau,
        ]);
        echo json_encode(['success'=>true, 'msg'=>'Đã thêm sản phẩm!']);
    }

    /** Sửa sản phẩm (AJAX) */
    public function ajax_edit() {
        if (!$this->input->is_ajax_request() || $this->input->method() !== 'post') show_404();
        $this->output->set_content_type('application/json');

        $id     = (int)$this->input->post('id', true);
        $ma_sp  = trim($this->input->post('ma_sp', true));
        $ten_sp = trim($this->input->post('ten_sp', true));
        $gia    = (int)$this->input->post('gia', true);
        $co_chiet_khau = (int)$this->input->post('co_chiet_khau', true);

        if ($id <= 0 || $ma_sp === '' || $ten_sp === '') {
            echo json_encode(['success'=>false, 'msg'=>'Thiếu thông tin!']); return;
        }

        // Kiểm tra trùng mã sản phẩm (trừ chính nó)
        if ($this->db->where('ma_sp', $ma_sp)->where('id !=', $id)->count_all_results('sanpham') > 0) {
            echo json_encode(['success'=>false, 'msg'=>'Mã sản phẩm đã tồn tại!']); return;
        }

        $this->db->where('id', $id)->update('sanpham', [
            'ma_sp'  => $ma_sp,
            'ten_sp' => $ten_sp,
            'gia'    => $gia,
            'co_chiet_khau' => $co_chiet_khau,
        ]);
        echo json_encode(['success'=>true, 'msg'=>'Đã cập nhật sản phẩm!']);
    }

    /** Xoá sản phẩm (AJAX) */
    public function ajax_delete() {
        if (!$this->input->is_ajax_request() || $this->input->method() !== 'post') show_404();
        $this->output->set_content_type('application/json');

        $id = (int)$this->input->post('id', true);
        if ($id <= 0) {
            echo json_encode(['success'=>false, 'msg'=>'Thiếu ID sản phẩm!']); return;
        }

        $this->db->where('id', $id)->delete('sanpham');
        echo json_encode(['success'=>true, 'msg'=>'Đã xoá sản phẩm!']);
    }

    /** API kiểm tra trùng mã sản phẩm (AJAX) */
    public function check_ma_sp() {
        if (!$this->input->is_ajax_request()) show_404();
        $this->output->set_content_type('application/json');

        $ma_sp = trim($this->input->get_post('ma_sp', true));
        $id    = (int)$this->input->get_post('id', true); // dùng cho edit

        if ($ma_sp === '') {
            echo json_encode(['exists'=>false]); return;
        }

        $this->db->where('ma_sp', $ma_sp);
        if ($id > 0) $this->db->where('id !=', $id);
        $exists = $this->db->count_all_results('sanpham') > 0;

        echo json_encode(['exists'=>$exists]);
    }

    /** API lấy 1 sản phẩm (AJAX) */
    public function get($id = 0)
    {
        if (!$this->input->is_ajax_request()) show_404();
        $id = (int)$id;
        $row = $this->db->get_where('sanpham', ['id' => $id])->row();
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
                'co_chiet_khau'  => (int)$row->co_chiet_khau,
            ]
        ]);
    }
}