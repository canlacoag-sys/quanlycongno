<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Log extends CI_Controller {
    public function index() {
        $this->load->model('Actionlog_model');
        $logs = $this->Actionlog_model->get_all_logs();
        $this->load->view('log/index', ['logs' => $logs]);
    }
}
