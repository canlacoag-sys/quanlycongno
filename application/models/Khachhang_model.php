<?php
// filepath: application/models/Khachhang_model.php
class Khachhang_model extends CI_Model
{
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    // Lấy danh sách khách hàng (có filter, phân trang)
    public function get_all($keyword = '', $limit = 50, $offset = 0) {
        if ($keyword !== '') {
            $this->db->group_start()
                ->like('ten', $keyword)
                ->or_like('dienthoai', $keyword)
                ->or_like('diachi', $keyword)
                ->group_end();
        }
        $this->db->order_by('id', 'DESC');
        if ($limit) $this->db->limit($limit, $offset);
        return $this->db->get('khachhang')->result();
    }

    // Đếm tổng số khách hàng (có filter)
    public function count_all($keyword = '') {
        if ($keyword !== '') {
            $this->db->group_start()
                ->like('ten', $keyword)
                ->or_like('dienthoai', $keyword)
                ->or_like('diachi', $keyword)
                ->group_end();
        }
        return $this->db->count_all_results('khachhang');
    }

    // Lấy 1 khách hàng theo id
    public function get_by_id($id) {
        return $this->db->get_where('khachhang', ['id' => $id])->row();
    }

    // Thêm khách hàng
    public function insert($data) {
        $this->db->insert('khachhang', $data);
        return $this->db->insert_id();
    }

    // Sửa khách hàng
    public function update($id, $data) {
        $this->db->where('id', $id)->update('khachhang', $data);
    }

    // Xóa khách hàng
    public function delete($id) {
        $this->db->where('id', $id)->delete('khachhang');
    }

    // Kiểm tra trùng số điện thoại (dạng CSV)
    public function phone_exists($phone, $excludeId = 0) {
        $this->db->from('khachhang');
        if ($excludeId > 0) $this->db->where('id !=', $excludeId);
        $this->db->where("FIND_IN_SET(".$this->db->escape($phone).", REPLACE(dienthoai,' ','')) >", 0, false);
        return $this->db->count_all_results() > 0;
    }

    // Autocomplete
    public function autocomplete($term) {
        if ($term !== '') {
            $this->db->group_start()
                ->like('ten', $term)
                ->or_like('dienthoai', $term)
                ->group_end();
        }
        return $this->db->limit(20)->get('khachhang')->result();
    }
}