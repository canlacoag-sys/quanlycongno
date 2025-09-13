<?php
class Actionlog_model extends CI_Model {
    public function log($user_id, $action, $object_type, $object_id, $data_before = null, $data_after = null) {
        $this->db->insert('action_log', [
            'user_id' => $user_id,
            'action' => $action,
            'object_type' => $object_type,
            'object_id' => $object_id,
            'data_before' => $data_before,
            'data_after' => $data_after,
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }

    public function get_all_logs() {
        return $this->db->order_by('id', 'DESC')->get('action_log')->result_array();
    }
}
