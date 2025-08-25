<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('phones_compact')) {
	// Dùng khi lưu: bỏ khoảng trắng thừa
	function phones_compact($csv) {
		return preg_replace('/\s*,\s*/', ',', (string)$csv);
	}
}

if (!function_exists('phones_pretty')) {
	// Dùng khi hiển thị: thêm khoảng trắng sau dấu phẩy
	function phones_pretty($csv) {
		$csv = phones_compact($csv); // chuẩn hoá trước
		return str_replace(',', ', ', $csv);
	}
}
