-- Sửa lỗi cú pháp khi thêm cột vào bảng donhang

ALTER TABLE donhang
  ADD COLUMN giao_hang VARCHAR(255) DEFAULT NULL,
  ADD COLUMN nguoi_nhan VARCHAR(255) DEFAULT NULL;
