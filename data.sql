-- USE warehouse_management;
INSERT INTO locations (id, street_address, ward, district, city, latitude, longitude, created_at, updated_at) VALUES
(1, NULL, 'Long Hậu', 'Lai Vung', 'Đồng Tháp', 10.267038, 105.616847, '2024-11-09 05:27:58', '2024-11-09 05:27:58'),
(2, NULL, 'An Phú', 'Thủ Đức', 'Hồ Chí Minh', 10.802134, 106.742250, '2024-11-08 19:01:37', '2024-11-08 19:01:37'),
(3, NULL, 'Kim Liên', 'Đống Đa', 'Hà Nội', 21.006237, 105.835247, '2024-11-08 19:03:01', '2024-11-08 19:03:01'),
(4, NULL, 'An Phú', 'Ninh Kiều', 'Cần Thơ', 10.033080, 105.778805, '2024-11-08 19:04:11', '2024-11-08 19:04:11'),
(5, NULL, 'An Hải Đông', 'Sơn Trà', 'Đà Nẵng', 16.054301, 108.239322, '2024-11-08 19:05:47', '2024-11-08 19:05:47'),
(6, NULL, 'Thới Hòa', 'Ô Môn', 'Cần Thơ', 10.117914, 105.618333, '2024-11-08 19:06:45', '2024-11-08 19:06:45'),
(7, NULL, 'Cái Khế', 'Ninh Kiều', 'Cần Thơ', 10.046767, 105.784807, '2024-11-08 19:07:34', '2024-11-08 19:07:34'),
(8, NULL, 'Phong Mỹ', 'Cao Lãnh', 'Đồng Tháp', 10.532860, 105.580467, '2024-11-08 19:08:23', '2024-11-08 19:08:23'),
(9, NULL, 'Nguyễn Cư Trinh', '1', 'Hồ Chí Minh', 10.763075, 106.686301, '2024-11-08 19:09:01', '2024-11-08 19:09:01'),
(10, NULL, 'An Khánh', 'Ninh Kiều', 'Cần Thơ', 10.031548, 105.755405, '2024-11-08 19:09:36', '2024-11-08 19:09:36'),
(12, NULL, 'Hoà Hải', 'Ngũ Hành Sơn', 'Đà Nẵng', 15.996233, 108.264255, '2024-11-09 05:35:23', '2024-11-09 05:35:23'),
(13, NULL, 'Phước Thạnh', 'Châu Thành', 'Bến Tre', 10.278979, 106.391388, '2024-11-09 05:38:02', '2024-11-09 05:38:02'),
(14, NULL, 'Nhơn Phú', 'Mang Thít', 'Vĩnh Long', 10.208893, 106.087338, '2024-11-09 05:53:10', '2024-11-09 05:53:10'),
(15, NULL, 'Hiệp Tùng', 'Năm Căn', 'Cà Mau', 8.825876, 105.137454, '2024-11-09 05:54:32', '2024-11-09 05:54:32');

INSERT INTO providers (id, name, phone, email, status, location_id, created_at, updated_at) VALUES
(1, 'Nhà cung cấp Thời trang', '0874758463', 'thoitrang@gmail.com', 'active', 6, '2024-11-08 19:06:45', '2024-11-08 19:06:45'),
(2, 'Nhà cung cấp FPT', '0864859674', 'fpt@gmail.com', 'active', 7, '2024-11-08 19:07:34', '2024-11-08 19:07:34'),
(3, 'Nhà cung cấp Fahasa', '0746384659', 'fahasa@gmail.com', 'active', 8, '2024-11-08 19:08:23', '2024-11-08 19:08:23'),
(4, 'Nhà cung cấp Mỹ phẩm', '0846586464', 'mypham@gmail.com', 'active', 9, '2024-11-08 19:09:01', '2024-11-08 19:09:01'),
(5, 'Nhà cung cấp Bách hóa', '0764856596', 'bachhoa@gmail.com', 'active', 10, '2024-11-08 19:09:36', '2024-11-08 19:09:36');


INSERT INTO warehouses (id, name, capacity, size, isRefrigerated, location_id, created_at, updated_at) VALUES
(1, 'Nhà kho An Phú - Thủ Đức - Hồ Chí Minh', 5000, 500.00, 1, 2, '2024-11-08 19:01:37', '2024-11-08 19:01:37'),
(2, 'Nhà kho Kim Liên - Đống Đa - Hà Nội', 4500, 450.00, 1, 3, '2024-11-08 19:03:01', '2024-11-08 19:03:01'),
(3, 'Nhà kho An Phú - Ninh Kiều - Cần Thơ', 4000, 400.00, 1, 4, '2024-11-08 19:04:11', '2024-11-08 19:04:11'),
(4, 'Nhà kho An Hải Đông - Sơn Trà - Đà Nẵng', 450, 4500.00, 1, 5, '2024-11-08 19:05:47', '2024-11-08 19:05:47');

INSERT INTO users (id, name, gender, birth_date, phone, status, email, password, warehouse_id, location_id, type, created_at, updated_at) VALUES
(3, 'admin', 'male', '2000-11-14', '0875645575', 'active', 'admin@gmail.com', '$2y$12$h3HJ9dO0SVWtkcQbMdpJt.plChk.JdFuBNvA5Jdh1bEYNo7HMMHmm', NULL, 1, 'admin', '2024-11-09 05:27:59', '2024-11-09 05:27:59'),
(4, 'QL Kho HCM', 'male', '2001-11-14', '0999999976', 'active', 'managerHCM@gmail.com', '$2y$12$YsG4zCLcWxhQcMACm3XPAeX7nEACCyZs6y2DsDF5xU6Z/GwDrjhWu', 1, 12, 'manager', '2024-11-09 05:35:23', '2024-11-09 05:35:23'),
(5, 'QL Kho HN', 'female', '1996-12-12', '0888888867', 'active', 'managerHN@gmail.com', '$2y$12$AGiOgC0xjjXqhho2xqk7v.telccVi5HUcJsI0ndTQcWjlbTUGn3dW', 2, 13, 'manager', '2024-11-09 05:38:02', '2024-11-09 05:38:02'),
(6, 'QL Kho CT', 'male', '1999-08-20', '0875637465', 'active', 'managerCT@gmail.com', '$2y$12$RfrEOtPKygVcBwz7obBLau5JXCK5n7mQ09LwdjQo1aFas5slW1UG.', 3, 14, 'manager', '2024-11-09 05:53:11', '2024-11-09 05:53:11'),
(7, 'QL Kho DN', 'female', '1998-02-01', '0976374859', 'active', 'managerDN@gmail.com', '$2y$12$tjGYjBW/MhHZJKi1aHBqZeIOFMDMGkR6R7R6WL/9S4TFProUIF2ou', 4, 15, 'manager', '2024-11-09 05:54:32', '2024-11-09 05:54:32'),
(9, 'Khách hàng A', 'male', '1997-08-05', '0764537846', 'active', 'khachhangA@gmail.com', '$2y$12$kktqzoZT7mOS54btEwpWUO6Ttz9oyxSc8IJP/MNn0d9UDeifNQIf.', NULL, NULL, 'customer', '2024-11-09 05:57:47', '2024-11-09 05:57:47'),
(10, 'Khách hàng B', 'female', '1995-05-10', '0764537890', 'active', 'khachhangB@gmail.com', '', NULL, NULL, 'customer', '2024-11-09 06:00:00', '2024-11-09 06:00:00'),
(11, 'Khách hàng C', 'male', '1988-11-22', '0764537881', 'active', 'khachhangC@gmail.com', '', NULL, NULL, 'customer', '2024-11-09 06:02:00', '2024-11-09 06:02:00'),
(12, 'Khách hàng D', 'female', '1992-03-18', '0764537872', 'active', 'khachhangD@gmail.com', '', NULL, NULL, 'customer', '2024-11-09 06:04:00', '2024-11-09 06:04:00'),
(13, 'Khách hàng E', 'male', '1985-09-10', '0764537863', 'active', 'khachhangE@gmail.com', '', NULL, NULL, 'customer', '2024-11-09 06:06:00', '2024-11-09 06:06:00'),
(14, 'Khách hàng F', 'female', '1990-01-30', '0764537854', 'active', 'khachhangF@gmail.com', '', NULL, NULL, 'customer', '2024-11-09 06:08:00', '2024-11-09 06:08:00');
-- INSERT INTO permissions(id,code,name,group,created_at,updated_at) VALUES



INSERT INTO roles (id, code, name,created_at,updated_at) VALUES
(1, 'admin', 'Người quản lý tất cả kho', NOW(),NOW()),
(2,'manager', 'Nguời quản lý kho riêng',NOW(),NOW()),
(3,'customer', 'Khách hàng',NOW(),NOW());

-- INSERT INTO model_has_permissions (permission_id, model-type, model_id) VALUES

INSERT INTO model_has_roles (role_id, model_type, model_id) VALUES
(1, 'App\\Models\\User', 3),
(2, 'App\\Models\\User', 4),
(2, 'App\\Models\\User', 5),
(2, 'App\\Models\\User', 6),
(2, 'App\\Models\\User', 7),
(3, 'App\\Models\\User', 8),
(3, 'App\\Models\\User', 9);

-- INSERT INTO role_has_permissions (permission_id, role_id) VALUES

INSERT INTO units (id, name, created_at, updated_at) VALUES
    (1, 'Kilogram', NOW(), NOW()),
    (2, 'Gram', NOW(), NOW()),
    (3, 'Lít', NOW(), NOW()),
    (4, 'Hộp', NOW(), NOW()),
    (5, 'Thùng', NOW(), NOW()),
    (6, 'Gói', NOW(), NOW()),
    (7, 'Cái', NOW(), NOW()),
    (8, 'Chai', NOW(), NOW()),
    (9, 'Lon', NOW(), NOW()),
    (10, 'Bao', NOW(), NOW());

INSERT INTO categories (id, name, created_at, updated_at) VALUES 
    (1, 'Thời trang', NOW(), NOW()),
    (2, 'Điện tử và Công nghệ', NOW(), NOW()),
    (3, 'Sức khỏe và Làm đẹp', NOW(), NOW()),
    (4, 'Thực phâm và Đồ uống', NOW(), NOW());



INSERT INTO products (code, name, category_id, description, unit_id, selling_price, status, refrigerated, minimum_stock_level, created_at, updated_at) VALUES
    ('HH0001', 'Áo phông nam', 1, 'Áo phông nam chất liệu cotton, màu sắc đa dạng.', 1, 150000, 'available', 0, 10, NOW(), NOW()),
    ('HH0002', 'Đầm nữ', 1, 'Đầm nữ thanh lịch, phù hợp cho các buổi tiệc.', 1, 350000, 'available', 0, 10, NOW(), NOW()),
    ('HH0003', 'Giày thể thao nam', 1, 'Giày thể thao nam thiết kế thể thao, thoải mái.', 1, 450000, 'available', 0, 15, NOW(), NOW()),
    ('HH0004', 'Túi xách nữ', 1, 'Túi xách nữ thời trang, phù hợp với nhiều phong cách.', 1, 200000, 'available', 0, 5, NOW(), NOW()),
    ('HH0005', 'Kính mắt thời trang', 1, 'Kính mắt thời trang nam nữ, chống tia UV.', 1, 120000, 'available', 0, 20, NOW(), NOW());

INSERT INTO products (code, name, category_id, description, unit_id, selling_price, status, refrigerated, minimum_stock_level, created_at, updated_at) VALUES
    ('HH0006', 'Điện thoại iPhone 14', 2, 'Điện thoại iPhone 14, màn hình OLED, camera chất lượng cao.', 1, 25000000, 'available', 0, 10, NOW(), NOW()),
    ('HH0007', 'Laptop Dell XPS 13', 2, 'Laptop Dell XPS 13, cấu hình mạnh mẽ, màn hình cảm ứng.', 1, 30000000, 'available', 0, 5, NOW(), NOW()),
    ('HH0008', 'Tai nghe Bluetooth Sony', 2, 'Tai nghe Bluetooth Sony, âm thanh sống động.', 1, 2000000, 'available', 0, 50, NOW(), NOW()),
    ('HH0009', 'Máy tính bảng Samsung Galaxy Tab S8', 2, 'Máy tính bảng Samsung Galaxy Tab S8, màn hình 11 inch.', 1, 15000000, 'available', 0, 8, NOW(), NOW()),
    ('HH0010', 'Smartwatch Apple Watch Series 7', 2, 'Smartwatch Apple Watch Series 7, hỗ trợ theo dõi sức khỏe.', 1, 10000000, 'available', 0, 15, NOW(), NOW());

INSERT INTO products (code, name, category_id, description, unit_id, selling_price, status, refrigerated, minimum_stock_level, created_at, updated_at) VALUES
    ('HH0011', 'Kem dưỡng da mặt', 3, 'Kem dưỡng da mặt giúp làm mềm và dưỡng ẩm cho da.', 1, 250000, 'available', 0, 20, NOW(), NOW()),
    ('HH0012', 'Son môi màu đỏ', 3, 'Son môi với màu sắc tươi tắn, phù hợp với mọi phong cách.', 1, 150000, 'available', 0, 30, NOW(), NOW()),
    ('HH0013', 'Sữa tắm thư giãn', 3, 'Sữa tắm có hương thơm dễ chịu, giúp thư giãn cơ thể.', 1, 120000, 'available', 0, 25, NOW(), NOW()),
    ('HH0014', 'Dầu gội đầu', 3, 'Dầu gội đầu cho tóc mềm mượt, giảm gãy rụng.', 1, 100000, 'available', 0, 10, NOW(), NOW());

INSERT INTO products (code, name, category_id, description, unit_id, selling_price, status, refrigerated, minimum_stock_level, created_at, updated_at) VALUES
    ('HH0015', 'Mi gói', 4, 'Mi gói ăn liền, dễ chế biến, nhiều hương vị.', 1, 12000, 'available', 0, 50, NOW(), NOW()),
    ('HH0016', 'Cà phê rang xay', 4, 'Cà phê rang xay, đậm đà hương vị tự nhiên.', 1, 80000, 'available', 0, 30, NOW(), NOW()),
    ('HH0017', 'Sữa tươi không đường', 4, 'Sữa tươi không đường, nguồn dinh dưỡng cho cơ thể.', 1, 40000, 'available', 1, 40, NOW(), NOW()),
    ('HH0018', 'Bánh mì sandwich', 4, 'Bánh mì sandwich mềm, thích hợp cho bữa sáng.', 1, 25000, 'available', 0, 20, NOW(), NOW()),
    ('HH0019', 'Nước giải khát Coca Cola', 4, 'Nước giải khát Coca Cola, sảng khoái cho mọi người.', 1, 15000, 'available', 0, 60, NOW(), NOW());




INSERT INTO batches (id, code, product_id, price, manufacturing_date, expiry_date,created_at, updated_at) VALUES
  (1, 'LH0001', 1, 140000, '2024-01-01', '2025-01-01', NOW(), NOW()),
    (2, 'LH0002', 2, 340000, '2024-02-01', '2025-02-01', NOW(), NOW()),
    (3, 'LH0003', 3, 440000, '2024-03-01', '2025-03-01', NOW(), NOW()),
    (4, 'LH0004', 4, 190000, '2024-04-01', '2025-04-01', NOW(), NOW()),
    (5, 'LH0005', 5, 110000, '2024-05-01', '2025-05-01', NOW(), NOW()),
    (6, 'LH0006', 6, 24000000, '2024-06-01', '2025-06-01', NOW(), NOW()),
    (7, 'LH0007', 7, 29000000, '2024-07-01', '2025-07-01', NOW(), NOW()),
    (8, 'LH0008', 8, 1900000, '2024-08-01', '2025-08-01', NOW(), NOW()),
    (9, 'LH0009', 9, 14000000, '2024-09-01', '2025-09-01', NOW(), NOW()),
    (10, 'LH0010', 10, 9000000, '2024-10-01', '2025-10-01', NOW(), NOW()),
    (11, 'LH0011', 11, 140000, '2024-11-01', '2025-11-01', NOW(), NOW()),
    (12, 'LH0012', 12, 340000, '2024-12-01', '2025-12-01', NOW(), NOW()),
    (13, 'LH0013', 13, 440000, '2024-01-15', '2025-01-15', NOW(), NOW()),
    (14, 'LH0014', 14, 190000, '2024-02-15', '2025-02-15', NOW(), NOW()),
    (15, 'LH0015', 15, 110000, '2024-03-15', '2025-03-15', NOW(), NOW()),
    (16, 'LH0016', 16, 24000000, '2024-04-15', '2025-04-15', NOW(), NOW()),
    (17, 'LH0017', 17, 29000000, '2024-05-15', '2025-05-15', NOW(), NOW()),
    (18, 'LH0018', 18, 1900000, '2024-06-15', '2025-06-15', NOW(), NOW()),
    (19, 'LH0019', 19, 14000000, '2024-07-15', '2025-07-15', NOW(), NOW());

INSERT INTO inventories (id, batch_id, warehouse_id, quantity_available, created_at, updated_at) VALUES
    (1, 1, 1, 150, NOW(), NOW()),
    (2, 2, 2, 200, NOW(), NOW()),
    (3, 3, 3, 300, NOW(), NOW()),
    (4, 4, 4, 120, NOW(), NOW()),
    (5, 5, 1, 180, NOW(), NOW()),
    (6, 6, 2, 110, NOW(), NOW()),
    (7, 7, 3, 130, NOW(), NOW()),
    (8, 8, 4, 140, NOW(), NOW()),
    (9, 9, 1, 160, NOW(), NOW()),
    (10, 10, 2, 170, NOW(), NOW()),
    (11, 11, 3, 150, NOW(), NOW()),
    (12, 12, 4, 200, NOW(), NOW()),
    (13, 13, 1, 300, NOW(), NOW()),
    (14, 14, 2, 120, NOW(), NOW()),
    (15, 15, 3, 180, NOW(), NOW()),
    (16, 16, 4, 110, NOW(), NOW()),
    (17, 17, 1, 130, NOW(), NOW()),
    (18, 18, 2, 140, NOW(), NOW()),
    (19, 19, 3, 160, NOW(), NOW());


-- INSERT INTO images (id, url, imageable_id, imageable_type, created_at, updated_at) VALUES

-- INSERT INTO goods_issues(id, code, creator_id, warehouse_id, customer_id,created_at,updated_at) VALUES 

-- INSERT INTO goods_issue_details( id, goods_issue_id,product_id, quantity, unit_price, discount) VALUES

-- INSERT INTO goods_issue_batches (id, goods_issue_detail_id, warehouse_id, batch_id, quantity,created_at,updated_at) VALUES
 
-- INSERT INTO goods_receipts(id, code,warehouse_id, creator_id,provider_id,created_at,updated_at) VALUES

-- INSERT INTO goods_receipt_details(id, goods_receipt_id, product_id, quantity, unit_price, discount, manufacturing_date,expiry_date,created_at,updated_at) VALUES

-- Insert into attributes table
INSERT INTO attributes (id, category_id, name, created_at, updated_at) VALUES
    -- Attributes for 'Thời trang' category
    (1, 1, 'Chất liệu', NOW(), NOW()),
    (2, 1, 'Kích cỡ', NOW(), NOW()),
    (3, 1, 'Màu sắc', NOW(), NOW()),

    -- Attributes for 'Điện tử và Công nghệ' category
    (4, 2, 'Bộ nhớ RAM', NOW(), NOW()),
    (5, 2, 'Dung lượng pin', NOW(), NOW()),

    -- Attributes for 'Sức khỏe và Làm đẹp' category
    (6, 3, 'Công dụng', NOW(), NOW()),
    (7, 3, 'Thành phần', NOW(), NOW());


-- Insert into attribute_values table
INSERT INTO attribute_values (id, attribute_id, value, created_at, updated_at) VALUES
    -- Values for attributes of 'Thời trang' category
    (1, 1, 'Cotton', NOW(), NOW()),
    (2, 1, 'Polyester', NOW(), NOW()),
    (3, 2, 'S', NOW(), NOW()),
    (4, 2, 'M', NOW(), NOW()),
    (5, 3, 'Đen', NOW(), NOW()),
    (6, 3, 'Trắng', NOW(), NOW()),

    -- Values for attributes of 'Điện tử và Công nghệ' category
    (7, 4, '8GB', NOW(), NOW()),
    (8, 4, '16GB', NOW(), NOW()),
    (9, 5, '3000mAh', NOW(), NOW()),
    (10, 5, '5000mAh', NOW(), NOW());

-- Insert into product_values table
INSERT INTO product_values (id, product_id, attribute_value_id, created_at, updated_at) VALUES
    -- Values for 'Áo phông nam'
    (1, 1, 1, NOW(), NOW()), -- Chất liệu: Cotton
    (2, 1, 3, NOW(), NOW()), -- Kích cỡ: S
    
    -- Values for 'Đầm nữ'
    (3, 2, 2, NOW(), NOW()), -- Chất liệu: Polyester
    (4, 2, 4, NOW(), NOW()), -- Kích cỡ: M

    -- Values for 'Điện thoại iPhone 14'
    (5, 6, 7, NOW(), NOW()), -- Bộ nhớ RAM: 8GB
    (6, 6, 9, NOW(), NOW()), -- Dung lượng pin: 3000mAh
    
    -- Values for 'Laptop Dell XPS 13'
    (7, 7, 8, NOW(), NOW()), -- Bộ nhớ RAM: 16GB
    (8, 7, 10, NOW(), NOW()), -- Dung lượng pin: 5000mAh

    -- Values for 'Kem dưỡng da mặt'
    (9, 11, 6, NOW(), NOW()), -- Công dụng: dưỡng ẩm
    (10, 11, 7, NOW(), NOW()), -- Thành phần: Thành phần tự nhiên

    -- Values for 'Giày thể thao nam'
    (11, 3, 1, NOW(), NOW()), -- Chất liệu: Cotton
    (12, 3, 4, NOW(), NOW()), -- Kích cỡ: M

    -- Values for 'Túi xách nữ'
    (13, 4, 2, NOW(), NOW()), -- Chất liệu: Polyester
    (14, 4, 5, NOW(), NOW()), -- Màu sắc: Đen

    -- Values for 'Tai nghe Bluetooth Sony'
    (15, 8, 7, NOW(), NOW()), -- Bộ nhớ RAM: 8GB
    (16, 8, 9, NOW(), NOW()), -- Dung lượng pin: 3000mAh

    -- Values for 'Smartwatch Apple Watch Series 7'
    (17, 10, 8, NOW(), NOW()), -- Bộ nhớ RAM: 16GB
    (18, 10, 10, NOW(), NOW()), -- Dung lượng pin: 5000mAh

    -- Values for 'Son môi màu đỏ'
    (19, 12, 6, NOW(), NOW()), -- Công dụng: dưỡng ẩm
    (20, 12, 7, NOW(), NOW()); -- Thành phần: Thành phần tự nhiên


-- INSERT INTO stock_takes( id,code, date, user_id, warehouse_id,notes) VALUES 

-- INSERT INTO stock_take_details(id, stock_take_id, product_id,inventory_quantity, actual_quantity, price,created_at,updated_at) VALUES

