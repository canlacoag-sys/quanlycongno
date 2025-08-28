<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Khachhang extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->library(['session', 'pagination']);
		$this->load->helper(['url', 'security']);
		if (!$this->session->userdata('user_id')) redirect('auth/login');
	}

	/* ==================== Helpers (1 bản duy nhất) ==================== */

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
	// Kiểm tra 1 số có tồn tại ở KH khác không (đang lưu CSV nên dùng FIND_IN_SET)
	private function phone_exists(string $phone, int $excludeId = 0): bool {
		$this->db->from('khachhang');
		if ($excludeId > 0) $this->db->where('id !=', $excludeId);
		$this->db->where("FIND_IN_SET(".$this->db->escape($phone).", REPLACE(dienthoai,' ','')) >", 0, false);
		return $this->db->count_all_results() > 0;
	}

	/* ==================== View wrapper ==================== */
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

	/* ==================== Actions ==================== */

	public function index(): void
	{
		$keyword = trim((string)$this->input->get('keyword', true));
		$perPage = 30;

		if ($keyword !== '') {
			$this->db->group_start()
				->like('ten', $keyword)
				->or_like('dienthoai', $keyword)
				->or_like('diachi', $keyword)
			->group_end();
		}
		$total = $this->db->count_all_results('khachhang');

		$config = [
			'base_url'             => site_url('khachhang/index'),
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

		$offset = (int)$this->input->get('per_page');

		if ($keyword !== '') {
			$this->db->group_start()
				->like('ten', $keyword)
				->or_like('dienthoai', $keyword)
				->or_like('diachi', $keyword)
			->group_end();
		}
		$list = $this->db->order_by('id', 'DESC')
						 ->limit($perPage, $offset)
						 ->get('khachhang')->result();

		$data = [
			'title'      => 'Khách hàng',
			'active'     => 'khachhang',
			'list'       => $list,
			'pagination' => $this->pagination->create_links(),
			'keyword'    => $keyword,
		];
		$this->render('khachhang/index', $data);
	}

	/** Thêm nhanh (AJAX) – có check trùng */
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
		foreach ($phones as $p) if ($this->phone_exists($p, 0)) $dups[] = $p;
		if ($dups) { echo json_encode(['success'=>false,'msg'=>'Số đã tồn tại: '.implode(', ', $dups)]); return; }

		$payload = ['ten'=>$ten, 'dienthoai'=>implode(',', $phones), 'diachi'=>$diachi];
		if ($this->db->field_exists('created_at','khachhang')) $payload['created_at'] = date('Y-m-d H:i:s');

		$this->db->insert('khachhang', $payload);
		$id = (int)$this->db->insert_id();
		echo json_encode(['success'=>true,'msg'=>'Đã thêm khách hàng!','id'=>$id,'ten'=>$ten,'dienthoai'=>implode(',', $phones),'diachi'=>$diachi]);
	}

	/** Thêm qua trang riêng – có check trùng */
	public function add(): void
	{
		if ($this->input->method() === 'post') {
			$ten    = $this->input->post('ten', true);
			$csv    = (string)$this->input->post('dienthoai', true);
			$diachi = $this->input->post('diachi', true);

			$phones = $this->parse_phones_csv($csv);
			$dups = [];
			foreach ($phones as $p) if ($this->phone_exists($p, 0)) $dups[] = $p;
			if ($dups) {
				$this->session->set_flashdata('error', 'Số đã tồn tại: '.implode(', ', $dups));
				$this->session->set_flashdata('old', ['ten'=>$ten,'diachi'=>$diachi,'dienthoai'=>implode(',', $phones)]);
				redirect('khachhang/add'); return;
			}

			$payload = ['ten'=>$ten, 'dienthoai'=>implode(',', $phones), 'diachi'=>$diachi];
			if ($this->db->field_exists('created_at','khachhang')) $payload['created_at'] = date('Y-m-d H:i:s');
			$this->db->insert('khachhang', $payload);
			redirect('khachhang'); return;
		}

		$data = ['title'=>'Thêm khách hàng','active'=>'khachhang','old'=>$this->session->flashdata('old') ?? [],'error'=>$this->session->flashdata('error') ?? ''];
		$this->render('khachhang/add', $data);
	}

	/** Sửa – có check trùng & giữ lại tag */
	public function edit(int $id = 0): void
	{
		$kh = $this->db->get_where('khachhang', ['id'=>$id])->row();
		if (!$kh) show_404();

		if ($this->input->method() === 'post') {
			$ten    = $this->input->post('ten', true);
			$diachi = $this->input->post('diachi', true);
			$csv    = (string)$this->input->post('dienthoai', true);
			$phones = $this->parse_phones_csv($csv);

			$dups = [];
			foreach ($phones as $p) if ($this->phone_exists($p, $id)) $dups[] = $p;
			if ($dups) {
				$this->session->set_flashdata('error', 'Số đã tồn tại: '.implode(', ', $dups));
				$this->session->set_flashdata('old', ['ten'=>$ten,'diachi'=>$diachi,'dienthoai'=>implode(',', $phones)]);
				redirect('khachhang/edit/'.$id); return;
			}

			$payload = ['ten'=>$ten, 'dienthoai'=>implode(',', $phones), 'diachi'=>$diachi];
			if ($this->db->field_exists('updated_at','khachhang')) $payload['updated_at'] = date('Y-m-d H:i:s');

			$this->db->where('id', $id)->update('khachhang', $payload);
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
		$this->db->where('id', (int)$id)->delete('khachhang');
		if ($this->input->is_ajax_request()) {
			$this->output->set_content_type('application/json')->set_output(json_encode(['success'=>true])); return;
		}
		redirect('khachhang');
	}

	public function autocomplete(): void
	{
		$term = trim((string)$this->input->get('q', true));
		if ($term !== '') {
			$this->db->group_start()->like('ten', $term)->or_like('dienthoai', $term)->group_end();
		}
		$rs = $this->db->limit(20)->get('khachhang')->result();
		$out = [];
		foreach ($rs as $row) $out[] = ['id'=>(int)$row->id,'ten'=>$row->ten,'dienthoai'=>$row->dienthoai];
		$this->output->set_content_type('application/json')->set_output(json_encode($out));
	}

	/** API cho JS kiểm tra trùng */
	public function check_phone()
	{
		if (!$this->input->is_ajax_request()) show_404();
		$phone   = $this->normalize_phone($this->input->get('phone', true));
		$exclude = (int)$this->input->get('exclude_id');
		$exists  = $phone !== '' ? $this->phone_exists($phone, $exclude) : false;
		$this->output->set_content_type('application/json')->set_output(json_encode(['exists'=>$exists]));
	}
	
	/** Lấy dữ liệu 1 khách hàng (AJAX) */
	public function get($id = 0)
	{
		if (!$this->input->is_ajax_request()) show_404();
		$this->output->set_content_type('application/json');

		$id = (int)$id;
		if ($id <= 0) { echo json_encode(['success'=>false,'msg'=>'ID không hợp lệ']); return; }

		$row = $this->db->get_where('khachhang', ['id'=>$id])->row();
		if (!$row) { echo json_encode(['success'=>false,'msg'=>'Không tìm thấy khách hàng']); return; }

		echo json_encode([
			'success' => true,
			'data' => [
				'id'        => (int)$row->id,
				'ten'       => (string)($row->ten ?? ''),
				'dienthoai' => (string)($row->dienthoai ?? ''), // CSV
				'diachi'    => (string)($row->diachi ?? '')
			]
		]);
	}
}
