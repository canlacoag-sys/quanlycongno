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
        return $this->db->get()->result();
    }

    // Thêm đơn hàng, trả về insert_id
    public function insert($data) {
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
}