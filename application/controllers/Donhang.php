<?php
// filepath: application/controllers/Donhang.php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @property Khachhang_model $Khachhang_model
 * @property Donhang_model $Donhang_model
 * @property Sanpham_model $Sanpham_model
 * @property CI_Session $session
 * @property CI_Input $input
 * @property CI_Pagination $pagination
 * @property CI_Output $output
 * @property CI_DB_query_builder $db
 * @property Actionlog_model $Actionlog_model
 * 
 */
class Donhang extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Donhang_model');
        $this->load->model('Actionlog_model');
        $this->load->helper(['url', 'form']);
        $this->load->library('session');
        $this->load->database();
        if(!$this->session->userdata('user_id')) redirect('auth/login');
    }

    // Hàm render dùng chung cho mọi view
    private function render(string $view, array $data = []) {
        $data['title']  = $data['title']  ?? 'CHI TIẾT ĐƠN HÀNG';
        $data['active'] = $data['active'] ?? 'donhang';
        $this->load->view('templates/header',  $data);
        $this->load->view('templates/navbar',  $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view($view,               $data);
        $this->load->view('templates/footer');
    }


    
    public function index() {
        $this->load->model('Khachhang_model');
        $this->load->library('pagination');
        $perPage = 20;
        $segment = 3;
        $keyword = $this->input->get('keyword', true);

        // Đếm tổng số đơn hàng (có thể lọc theo từ khóa)
        $this->db->from('donhang');
        $this->db->join('khachhang', 'donhang.khachhang_id = khachhang.id', 'left');
        if (!empty($keyword)) {
            $this->db->group_start();
            $this->db->like('donhang.madon_id', $keyword); // Đúng tên trường
            $this->db->or_like('khachhang.ten', $keyword);
            $this->db->or_like('donhang.ngaylap', $keyword);
            $this->db->group_end();
        }
        $total = $this->db->count_all_results();

        // Cấu hình phân trang
        $config['base_url'] = site_url('donhang/index');
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

        // Truy vấn dữ liệu từng trang
        $this->db->select('donhang.*, khachhang.ten as ten_khachhang');
        $this->db->from('donhang');
        $this->db->join('khachhang', 'donhang.khachhang_id = khachhang.id', 'left');
        if (!empty($keyword)) {
            $this->db->group_start();
            $this->db->like('donhang.madon_id', $keyword);
            $this->db->or_like('khachhang.ten', $keyword);
            $this->db->or_like('donhang.ngaylap', $keyword);
            $this->db->group_end();
        }
        $this->db->order_by('donhang.id', 'DESC');
        $this->db->limit($perPage, $offset);
        $list = $this->db->get()->result();

        // Lấy user role từ session/database
        $user_id = $this->session->userdata('user_id');
        $user_role = null;
        if ($user_id) {
            $user = $this->db->get_where('users', ['id' => $user_id])->row();
            $user_role = $user ? $user->role : null;
        }

        // Tổng hợp mã sản phẩm cho từng đơn hàng
        $donhang_sanpham = [];
        foreach ($list as $dh) {
            $chitiet = $this->Donhang_model->get_chitiet($dh->id);
            $ma_sp_arr = [];
            foreach ($chitiet as $ct) {
                $ma_sps = array_map('trim', explode(',', $ct->ma_sp));
                foreach ($ma_sps as $ma_sp) {
                    if ($ma_sp && !in_array($ma_sp, $ma_sp_arr)) {
                        $ma_sp_arr[] = $ma_sp;
                    }
                }
            }
            $donhang_sanpham[$dh->id] = $ma_sp_arr;
        }

        $data = [
            'list' => $list,
            'donhang_sanpham' => $donhang_sanpham,
            'sanpham' => $this->db->get('sanpham')->result(),
            'khachhang' => $this->db->get('khachhang')->result(),
            'user_role' => $user_role,
            'keyword' => $keyword,
            'pagination' => $this->pagination->create_links(),
            'offset' => $offset
        ];
        $this->render('donhang/index', $data);
    }

    public function autocomplete_khachhang() {
        $term = $this->input->get('term');
        $this->load->model('Khachhang_model');
        $result = $this->Khachhang_model->autocomplete($term);
        $data = [];
        foreach ($result as $kh) {
            $data[] = [
                'id' => $kh->id,
                'label' => $kh->ten . ' (' . $kh->dienthoai . ')',
                'value' => $kh->ten,
                'dienthoai' => $kh->dienthoai,
                'diachi' => $kh->diachi
            ];
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function autocomplete_sanpham() {
        $term = $this->input->get('term');
        $chietkhau = $this->input->get('chietkhau');
        $this->load->model('Sanpham_model');
        $result = $this->Sanpham_model->autocomplete($term, $chietkhau);
        $data = [];
        foreach ($result as $sp) {
            $data[] = [
                'id' => $sp->id,
                'label' => $sp->ma_sp . ' - ' . $sp->ten_sp,
                'value' => $sp->ma_sp,
                'ten_sp' => $sp->ten_sp,
                'gia' => $sp->gia
            ];
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function add_khachhang_ajax() {
        $ten = $this->input->post('ten');
        $dienthoai = $this->input->post('dienthoai');
        $diachi = $this->input->post('diachi');
        $this->load->model('Khachhang_model');
        $id = $this->Khachhang_model->insert([
            'ten' => $ten,
            'dienthoai' => $dienthoai,
            'diachi' => $diachi
        ]);
        $kh = $this->Khachhang_model->get_by_id($id);
        $this->output->set_content_type('application/json')->set_output(json_encode($kh));
    }

    public function pos($id) {
        $this->load->model('Khachhang_model');
        $this->load->model('Sanpham_model');
        $dh = $this->Donhang_model->get_by_id($id);
        $ct = $this->Donhang_model->get_chitiet($id);
        $kh = $this->Khachhang_model->get_by_id($dh->khachhang_id);

        // Lấy danh sách sản phẩm dạng mảng ma_sp => object (để tra cứu nhanh trong pos.php)
        $sanpham_arr = [];
        $sanpham_db = $this->Sanpham_model->get_all();
        foreach ($sanpham_db as $sp) {
            $sanpham_arr[$sp->ma_sp] = $sp;
        }

        $this->load->view('donhang/pos', [
            'donhang' => $dh,
            'chitiet' => $ct,
            'khachhang' => $kh,
            'sanpham' => $sanpham_arr // truyền vào dạng mảng ma_sp => object
        ]);
    }

    public function add() {
        if($this->input->method() === 'post') {
            $khachhang_id = $this->input->post('khachhang_id');
            $tongtien = $this->input->post('tongtien');
            $ngaylap = $this->input->post('ngaylap');
            $giao_hang = $this->input->post('giao_hang');
            $nguoi_nhan = $this->input->post('nguoi_nhan');
            $ghi_chu = $this->input->post('ghi_chu');
            $co_chiet_khau = $this->input->post('co_chiet_khau') ?? null;
            if(!$ngaylap) $ngaylap = date('Y-m-d H:i:s');

            // Tạo mã đơn hàng: DH + chuỗi số từ ngày lập (YYYYMMDDHHIISS)
            $madon_id = 'DH' . date('YmdHis', strtotime($ngaylap));

                $data_new = [
                    'madon_id' => $madon_id,
                    'khachhang_id' => $khachhang_id,
                    'tongtien' => $tongtien,
                    'ngaylap' => $ngaylap,
                    'giao_hang' => $giao_hang,
                    'nguoi_nhan' => $nguoi_nhan,
                    'ghi_chu' => $ghi_chu,
                    'co_chiet_khau' => $co_chiet_khau
                ];
                $donhang_id = $this->Donhang_model->insert($data_new);
                // Actionlog_model đã được load trong __construct
                $user_id = $this->session->userdata('user_id');
                $this->Actionlog_model->log($user_id, 'add', 'donhang', $donhang_id, null, json_encode($data_new, JSON_UNESCAPED_UNICODE));

            $ma_sp = $this->input->post('ma_sp');
            $so_luong = $this->input->post('so_luong');
            $don_gia = $this->input->post('don_gia');
            $thanh_tien = $this->input->post('thanh_tien');

            $chitiet_after = [];
            for($i=0;$i<count($ma_sp);$i++) {
                if(!empty($ma_sp[$i]) && $so_luong[$i] > 0) {
                    $row = [
                        'donhang_id' => $donhang_id,
                        'ma_sp' => $ma_sp[$i],
                        'so_luong' => $so_luong[$i],
                        'don_gia' => $don_gia[$i],
                        'thanh_tien' => $thanh_tien[$i]
                    ];
                    $this->Donhang_model->insert_chitiet($row);
                    $chitiet_after[] = $row;
                }
            }
            // Log chi tiết đơn hàng sau khi thêm
            $user_id = $this->session->userdata('user_id');
            $this->Actionlog_model->log($user_id, 'add', 'chitiet_donhang', $donhang_id, null, json_encode($chitiet_after, JSON_UNESCAPED_UNICODE));
            redirect('donhang/pos/'.$donhang_id);
        }
        $data = [
            'sanpham' => $this->db->get('sanpham')->result(),
        ];
        $this->render('donhang/add', $data);
    }

    public function danhsachdonhang() {
        $this->load->model('Khachhang_model');
        $list = $this->Donhang_model->get_all();
        $data = [
            'list' => $list,
            'sanpham' => $this->db->get('sanpham')->result(),
            'khachhang' => $this->db->get('khachhang')->result(),
        ];
        $this->render('donhang/danhsachdonhang', $data);
    }

    public function themdonhang() {
        $data = [
            'sanpham' => $this->db->get('sanpham')->result(),
        ];
        $this->render('donhang/themdonhang', $data);
    }

    public function edit($id) {
        $this->load->model('Khachhang_model');
        $donhang = $this->Donhang_model->get_by_id($id);
        $chitiet = $this->Donhang_model->get_chitiet($id);

        // Lấy đúng thông tin khách hàng cho autocomplete khi sửa
        $khachhang = null;
        if ($donhang && $donhang->khachhang_id) {
            $khachhang = $this->Khachhang_model->get_by_id($donhang->khachhang_id);
        }

        if ($this->input->method() === 'post') {
                $row_before = $this->Donhang_model->get_by_id($id);
                $khachhang_id = $this->input->post('khachhang_id');
                $ngaylap = $this->input->post('ngaylap');
                $giao_hang = $this->input->post('giao_hang');
                $nguoi_nhan = $this->input->post('nguoi_nhan');
                $ghi_chu = $this->input->post('ghi_chu');
                if(!$ngaylap) $ngaylap = date('Y-m-d H:i:s');

                $ma_sp = $this->input->post('ma_sp');
                $so_luong = $this->input->post('so_luong');
                $don_gia = $this->input->post('don_gia');
                $thanh_tien = $this->input->post('thanh_tien');

                // Lấy chi tiết cũ để log
                $chitiet_before = $this->Donhang_model->get_chitiet($id);
                $this->db->where('donhang_id', $id)->delete('chitiet_donhang');

                // Thêm chi tiết mới và tính lại tổng tiền
                $tongtien = 0;
                $chitiet_after = [];
                for($i=0;$i<count($ma_sp);$i++) {
                    if(!empty($ma_sp[$i]) && $so_luong[$i] > 0) {
                        $row = [
                            'donhang_id' => $id,
                            'ma_sp' => $ma_sp[$i],
                            'so_luong' => $so_luong[$i],
                            'don_gia' => $don_gia[$i],
                            'thanh_tien' => $thanh_tien[$i]
                        ];
                        $this->Donhang_model->insert_chitiet($row);
                        $chitiet_after[] = $row;
                        $tongtien += floatval($thanh_tien[$i]);
                    }
                }
                // Log chi tiết đơn hàng trước và sau khi sửa
                $user_id = $this->session->userdata('user_id');
                $this->Actionlog_model->log($user_id, 'edit', 'chitiet_donhang', $id, json_encode($chitiet_before, JSON_UNESCAPED_UNICODE), json_encode($chitiet_after, JSON_UNESCAPED_UNICODE));

                // Cập nhật lại tổng tiền vào đơn hàng
                $data_new = [
                    'khachhang_id' => $khachhang_id,
                    'tongtien' => $tongtien,
                    'ngaylap' => $ngaylap,
                    'giao_hang' => $giao_hang,
                    'nguoi_nhan' => $nguoi_nhan,
                    'ghi_chu' => $ghi_chu
                ];
                $this->Donhang_model->update($id, $data_new);
                $row_after = $this->Donhang_model->get_by_id($id);
                // Actionlog_model đã được load trong __construct
                $user_id = $this->session->userdata('user_id');
                $this->Actionlog_model->log($user_id, 'edit', 'donhang', $id, json_encode($row_before, JSON_UNESCAPED_UNICODE), json_encode($row_after, JSON_UNESCAPED_UNICODE));

                    if ($this->input->is_ajax_request()) {
                        echo json_encode(['id' => $id]);
                        return;
                    } else {
                        redirect('donhang/pos/'.$id);
                        return;
                    }
        }

        $data = [
            'donhang' => $donhang,
            'chitiet' => $chitiet,
            'sanpham' => $this->db->get('sanpham')->result(),
            'khachhang' => $khachhang, // truyền đúng object khách hàng cho view
            'active' => 'donhang/edit',
        ];
        $this->render('donhang/edit', $data);
    }

    // Xoá đơn hàng (và chi tiết) an toàn
    public function delete($id) {
        // allow only numeric id
        $id = (int) $id;
        if (!$id) {
            show_404();
            return;
        }

        $this->load->model('Donhang_model');
            $row_before = $this->Donhang_model->get_by_id($id);
            $chitiet_before = $this->Donhang_model->get_chitiet($id);
            $ok = $this->Donhang_model->delete_with_chitiet($id);
            // Actionlog_model đã được load trong __construct
            $user_id = $this->session->userdata('user_id');
            $this->Actionlog_model->log($user_id, 'delete', 'donhang', $id, json_encode($row_before, JSON_UNESCAPED_UNICODE), null);
            $this->Actionlog_model->log($user_id, 'delete', 'chitiet_donhang', $id, json_encode($chitiet_before, JSON_UNESCAPED_UNICODE), null);

        if ($this->input->is_ajax_request()) {
            $this->output->set_content_type('application/json')->set_output(json_encode(['success' => (bool)$ok]));
            return;
        }

        // Redirect back to list with simple flash
        if ($ok) {
            $this->session->set_flashdata('message', 'Đã xóa đơn hàng');
        } else {
            $this->session->set_flashdata('error', 'Xóa không thành công');
        }
        redirect('donhang');
    }

    public function addcochietkhau() {
        $this->load->helper('money');
        if($this->input->method() === 'post') {
            $khachhang_id = $this->input->post('khachhang_id');
            $tongtien = $this->input->post('tongtien');
            $ngaylap = $this->input->post('ngaylap');
            $giao_hang = $this->input->post('giao_hang');
            $nguoi_nhan = $this->input->post('nguoi_nhan');
            $ghi_chu = $this->input->post('ghi_chu');
            $co_chiet_khau = 1;
            if(!$ngaylap) $ngaylap = date('Y-m-d H:i:s');

            if ($tongtien === null || $tongtien === '' || !is_numeric($tongtien)) {
                $tongtien = 0;
            }

            // Tạo mã đơn hàng: DH + chuỗi số từ ngày lập (YYYYMMDDHHIISS)
            $madon_id = 'DH' . date('YmdHis', strtotime($ngaylap));

            $donhang_id = $this->Donhang_model->insert([
                'madon_id' => $madon_id,
                'khachhang_id' => $khachhang_id,
                'tongtien' => $tongtien,
                'ngaylap' => $ngaylap,
                'giao_hang' => $giao_hang,
                'nguoi_nhan' => $nguoi_nhan,
                'ghi_chu' => $ghi_chu,
                'co_chiet_khau' => $co_chiet_khau
            ]);

            $ma_sp = $this->input->post('ma_sp');
            $so_luong = $this->input->post('so_luong');
            $don_gia = $this->input->post('don_gia');
            $thanh_tien = $this->input->post('thanh_tien');

            for($i=0;$i<count($ma_sp);$i++) {
                if(!empty($ma_sp[$i]) && $so_luong[$i] > 0) {
                    $this->Donhang_model->insert_chitiet([
                        'donhang_id' => $donhang_id,
                        'ma_sp' => $ma_sp[$i],
                        'so_luong' => $so_luong[$i],
                        'don_gia' => $don_gia[$i],
                        'thanh_tien' => $thanh_tien[$i]
                    ]);
                }
            }
            if ($this->input->is_ajax_request()) {
                echo json_encode(['id' => $donhang_id]);
                return;
            } else {
                redirect('donhang/pos/'.$donhang_id);
            }
        }
        $data = [
            'sanpham' => $this->db->get('sanpham')->result(),
            'active' => 'donhang/addcochietkhau',
        ];
        $this->render('donhang/addcochietkhau', $data);
    }

    public function addkochietkhau() {
        $this->load->helper('money');
        if($this->input->method() === 'post') {
            $khachhang_id = $this->input->post('khachhang_id');
            $tongtien = $this->input->post('tongtien');
            $ngaylap = $this->input->post('ngaylap');
            $giao_hang = $this->input->post('giao_hang');
            $nguoi_nhan = $this->input->post('nguoi_nhan');
            $ghi_chu = $this->input->post('ghi_chu');
            $co_chiet_khau = 0;
            if(!$ngaylap) $ngaylap = date('Y-m-d H:i:s');

            // Tạo mã đơn hàng: DH + chuỗi số từ ngày lập (YYYYMMDDHHIISS)
            $madon_id = 'DH' . date('YmdHis', strtotime($ngaylap));

            $donhang_id = $this->Donhang_model->insert([
                'madon_id' => $madon_id,
                'khachhang_id' => $khachhang_id,
                'tongtien' => $tongtien,
                'ngaylap' => $ngaylap,
                'giao_hang' => $giao_hang,
                'nguoi_nhan' => $nguoi_nhan,
                'ghi_chu' => $ghi_chu,
                'co_chiet_khau' => $co_chiet_khau
            ]);

            $ma_sp = $this->input->post('ma_sp');
            $so_luong = $this->input->post('so_luong');
            $don_gia = $this->input->post('don_gia');
            $thanh_tien = $this->input->post('thanh_tien');

            for($i=0;$i<count($ma_sp);$i++) {
                if(!empty($ma_sp[$i]) && $so_luong[$i] > 0) {
                    $this->Donhang_model->insert_chitiet([
                        'donhang_id' => $donhang_id,
                        'ma_sp' => $ma_sp[$i],
                        'so_luong' => $so_luong[$i],
                        'don_gia' => $don_gia[$i],
                        'thanh_tien' => $thanh_tien[$i]
                    ]);
                }
            }
            if ($this->input->is_ajax_request()) {
                echo json_encode(['id' => $donhang_id]);
                return;
            } else {
                redirect('donhang/pos/'.$donhang_id);
            }
        }
        $data = [
            'sanpham' => $this->db->get('sanpham')->result(),
            'active' => 'donhang/addkochietkhau', // Thêm dòng này
        ];
        $this->render('donhang/addkochietkhau', $data);
    }

    public function detail($id) {
        $this->load->model('Donhang_model');
        $this->load->model('Khachhang_model');

        // Lấy đơn hàng
        $donhang = $this->Donhang_model->get_by_id($id);
        if (!$donhang) show_404();

        // Lấy khách hàng
        $khachhang = $this->Khachhang_model->get_by_id($donhang->khachhang_id);

        // Lấy chi tiết sản phẩm của đơn hàng
        $chitiet = $this->Donhang_model->get_chitiet($id);

        // Lấy danh sách sản phẩm để đối chiếu tên
        $sanpham = $this->db->get('sanpham')->result();

        $data = [
            'donhang'   => $donhang,
            'khachhang' => $khachhang,
            'chitiet'   => $chitiet,
            'sanpham'   => $sanpham,
        ];
        $this->render('donhang/detail', $data);
    }

}