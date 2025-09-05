<?php
class Users_model extends CI_Model
{
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function get_all() {
        return $this->db->order_by('id', 'DESC')->get('users')->result();
    }

    public function get_by_id($id) {
        return $this->db->get_where('users', ['id' => $id])->row();
    }

    public function insert($data) {
        $this->db->insert('users', $data);
        return $this->db->insert_id();
    }

    public function update($id, $data) {
        $this->db->where('id', $id)->update('users', $data);
    }

    public function delete($id) {
        $this->db->where('id', $id)->delete('users');
    }

    public function username_exists($username) {
        return $this->db->where('username', $username)->count_all_results('users') > 0;
    }
}
