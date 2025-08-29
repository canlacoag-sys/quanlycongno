<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
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

    // ...giữ nguyên các hàm normalize_phone, parse_phones_csv...

    private function render(string $view, array $data = []): void
    {
        $data['title']  = $data['title']  ?? 'Khách hàng';
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
        $perPage = 50;
        $offset = (int)$this->input->get('per_page');

        $total = $this->Khachhang_model->count_all($keyword);

        $config = [
            'base_url'             => site_url('khachhang/index'),
            'total_rows'           => $total,
            'per_page'             => $perPage,
            'page_query_string'    => true,
            'query_string_segment' => 'per_page',
            'reuse_query_string'   => true,
            // ...các config phân trang khác...
        ];
        $this->pagination->initialize($config);

        $list = $this->Khachhang_model->get_all($keyword, $perPage, $offset);

        $data = [
            'title'      => 'Khách hàng',
            'active'     => 'khachhang',
            'list'       => $list,
            'pagination' => $this->pagination->create_links(),
            'keyword'    => $keyword,
        ];
        $this->render('khachhang/index', $data);
    }

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

    public function add(): void
    {
        if ($this->input->method() === 'post') {
            $ten    = $this->input->post('ten', true);
            $csv    = (string)$this->input->post('dienthoai', true);
            $diachi = $this->input->post('diachi', true);

            $phones = $this->parse_phones_csv($csv);
            $dups = [];
            foreach ($phones as $p) if ($this->Khachhang_model->phone_exists($p, 0)) $dups[] = $p;
            if ($dups) {
                $this->session->set_flashdata('error', 'Số đã tồn tại: '.implode(', ', $dups));
                $this->session->set_flashdata('old', ['ten'=>$ten,'diachi'=>$diachi,'dienthoai'=>implode(',', $phones)]);
                redirect('khachhang/add'); return;
            }

            $payload = ['ten'=>$ten, 'dienthoai'=>implode(',', $phones), 'diachi'=>$diachi];
            if ($this->db->field_exists('created_at','khachhang')) $payload['created_at'] = date('Y-m-d H:i:s');
            $this->Khachhang_model->insert($payload);
            redirect('khachhang'); return;
        }

        $data = ['title'=>'Thêm khách hàng','active'=>'khachhang','old'=>$this->session->flashdata('old') ?? [],'error'=>$this->session->flashdata('error') ?? ''];
        $this->render('khachhang/add', $data);
    }

    public function edit(int $id = 0): void
    {
        $kh = $this->Khachhang_model->get_by_id($id);
        if (!$kh) show_404();

        if ($this->input->method() === 'post') {
            $ten    = $this->input->post('ten', true);
            $diachi = $this->input->post('diachi', true);
            $csv    = (string)$this->input->post('dienthoai', true);
            $phones = $this->parse_phones_csv($csv);

            $dups = [];
            foreach ($phones as $p) if ($this->Khachhang_model->phone_exists($p, $id)) $dups[] = $p;
            if ($dups) {
                $this->session->set_flashdata('error', 'Số đã tồn tại: '.implode(', ', $dups));
                $this->session->set_flashdata('old', ['ten'=>$ten,'diachi'=>$diachi,'dienthoai'=>implode(',', $phones)]);
                redirect('khachhang/edit/'.$id); return;
            }

            $payload = ['ten'=>$ten, 'dienthoai'=>implode(',', $phones), 'diachi'=>$diachi];
            if ($this->db->field_exists('updated_at','khachhang')) $payload['updated_at'] = date('Y-m-d H:i:s');

            $this->Khachhang_model->update($id, $payload);
            redirect('khachhang'); return;
        }

        $data = [
            'title'=>'Sửa khách hàng','active'=>'khachhang','kh'=>$kh,
            'old'=>$this->session->flashdata('old') ?? [], 'error'=>$this->session->flashdata('error') ?? '',
        ];
        $this->render('khachhang/edit', $data);
    }

    public function delete(int $id = 0): void
    {
        $this->Khachhang_model->delete($id);
        if ($this->input->is_ajax_request()) {
            $this->output->set_content_type('application/json')->set_output(json_encode(['success'=>true])); return;
        }
        redirect('khachhang');
    }

    public function autocomplete(): void
    {
        $term = trim((string)$this->input->get('q', true));
        $rs = $this->Khachhang_model->autocomplete($term);
        $out = [];
        foreach ($rs as $row) $out[] = ['id'=>(int)$row->id,'ten'=>$row->ten,'dienthoai'=>$row->dienthoai];
        $this->output->set_content_type('application/json')->set_output(json_encode($out));
    }

    public function check_phone()
    {
        if (!$this->input->is_ajax_request()) show_404();
        $phone   = $this->normalize_phone($this->input->get('phone', true));
        $exclude = (int)$this->input->get('exclude_id');
        $exists  = $phone !== '' ? $this->Khachhang_model->phone_exists($phone, $exclude) : false;
        $this->output->set_content_type('application/json')->set_output(json_encode(['exists'=>$exists]));
    }

    public function get($id = 0)
    {
        if (!$this->input->is_ajax_request()) show_404();
        $this->output->set_content_type('application/json');

        $id = (int)$id;
        if ($id <= 0) { echo json_encode(['success'=>false,'msg'=>'ID không hợp lệ']); return; }

        $row = $this->Khachhang_model->get_by_id($id);
        if (!$row) { echo json_encode(['success'=>false,'msg'=>'Không tìm thấy khách hàng']); return; }

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
}