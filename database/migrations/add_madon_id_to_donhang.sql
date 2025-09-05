ALTER TABLE `donhang`
ADD COLUMN `madon_id` VARCHAR(32) NOT NULL AFTER `id`;
-- Nếu muốn nằm trước khachhang_id:
ALTER TABLE `donhang`
MODIFY COLUMN `madon_id` VARCHAR(32) NOT NULL AFTER `id`;
-- ...existing columns...
-- id | madon_id | khachhang_id | ...
