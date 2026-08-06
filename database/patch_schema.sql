-- SQL Patch Script for manual MySQL execution
-- Sửa đổi/bổ sung cột CSDL trực tiếp không làm phình thư mục migrations

-- 1. Bổ sung cột slug_vn và slug_en cho bảng tp_brands (nếu chưa có)
ALTER TABLE `tp_brands` 
    ADD COLUMN `slug_vn` VARCHAR(255) NULL AFTER `name_en`,
    ADD COLUMN `slug_en` VARCHAR(255) NULL AFTER `slug_vn`,
    ADD INDEX `tp_brands_slug_vn_index` (`slug_vn`),
    ADD INDEX `tp_brands_slug_en_index` (`slug_en`);

-- 2. Bổ sung cột brand_id cho bảng tp_products (nếu chưa có)
ALTER TABLE `tp_products` 
    ADD COLUMN `brand_id` BIGINT UNSIGNED NULL AFTER `category_id`,
    ADD INDEX `tp_products_brand_id_index` (`brand_id`);
