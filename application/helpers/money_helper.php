<?php
if (!function_exists('money_vnd')) {
    /**
     * Định dạng số tiền kiểu Việt Nam, có dấu phẩy và thêm "đ"
     * @param int|float|string $amount
     * @return string
     */
    function money_vnd($amount) {
        return number_format((float)$amount, 0, ',', '.') . ' <span class="donvi">đ</span>';
    }
}
