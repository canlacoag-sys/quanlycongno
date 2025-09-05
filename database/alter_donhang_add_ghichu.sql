-- Thêm trường ghi chú vào bảng donhang

ALTER TABLE donhang
  ADD COLUMN ghi_chu TEXT DEFAULT NULL;
