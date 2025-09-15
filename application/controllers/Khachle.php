<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @property Sanpham_model $Sanpham_model
 * @property Khachle_model $Khachle_model
 * @property CI_Session $session
 * @property CI_Input $input
 * @property CI_Pagination $pagination
 * @property CI_Output $output
 * @property CI_DB_query_builder $db
 * @property Actionlog_model $Actionlog_model
 */

class Khachle extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Khachle_model');
        $this->load->helper(['url', 'form']);
        $this->load->library('session');
        if(!$this->session->userdata('user_id')) redirect('auth/login');
    }

    private function render(string $view, array $data = []) {
        $data['title']  = $data['title']  ?? 'CHI TIẾT KHÁCH LẺ';
        $data['active'] = $data['active'] ?? 'khachle';
        $this->load->view('templates/header',  $data);
        $this->load->view('templates/navbar',  $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view($view,               $data);
        $this->load->view('templates/footer');
    }

    // Danh sách đơn khách lẻ
    public function index() {
        $this->load->library('pagination');
        $this->load->database();

        $keyword = $this->input->get('keyword', true);
        $perPage = 20;
        $segment = 3;

        // Đếm tổng số đơn khách lẻ (có thể lọc theo từ khóa)
        $total = $this->Khachle_model->count_all($keyword);

        $config['base_url'] = site_url('khachle/index');
        $config['total_rows'] = $total;
        $config['per_page'] = $perPage;
        $config['uri_segment'] = $segment;
        $config['reuse_query_string'] = true;

        // Bootstrap style
        $config['full_tag_open']   = '<nav><ul class="pagination justify-content-center mb-0">';
        $config['full_tag_close']  = '</ul></nav>';
        $config['first_link']      = 'Đầu';
        $config['last_link']       = 'Cuối';
        $config['first_tag_open']  = '<li class="page-item">';
        $config['first_tag_close'] = '</li>';
        $config['prev_link']       = '&laquo;';
        $config['prev_tag_open']   = '<li class="page-item">';
        $config['prev_tag_close']  = '</li>';
        $config['next_link']       = '&raquo;';
        $config['next_tag_open']   = '<li class="page-item">';
        $config['next_tag_close']  = '</li>';
        $config['last_tag_open']   = '<li class="page-item">';
        $config['last_tag_close']  = '</li>';
        $config['cur_tag_open']    = '<li class="page-item active"><span class="page-link">';
        $config['cur_tag_close']   = '</span></li>';
        $config['num_tag_open']    = '<li class="page-item">';
        $config['num_tag_close']   = '</li>';
        $config['attributes']      = ['class' => 'page-link'];

        $this->pagination->initialize($config);

        $offset = (int) $this->uri->segment($segment, 0);
        $list = $this->Khachle_model->get_page($perPage, $offset, $keyword);

        $user_id = $this->session->userdata('user_id');
        $user_role = null;
        if ($user_id) {
            $user = $this->db->get_where('users', ['id' => $user_id])->row();
            $user_role = $user ? $user->role : null;
        }
        $data = [
            'list' => $list,
            'keyword' => $keyword,
            'user_role' => $user_role,
            'pagination' => $this->pagination->create_links(),
            'offset' => $offset
        ];
        $this->render('khachle/index', $data);
    }

    // Thêm đơn khách lẻ
    public function add() {
        if ($this->input->method() === 'post') {
            // Tạo mã đơn khách lẻ tự động nếu chưa có
            $madon_id = $this->input->post('madon_id', true);
            if (!$madon_id) {
                $madon_id = 'KL' . date('YmdHis');
            }
            $data = [
                'madon_id' => $madon_id,
                'ten' => $this->input->post('ten', true),
                'dienthoai' => $this->input->post('dienthoai', true),
                'diachi' => $this->input->post('diachi', true),
                'ngaylap' => $this->input->post('ngaylap', true) ?: date('Y-m-d H:i:s'),
                'tongtien' => $this->input->post('tongtien', true),
                'giamgiatt_loai' => $this->input->post('giamgiatt_loai', true) ?: 'none',
                'giamgiatt_giatri' => $this->input->post('giamgiatt_giatri', true) ?: 0,
                'giamgiatt_thanhtien' => $this->input->post('giamgiatt_thanhtien', true) ?: 0,
                'giao_hang' => $this->input->post('giao_hang', true),
                'nguoi_nhan' => $this->input->post('nguoi_nhan', true),
                'ship' => $this->input->post('ship', true),
                'tongcong_tien' => $this->input->post('tongcong_tien', true),
                'ghi_chu' => $this->input->post('ghi_chu', true),
            ];

            $ship = $this->input->post('ship', true);
            $tongcong_tien = $this->input->post('tongcong_tien', true);
            $tongtien = $this->input->post('tongtien', true);
            $tongtien = $tongtien ? preg_replace('/[^0-9]/', '', $tongtien) : '0';

            // Chuyển về số nguyên
            $ship = $ship ? preg_replace('/[^0-9]/', '', $ship) : '0';
            $tongcong_tien = $tongcong_tien ? preg_replace('/[^0-9]/', '', $tongcong_tien) : '0';

            $data = [
                'madon_id' => $madon_id,
                'ten' => $this->input->post('ten', true),
                'dienthoai' => $this->input->post('dienthoai', true),
                'diachi' => $this->input->post('diachi', true),
                'ngaylap' => $this->input->post('ngaylap', true) ?: date('Y-m-d H:i:s'),
                'tongtien' => $tongtien,
                'giamgiatt_loai' => $this->input->post('giamgiatt_loai', true) ?: 'none',
                'giamgiatt_giatri' => $this->input->post('giamgiatt_giatri', true) ?: 0,
                'giamgiatt_thanhtien' => $this->input->post('giamgiatt_thanhtien', true) ?: 0,
                'giao_hang' => $this->input->post('giao_hang', true),
                'nguoi_nhan' => $this->input->post('nguoi_nhan', true),
                'ship' => $ship,
                'tongcong_tien' => $tongcong_tien,
                'ghi_chu' => $this->input->post('ghi_chu', true),
            ];
            $khachle_id = $this->Khachle_model->insert($data);
            // Ghi log thêm đơn khách lẻ
            $this->load->model('Actionlog_model');
            $user_id = $this->session->userdata('user_id');
            $this->Actionlog_model->log($user_id, 'add', 'khachle', $khachle_id, null, json_encode($data, JSON_UNESCAPED_UNICODE));

            // Thêm chi tiết đơn hàng, lấy cả giảm giá từng dòng
            $ma_sp = $this->input->post('ma_sp');
            $so_luong = $this->input->post('so_luong');
            $don_gia = $this->input->post('don_gia');
            $thanh_tien = $this->input->post('thanh_tien');
            $giamgiadg_loai = $this->input->post('giamgiadg_loai');
            $giamgiadg_giatri = $this->input->post('giamgiadg_giatri');
            $giamgiadg_thanhtien = $this->input->post('giamgiadg_thanhtien');
            if (is_array($ma_sp)) {
                for($i=0;$i<count($ma_sp);$i++) {
                    if(!empty($ma_sp[$i]) && $so_luong[$i] > 0) {
                        $this->Khachle_model->insert_chitiet([
                            'khachle_id' => $khachle_id,
                            'ma_sp' => $ma_sp[$i],
                            'so_luong' => $so_luong[$i],
                            'don_gia' => $don_gia[$i],
                            'thanh_tien' => $thanh_tien[$i],
                            'giamgiadg_loai' => isset($giamgiadg_loai[$i]) ? $giamgiadg_loai[$i] : null,
                            'giamgiadg_giatri' => isset($giamgiadg_giatri[$i]) ? $giamgiadg_giatri[$i] : null,
                            'giamgiadg_thanhtien' => isset($giamgiadg_thanhtien[$i]) ? $giamgiadg_thanhtien[$i] : null,
                        ]);
                    }
                }
            }
            // Nếu là AJAX thì trả về JSON, nếu không thì redirect như cũ
            if ($this->input->is_ajax_request()) {
                echo json_encode(['id' => $khachle_id]);
                exit;
            } else {
                redirect('khachle/pos/' . $khachle_id);
            }
        }
        // Lấy danh sách sản phẩm để chọn
        $data = [
            'sanpham' => $this->db->get('sanpham')->result(),
            'active' => 'khachle/add',
        ];
        $this->render('khachle/add', $data);
        
    }

    // Sửa đơn khách lẻ
    public function edit($id) {
        $row = $this->Khachle_model->get_by_id($id);
        $chitiet = $this->Khachle_model->get_chitiet($id);
        if (!$row) show_404();

        if ($this->input->method() === 'post') {
            
            $ship = $this->input->post('ship', true);
            $tongcong_tien = $this->input->post('tongcong_tien', true);
            $tongtien = $this->input->post('tongtien', true);
            $tongtien = $tongtien ? preg_replace('/[^0-9]/', '', $tongtien) : '0';

            // Chuyển về số nguyên
            $ship = $ship ? preg_replace('/[^0-9]/', '', $ship) : '0';
            $tongcong_tien = $tongcong_tien ? preg_replace('/[^0-9]/', '', $tongcong_tien) : '0';

            $data = [
                'madon_id' => $this->input->post('madon_id', true),
                'ten' => $this->input->post('ten', true),
                'dienthoai' => $this->input->post('dienthoai', true),
                'diachi' => $this->input->post('diachi', true),
                'ngaylap' => $this->input->post('ngaylap', true) ?: date('Y-m-d H:i:s'),
                'tongtien' => $tongtien,
                'giamgiatt_loai' => $this->input->post('giamgiatt_loai', true) ?: 'none',
                'giamgiatt_giatri' => $this->input->post('giamgiatt_giatri', true) ?: 0,
                'giamgiatt_thanhtien' => $this->input->post('giamgiatt_thanhtien', true) ?: 0,
                'giao_hang' => $this->input->post('giao_hang', true),
                'nguoi_nhan' => $this->input->post('nguoi_nhan', true),
                'ship' => $ship,
                'tongcong_tien' => $tongcong_tien,
                'ghi_chu' => $this->input->post('ghi_chu', true),
            ];
            $this->Khachle_model->update($id, $data);

            // Xoá chi tiết cũ và thêm lại
            $this->db->where('khachle_id', $id)->delete('khachle_donhang');
            $ma_sp = $this->input->post('ma_sp');
            $so_luong = $this->input->post('so_luong');
            $don_gia = $this->input->post('don_gia');
            $thanh_tien = $this->input->post('thanh_tien');
            $giamgiadg_loai = $this->input->post('giamgiadg_loai');
            $giamgiadg_giatri = $this->input->post('giamgiadg_giatri');
            $giamgiadg_thanhtien = $this->input->post('giamgiadg_thanhtien');
            if (is_array($ma_sp)) {
                for($i=0;$i<count($ma_sp);$i++) {
                    if(!empty($ma_sp[$i]) && $so_luong[$i] > 0) {
                        $this->Khachle_model->insert_chitiet([
                            'khachle_id' => $id,
                            'ma_sp' => $ma_sp[$i],
                            'so_luong' => $so_luong[$i],
                            'don_gia' => $don_gia[$i],
                            'thanh_tien' => $thanh_tien[$i],
                            'giamgiadg_loai' => isset($giamgiadg_loai[$i]) ? $giamgiadg_loai[$i] : null,
                            'giamgiadg_giatri' => isset($giamgiadg_giatri[$i]) ? $giamgiadg_giatri[$i] : null,
                            'giamgiadg_thanhtien' => isset($giamgiadg_thanhtien[$i]) ? $giamgiadg_thanhtien[$i] : null,
                        ]);
                    }
                }
            }
            // Ghi log sửa đơn khách lẻ
            $this->load->model('Actionlog_model');
            $user_id = $this->session->userdata('user_id');
            $row_before = $this->Khachle_model->get_by_id($id); // hoặc lấy trước khi update
            $this->Actionlog_model->log($user_id, 'edit', 'khachle', $id, json_encode($row_before, JSON_UNESCAPED_UNICODE), json_encode($data, JSON_UNESCAPED_UNICODE));

            if ($this->input->is_ajax_request()) {
                echo json_encode(['id' => $id]);
                exit;
            } else {
                redirect('khachle');
            }
        }
        $data = [
            'row' => $row,
            'chitiet' => $chitiet,
            'sanpham' => $this->db->get('sanpham')->result(),
        ];
        $this->render('khachle/edit', $data);
    }

    // Xoá đơn khách lẻ
    public function delete($id) {
        $row_before = $this->Khachle_model->get_by_id($id);
        $this->db->where('id', $id)->delete('khachle');
        $this->db->where('khachle_id', $id)->delete('khachle_donhang');
        // Ghi log xóa đơn khách lẻ
        $this->load->model('Actionlog_model');
        $user_id = $this->session->userdata('user_id');
        $this->Actionlog_model->log($user_id, 'delete', 'khachle', $id, json_encode($row_before, JSON_UNESCAPED_UNICODE), null);

        redirect('khachle');
    }

    // In đơn khách lẻ (POS)
    public function pos($id) {
        // Hiển thị biên nhận POS cho đơn khách lẻ
        $row = $this->Khachle_model->get_by_id($id);
        $chitiet = $this->Khachle_model->get_chitiet($id);
        $sanpham = $this->db->get('sanpham')->result();
        $this->load->view('khachle/pos', [
            'row' => $row,
            'chitiet' => $chitiet,
            'sanpham' => $sanpham
        ]);
    }

    // Chi tiết đơn khách lẻ
    public function detail($id) {
        $this->load->model('Khachle_model');

        // Lấy đơn khách lẻ
        $khachle = $this->Khachle_model->get_by_id($id);
        if (!$khachle) show_404();

        // Lấy chi tiết sản phẩm của đơn khách lẻ
        $chitiet = $this->Khachle_model->get_chitiet($id);

        // Lấy danh sách sản phẩm để đối chiếu tên bánh
        $sanpham = $this->db->get('sanpham')->result();

        $data = [
            'khachle' => $khachle,
            'chitiet' => $chitiet,
            'sanpham' => $sanpham,
        ];
        $this->render('khachle/detail', $data);
    }
}
