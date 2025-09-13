-- Bảng lưu log thao tác
CREATE TABLE IF NOT EXISTS action_log (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    action VARCHAR(20), -- edit, delete, add
    object_type VARCHAR(50), -- sanpham, khachhang, congno, donhang, users, khachle
    object_id INT,
    data_before TEXT,
    data_after TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);
