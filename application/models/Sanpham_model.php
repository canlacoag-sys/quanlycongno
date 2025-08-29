<?php
// filepath: application/models/Sanpham_model.php
class Sanpham_model extends CI_Model
{
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    // Lấy danh sách sản phẩm (có filter, phân trang)
    public function get_all($keyword = '', $chietkhau = '', $limit = 50, $offset = 0) {
        if ($chietkhau !== '' && $chietkhau !== null) {
            $this->db->where('co_chiet_khau', (int)$chietkhau);
        }
        if ($keyword) {
            $this->db->group_start()
                ->like('ma_sp', $keyword)
                ->or_like('ten_sp', $keyword)
                ->group_end();
        }
        $this->db->order_by('id', 'DESC');
        if ($limit) $this->db->limit($limit, $offset);
        return $this->db->get('sanpham')->result();
    }

    // Đếm tổng số sản phẩm (có filter)
    public function count_all($keyword = '', $chietkhau = '') {
        if ($chietkhau !== '' && $chietkhau !== null) {
            $this->db->where('co_chiet_khau', (int)$chietkhau);
        }
        if ($keyword) {
            $this->db->group_start()
                ->like('ma_sp', $keyword)
                ->or_like('ten_sp', $keyword)
                ->group_end();
        }
        return $this->db->count_all_results('sanpham');
    }

    // Lấy 1 sản phẩm theo id
    public function get_by_id($id) {
        return $this->db->get_where('sanpham', ['id' => $id])->row();
    }

    // Thêm sản phẩm
    public function insert($data) {
        $this->db->insert('sanpham', $data);
        return $this->db->insert_id();
    }

    // Sửa sản phẩm
    public function update($id, $data) {
        $this->db->where('id', $id)->update('sanpham', $data);
    }

    // Xóa sản phẩm
    public function delete($id) {
        $this->db->where('id', $id)->delete('sanpham');
    }

    // Kiểm tra trùng mã sản phẩm
    public function ma_sp_exists($ma_sp, $ignore_id = 0) {
        $this->db->where('ma_sp', $ma_sp);
        if ($ignore_id > 0) $this->db->where('id !=', $ignore_id);
        return $this->db->count_all_results('sanpham') > 0;
    }
}