<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Congno_model extends CI_Model {
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    // Thêm công nợ
    public function insert($data) {
        $this->db->insert('congno', $data);
        return $this->db->insert_id();
    }

    // Xóa công nợ
    public function delete($id) {
        $this->db->where('id', $id)->delete('congno');
    }

    // Có thể bổ sung các hàm khác nếu cần
}
