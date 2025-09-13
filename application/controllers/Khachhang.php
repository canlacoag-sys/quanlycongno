<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @property Sanpham_model $Sanpham_model
 * @property Khachhang_model $Khachhang_model
 * @property CI_Session $session
 * @property CI_Input $input
 * @property CI_Pagination $pagination
 * @property CI_Output $output
 */
class Khachhang extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Khachhang_model');
        $this->load->library(['session', 'pagination']);
        $this->load->helper(['url', 'security']);
        if (!$this->session->userdata('user_id')) redirect('auth/login');
    }

    // Chuẩn hoá số: bỏ ký tự lạ, +84/84 -> 0
    private function normalize_phone(?string $raw): string {
        $raw = trim((string)$raw);
        if ($raw === '') return '';
        $p = preg_replace('/[^0-9\+]/', '', $raw);
        if (strpos($p, '+84') === 0) $p = '0'.substr($p, 3);
        if (strpos($p, '84')  === 0 && strlen($p) >= 10) $p = '0'.substr($p, 2);
        return $p;
    }
    // Tách CSV -> mảng số đã chuẩn hoá, unique, bỏ rỗng
    private function parse_phones_csv(string $csv): array {
        $arr = array_map('trim', explode(',', $csv));
        $arr = array_map(function($p){ return $this->normalize_phone($p); }, $arr);
        $arr = array_filter($arr, function($p){ return $p !== ''; });
        return array_values(array_unique($arr));
    }

    private function render(string $view, array $data = []): void
    {
        $data['title']  = $data['title']  ?? 'DANH SÁCH KHÁCH HÀNG';
        $data['active'] = $data['active'] ?? 'khachhang';
        $this->load->view('templates/header',  $data);
        $this->load->view('templates/navbar',  $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view($view,               $data);
        $this->load->view('templates/footer');
    }

    public function index(): void
    {
        $keyword = trim((string)$this->input->get('keyword', true));
        $perPage = 20;
        $offset = (int)$this->input->get('per_page');

        $total = $this->Khachhang_model->count_all($keyword);

        $config = [
            'base_url'             => site_url('khachhang/index'),
            'total_rows'           => $total,
            'per_page'             => $perPage,
            'page_query_string'    => true,
            'query_string_segment' => 'per_page',
            'reuse_query_string'   => true,
            'full_tag_open'        => '<ul class="pagination justify-content-center">',
            'full_tag_close'       => '</ul>',
            'num_tag_open'         => '<li class="page-item">',
            'num_tag_close'        => '</li>',
            'cur_tag_open'         => '<li class="page-item active"><span class="page-link">',
            'cur_tag_close'        => '</span></li>',
            'next_tag_open'        => '<li class="page-item">',
            'next_tag_close'       => '</li>',
            'prev_tag_open'        => '<li class="page-item">',
            'prev_tag_close'       => '</li>',
            'first_tag_open'       => '<li class="page-item">',
            'first_tag_close'      => '</li>',
            'last_tag_open'        => '<li class="page-item">',
            'last_tag_close'       => '</li>',
            'attributes'           => ['class' => 'page-link'],
            'first_link'           => 'Đầu',
            'last_link'            => 'Cuối',
            'next_link'            => '&raquo;',
            'prev_link'            => '&laquo;',
        ];
        $this->pagination->initialize($config);

        $list = $this->Khachhang_model->get_all($keyword, $perPage, $offset);

        $user_id = $this->session->userdata('user_id');
        $user_role = null;
        if ($user_id) {
            $user = $this->db->get_where('users', ['id' => $user_id])->row();
            $user_role = $user ? $user->role : null;
        }
        $data = [
            'title'      => 'Khách hàng',
            'active'     => 'khachhang',
            'list'       => $list,
            'pagination' => $this->pagination->create_links(),
            'keyword'    => $keyword,
            'user_role'  => $user_role,
            'offset'     => $offset,
        ];
        $this->render('khachhang/index', $data);
    }

    // ================== AJAX CRUD ==================

    public function ajax_add(): void
    {
        if (!$this->input->is_ajax_request() || $this->input->method() !== 'post') show_404();
        $this->output->set_content_type('application/json');

        $ten    = trim($this->input->post('ten', true));
        $csv    = trim($this->input->post('dienthoai', true));
        $diachi = trim($this->input->post('diachi', true));
        if ($ten === '') { echo json_encode(['success'=>false,'msg'=>'Chưa nhập tên khách hàng']); return; }

        $phones = $this->parse_phones_csv($csv);
        $dups = [];
        foreach ($phones as $p) if ($this->Khachhang_model->phone_exists($p, 0)) $dups[] = $p;
        if ($dups) { echo json_encode(['success'=>false,'msg'=>'Số đã tồn tại: '.implode(', ', $dups)]); return; }

        $payload = ['ten'=>$ten, 'dienthoai'=>implode(',', $phones), 'diachi'=>$diachi];
        if ($this->db->field_exists('created_at','khachhang')) $payload['created_at'] = date('Y-m-d H:i:s');

        $id = $this->Khachhang_model->insert($payload);
        echo json_encode(['success'=>true,'msg'=>'Đã thêm khách hàng!','id'=>$id,'ten'=>$ten,'dienthoai'=>implode(',', $phones),'diachi'=>$diachi]);
    }

    public function ajax_edit(): void
    {
        if (!$this->input->is_ajax_request() || $this->input->method() !== 'post') show_404();
        $this->output->set_content_type('application/json');

        $id     = (int)$this->input->post('id', true);
        $ten    = trim($this->input->post('ten', true));
        $csv    = trim((string)$this->input->post('dienthoai', true));
        $diachi = trim($this->input->post('diachi', true));

        if ($id <= 0) { echo json_encode(['success'=>false,'msg'=>'ID không hợp lệ']); return; }
        if ($ten === '') { echo json_encode(['success'=>false,'msg'=>'Chưa nhập tên khách hàng']); return; }

        $phones = $this->parse_phones_csv($csv);
        $dups = [];
        foreach ($phones as $p) if ($this->Khachhang_model->phone_exists($p, $id)) $dups[] = $p;
        if ($dups) { echo json_encode(['success'=>false,'msg'=>'Số đã tồn tại: '.implode(', ', $dups)]); return; }

        $payload = ['ten'=>$ten, 'dienthoai'=>implode(',', $phones), 'diachi'=>$diachi];
        if ($this->db->field_exists('updated_at','khachhang')) $payload['updated_at'] = date('Y-m-d H:i:s');

        $this->Khachhang_model->update($id, $payload);
        echo json_encode(['success'=>true,'msg'=>'Đã cập nhật khách hàng','id'=>$id,'ten'=>$ten,'dienthoai'=>implode(',', $phones),'diachi'=>$diachi]);
    }

    public function ajax_delete()
    {
        if (!$this->input->is_ajax_request()) show_404();
        $id = (int)$this->input->post('id');
        if ($id <= 0) {
            echo json_encode(['success'=>false, 'msg'=>'ID không hợp lệ']);
            return;
        }
        $row = $this->Khachhang_model->get_by_id($id);
        if (!$row) {
            echo json_encode(['success'=>false, 'msg'=>'Không tìm thấy khách hàng']);
            return;
        }
        $this->Khachhang_model->delete($id);
        echo json_encode(['success'=>true]);
    }

    // ================== AJAX hỗ trợ ==================

    public function get()
    {
        if (!$this->input->is_ajax_request()) show_404();
        $this->output->set_content_type('application/json');

        $id = (int)$this->input->get('id', true);
        if ($id <= 0) {
            echo json_encode(['success'=>false,'msg'=>'ID không hợp lệ']);
            return;
        }

        $row = $this->Khachhang_model->get_by_id($id);
        if (!$row) {
            echo json_encode(['success'=>false,'msg'=>'Không tìm thấy khách hàng']);
            return;
        }

        echo json_encode([
            'success' => true,
            'data' => [
                'id'        => (int)$row->id,
                'ten'       => (string)($row->ten ?? ''),
                'dienthoai' => (string)($row->dienthoai ?? ''),
                'diachi'    => (string)($row->diachi ?? '')
            ]
        ]);
    }

    public function check_phone()
    {
        if (!$this->input->is_ajax_request()) show_404();
        $phone   = $this->normalize_phone($this->input->get('phone', true));
        $exclude = (int)$this->input->get('exclude_id');
        $exists  = $phone !== '' ? $this->Khachhang_model->phone_exists($phone, $exclude) : false;
        $this->output->set_content_type('application/json')->set_output(json_encode(['exists'=>$exists]));
    }

    public function autocomplete(): void
    {
        $term = trim((string)($this->input->get('term', true) ?? $this->input->get('q', true)));
        $rs = $this->Khachhang_model->autocomplete($term);
        $out = [];
        foreach ($rs as $row) $out[] = ['id'=>(int)$row->id,'ten'=>$row->ten,'dienthoai'=>$row->dienthoai];
        $this->output->set_content_type('application/json')->set_output(json_encode($out));
    }
}