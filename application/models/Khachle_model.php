<?php
class Khachle_model extends CI_Model
{
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    // Lấy danh sách đơn khách lẻ, có tìm kiếm theo keyword
    public function get_all($keyword = '') {
        if ($keyword !== '') {
            $this->db->group_start()
                ->like('madon_id', $keyword)
                ->or_like('ten', $keyword)
                ->or_like('dienthoai', $keyword)
                ->or_like('diachi', $keyword)
                ->group_end();
        }
        $this->db->order_by('id', 'DESC');
        return $this->db->get('khachle')->result();
    }

    // Thêm đơn khách lẻ, trả về insert_id
    public function insert($data) {
        $this->db->insert('khachle', $data);
        return $this->db->insert_id();
    }

    // Thêm chi tiết đơn hàng vào bảng khachle_donhang
    public function insert_chitiet($data) {
        $this->db->insert('khachle_donhang', $data);
    }

    // Lấy 1 đơn khách lẻ theo id
    public function get_by_id($id) {
        return $this->db->get_where('khachle', ['id' => $id])->row();
    }

    // Lấy chi tiết đơn hàng từ bảng khachle_donhang
    public function get_chitiet($khachle_id) {
        return $this->db->get_where('khachle_donhang', ['khachle_id' => $khachle_id])->result();
    }

    // Sửa đơn khách lẻ (bảng khachle)
    public function update($id, $data) {
        $this->db->where('id', $id)->update('khachle', $data);
    }

    public function count_all($keyword = '')
    {
        if ($keyword) {
            $this->db->group_start()
                ->like('madon_id', $keyword)
                ->or_like('ten', $keyword)
                ->or_like('dienthoai', $keyword)
                ->group_end();
        }
        return $this->db->count_all_results('khachle');
    }

    public function get_page($limit, $offset, $keyword = '')
    {
        if ($keyword) {
            $this->db->group_start()
                ->like('madon_id', $keyword)
                ->or_like('ten', $keyword)
                ->or_like('dienthoai', $keyword)
                ->group_end();
        }
        $this->db->order_by('id', 'DESC');
        return $this->db->get('khachle', $limit, $offset)->result();
    }
}

// Không cần sửa gì ở đây nếu controller đã truyền đúng kiểu số
