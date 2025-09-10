CREATE TABLE `congno` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `khachhang_id` INT NOT NULL,
  `ngaylap` DATETIME NOT NULL,
  `tong_tien` BIGINT NOT NULL,
  `ghichu` VARCHAR(255),
  `data_json` TEXT,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
