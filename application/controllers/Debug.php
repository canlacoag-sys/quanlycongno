<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Debug extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    // GET /index.php/debug/timezone
    public function timezone()
    {
        $row = $this->db->query("SELECT @@session.time_zone AS session_tz, @@global.time_zone AS global_tz, NOW() AS now_time")->row();
        header('Content-Type: application/json');
        echo json_encode($row);
    }

    // GET /index.php/debug/insert_test
    public function insert_test()
    {
        // create a small test table if not exists
        $this->db->query("CREATE TABLE IF NOT EXISTS debug_time_test (id INT AUTO_INCREMENT PRIMARY KEY, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP)");
        $this->db->insert('debug_time_test', []);
        $id = $this->db->insert_id();
        $row = $this->db->query("SELECT id, created_at FROM debug_time_test WHERE id = ?", [$id])->row();
        header('Content-Type: application/json');
        echo json_encode($row);
    }

}
