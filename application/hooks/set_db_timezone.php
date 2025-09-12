<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Hook to set DB session timezone to +07:00 after controller is constructed
function set_db_timezone()
{
    $CI =& get_instance();
    // Make sure database is loaded
    if (!isset($CI->db)) {
        $CI->load->database();
    }

    try {
        $CI->db->query("SET time_zone = '+07:00'");
    } catch (Exception $e) {
        // Silent fail: do not break request if timezone cannot be set
        log_message('error', 'Failed to set DB time_zone: ' . $e->getMessage());
    }
}
