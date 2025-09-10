<?php
// filepath: application/models/Donhang_model.php
class Donhang_model extends CI_Model
{
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    // Lấy danh sách đơn hàng (join khách hàng)
    public function get_all() {
        $this->db->select('donhang.*, khachhang.ten as ten_khachhang');
        $this->db->from('donhang');
        $this->db->join('khachhang', 'donhang.khachhang_id = khachhang.id', 'left');
        $this->db->order_by('donhang.id', 'DESC');
        // Nếu bảng donhang đã có các trường giao_hang, nguoi_nhan thì không cần sửa gì thêm
        // Nếu chưa có, cần thêm các trường này vào bảng donhang trong database
        return $this->db->get()->result();
    }

    // Thêm đơn hàng, trả về insert_id
    public function insert($data) {
        // Đảm bảo có trường co_chiet_khau trong $data nếu dùng
        $this->db->insert('donhang', $data);
        return $this->db->insert_id();
    }

    // Thêm chi tiết đơn hàng
    public function insert_chitiet($data) {
        $this->db->insert('chitiet_donhang', $data);
    }

    // Lấy đơn hàng theo id
    public function get_by_id($id) {
        return $this->db->get_where('donhang', ['id' => $id])->row();
    }

    // Lấy chi tiết đơn hàng
    public function get_chitiet($donhang_id) {
        return $this->db->get_where('chitiet_donhang', ['donhang_id' => $donhang_id])->result();
    }

    // Sửa đơn hàng
    public function update($id, $data) {
        $this->db->where('id', $id)->update('donhang', $data);
    }

    public function get_by_khachhang($khachhang_id)
{
    $this->db->where('khachhang_id', $khachhang_id);
    return $this->db->get('donhang')->result();
}
}