<?php
// filepath: application/models/Sanpham_model.php
class Sanpham_model extends CI_Model
{
    // Lấy 1 sản phẩm theo mã sản phẩm
    public function get_by_ma_sp($ma_sp) {
        return $this->db->get_where('sanpham', ['ma_sp' => $ma_sp])->row();
    }
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
        // Nếu muốn filter combo, thêm điều kiện tại đây (tuỳ nhu cầu)
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

    // Thêm sản phẩm (có trường combo)
    public function insert($data) {
        // $data phải có key 'combo' (0 hoặc 1)
        $this->db->insert('sanpham', $data);
        return $this->db->insert_id();
    }

    // Sửa sản phẩm (có trường combo)
    public function update($id, $data) {
        // $data phải có key 'combo' (0 hoặc 1) nếu muốn cập nhật
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

    // Autocomplete sản phẩm theo mã, tên, filter chiết khấu
    public function autocomplete($term, $chietkhau = '') {
        if ($chietkhau !== '' && $chietkhau !== null) {
            $this->db->where('co_chiet_khau', (int)$chietkhau);
        }
        if ($term) {
            $this->db->group_start()
                ->like('ma_sp', $term)
                ->or_like('ten_sp', $term)
                ->group_end();
        }
        return $this->db->limit(20)->get('sanpham')->result();
    }
}