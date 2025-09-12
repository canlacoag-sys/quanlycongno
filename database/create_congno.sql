CREATE TABLE `congno` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `khachhang_id` INT NOT NULL,
  `ngaylap` DATETIME NOT NULL,
  `tong_tien` BIGINT NOT NULL,
  `ghichu` VARCHAR(255),
  `data_json` TEXT,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- If you need to store detailed breakdowns (chiết khấu / không chiết khấu),
-- add these columns to the existing table. Run the following ALTER statements
-- against your database (only once):

ALTER TABLE `congno`
  ADD COLUMN `tong_chietkhau_truoc` BIGINT DEFAULT 0,
  ADD COLUMN `chietkhau_percent` DECIMAL(8,3) DEFAULT 0,
  ADD COLUMN `chietkhau_amount` BIGINT DEFAULT 0,
  ADD COLUMN `tong_chietkhau_sau` BIGINT DEFAULT 0,
  ADD COLUMN `tong_khong_chiet` BIGINT DEFAULT 0,
  ADD COLUMN `tong_cong` BIGINT DEFAULT 0;

-- Note: if any of these columns already exist, remove or adjust the ALTER
-- commands before running. Controller `congno/ketso` must be updated to
-- populate these fields when creating/updating a `congno` row.
