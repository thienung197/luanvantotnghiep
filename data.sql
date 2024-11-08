
INSERT INTO locations (id, street_address, ward, district, city, latitude, longitude, created_at, updated_at) VALUES
(1, NULL, 'Krông Jing', 'M', 'Đắk Lắk', 12.759451, 108.724802, '2024-11-08 17:32:48', '2024-11-08 17:32:48'),
(2, '0', 'An Phú', 'Thủ Đức', 'Hồ Chí Minh', 10.802134, 106.742250, '2024-11-08 19:01:37', '2024-11-08 19:01:37'),
(3, '0', 'Kim Liên', 'Đống Đa', 'Hà Nội', 21.006237, 105.835247, '2024-11-08 19:03:01', '2024-11-08 19:03:01'),
(4, '0', 'An Phú', 'Ninh Kiều', 'Cần Thơ', 10.033080, 105.778805, '2024-11-08 19:04:11', '2024-11-08 19:04:11'),
(5, '0', 'An Hải Đông', 'Sơn Trà', 'Đà Nẵng', 16.054301, 108.239322, '2024-11-08 19:05:47', '2024-11-08 19:05:47'),
(6, '0', 'Thới Hòa', 'Ô Môn', 'Cần Thơ', 10.117914, 105.618333, '2024-11-08 19:06:45', '2024-11-08 19:06:45'),
(7, '0', 'Cái Khế', 'Ninh Kiều', 'Cần Thơ', 10.046767, 105.784807, '2024-11-08 19:07:34', '2024-11-08 19:07:34'),
(8, '0', 'Phong Mỹ', 'Cao Lãnh', 'Đồng Tháp', 10.532860, 105.580467, '2024-11-08 19:08:23', '2024-11-08 19:08:23'),
(9, '0', 'Nguyễn Cư Trinh', '1', 'Hồ Chí Minh', 10.763075, 106.686301, '2024-11-08 19:09:01', '2024-11-08 19:09:01'),
(10, '0', 'An Khánh', 'Ninh Kiều', 'Cần Thơ', 10.031548, 105.755405, '2024-11-08 19:09:36', '2024-11-08 19:09:36');

INSERT INTO providers (id, name, phone, email, status, location_id, created_at, updated_at) VALUES
(1, 'Nhà cung cấp Thời trang', '0874758463', 'thoitrang@gmail.com', 'active', 6, '2024-11-08 19:06:45', '2024-11-08 19:06:45'),
(2, 'Nhà cung cấp FPT', '0864859674', 'fpt@gmail.com', 'active', 7, '2024-11-08 19:07:34', '2024-11-08 19:07:34'),
(3, 'Nhà cung cấp Fahasa', '0746384659', 'fahasa@gmail.com', 'active', 8, '2024-11-08 19:08:23', '2024-11-08 19:08:23'),
(4, 'Nhà cung cấp Mỹ phẩm', '0846586464', 'mypham@gmail.com', 'active', 9, '2024-11-08 19:09:01', '2024-11-08 19:09:01'),
(5, 'Nhà cung cấp Bách hóa', '0764856596', 'bachhoa@gmail.com', 'active', 10, '2024-11-08 19:09:36', '2024-11-08 19:09:36');


INSERT INTO warehouses (id, name, capacity, size, isRefrigerated, location_id, created_at, updated_at) VALUES
(1, 'Nhà kho An Phú - Thủ Đức - Hồ Chí Minh', 5000, 500.00, 1, 2, '2024-11-08 19:01:37', '2024-11-08 19:01:37'),
(2, 'Nhà kho Kim Liên - Đống Đa - Hà Nội', 4500, 450.00, 1, 3, '2024-11-08 19:03:01', '2024-11-08 19:03:01'),
(3, 'Nhà kho Hưng Thạnh - Ninh Kiều - An Phú', 4000, 400.00, 1, 4, '2024-11-08 19:04:11', '2024-11-08 19:04:11'),
(4, 'Nhà kho An Hải Đông - Sơn Trà - Đà Nẵng', 450, 4500.00, 1, 5, '2024-11-08 19:05:47', '2024-11-08 19:05:47');

INSERT INTO users (id, name, gender, birth_date, phone, status, email, password, warehouse_id, location_id, type, created_at, updated_at) VALUES
(1, 'admin', NULL, NULL, NULL, 'active', 'admin@gmail.com', '$2y$12$KkoFqkZNHzbc89pTA8r0AuMP.OEGi5CW9cYpHEKGeqQ.3aF.Xg/qm', NULL, NULL, 'admin', '2024-11-08 17:26:42', '2024-11-08 17:26:42'),
(2, 'admin 2', 'male', '2024-11-13', '0974537463', 'active', 'admin2@gmail.com', '$2y$12$DskYFuF/DpYZjkxn5Au1c.wd8.HVt4G22Sefkkmkt7XgPaOaWAbRa', NULL, 1, 'customer', '2024-11-08 17:32:49', '2024-11-08 17:32:49');

-- INSERT INTO permissions(id,code,name,group,created_at,updated_at) VALUES



INSERT INTO roles (id, code, name,created_at,updated_at) VALUES
(1, 'admin', 'Người quản lý tất cả kho', NOW(),NOW()),
(2,'manager', 'Nguời quản lý kho riêng',NOW(),NOW()),
(3,'customer', 'Khách hàng',NOW(),NOW());

-- INSERT INTO model_has_permissions (permission_id, model-type, model_id) VALUES

INSERT INTO model_has_roles (role_id, model_type, model_id) VALUES
(1, 'App\Models\User', 1),
(1, 'App\\Models\\User', 2);

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
    (2, 'Nhà sách', NOW(), NOW()),
    (3, 'Điện thoại và phụ kiện', NOW(), NOW()),
    (4, 'Máy tính và Laptop', NOW(), NOW()),
    (5, 'Thiết bị gia dụng', NOW(), NOW()),
    (6, 'Sắc đẹp', NOW(), NOW()),
    (7, 'Bách hóa', NOW(), NOW());


INSERT INTO products (code, name, category_id, description, unit_id, selling_price, status, refrigerated, minimum_stock_level, created_at, updated_at) VALUES
('SP0001', 'Áo sơ mi nam trắng', 1, 'Áo sơ mi nam trắng, chất liệu cotton mềm mại, thoáng mát, thích hợp cho mọi dịp.', 1, 350000, 'active', false, 50, NOW(), NOW()),
('SP0002', 'Quần jeans nam', 1, 'Quần jeans nam, kiểu dáng thời trang, chất liệu bền bỉ, dễ dàng kết hợp với áo thun hoặc sơ mi.', 1, 500000, 'active', false, 45, NOW(), NOW()),
('SP0003', 'Áo thun nam đen', 1, 'Áo thun nam đen, chất liệu cotton thoáng khí, dễ dàng kết hợp với các loại quần.', 1, 250000, 'active', false, 55, NOW(), NOW()),
('SP0004', 'Áo khoác nữ kaki', 1, 'Áo khoác nữ kaki, thiết kế nhẹ nhàng nhưng sang trọng, phù hợp cho mọi mùa.', 1, 650000, 'active', false, 30, NOW(), NOW()),
('SP0005', 'Giày thể thao nam Adidas', 1, 'Giày thể thao nam Adidas, đế cao su chống trượt, rất phù hợp cho các hoạt động thể thao.', 1, 1200000, 'active', false, 25, NOW(), NOW()),
('SP0006', 'Bộ vest nam', 1, 'Bộ vest nam lịch lãm, thiết kế thời trang, chất liệu cao cấp, phù hợp với các sự kiện trang trọng.', 1, 2000000, 'active', false, 15, NOW(), NOW()),
('SP0007', 'Quần tây nam', 1, 'Quần tây nam, thiết kế tinh tế, thích hợp cho môi trường công sở hoặc các sự kiện lịch sự.', 1, 550000, 'active', false, 50, NOW(), NOW()),
('SP0008', 'Áo len nam', 1, 'Áo len nam dày dặn, ấm áp, lý tưởng cho mùa đông.', 1, 700000, 'active', false, 40, NOW(), NOW()),
('SP0009', 'Áo nữ phong cách', 1, 'Áo nữ phong cách, mang đến sự trẻ trung, phong cách cho người mặc.', 1, 400000, 'active', false, 60, NOW(), NOW()),
('SP0010', 'Bộ đồ thể thao nam', 1, 'Bộ đồ thể thao nam, thoáng khí và co giãn, hoàn hảo cho các hoạt động thể dục thể thao.', 1, 450000, 'active', false, 65, NOW(), NOW()),
('SP0011', 'Thắt lưng nam da thật', 1, 'Thắt lưng nam da thật, mang đến vẻ sang trọng và lịch sự cho bộ trang phục.', 1, 300000, 'active', false, 70, NOW(), NOW()),
('SP0012', 'Mũ lưỡi trai nam', 1, 'Mũ lưỡi trai nam, chất liệu thoáng mát, dễ dàng kết hợp với nhiều bộ trang phục.', 1, 150000, 'active', false, 55, NOW(), NOW()),
('SP0013', 'Sách Tiếng Việt', 2, 'Sách Tiếng Việt về văn hóa, lịch sử, giáo dục.', 1, 120000, 'active', false, 50, NOW(), NOW()),
('SP0014', 'Sách Tiếng Anh', 2, 'Sách Tiếng Anh với nhiều chủ đề đa dạng và bổ ích.', 1, 150000, 'active', false, 45, NOW(), NOW()),
('SP0015', 'Bút bi 4 màu', 2, 'Bút bi 4 màu dùng cho học sinh và nhân viên văn phòng.', 1, 20000, 'active', false, 100, NOW(), NOW()),
('SP0016', 'Vở viết học sinh', 2, 'Vở viết học sinh với bìa cứng, giấy chất lượng cao.', 1, 30000, 'active', false, 150, NOW(), NOW()),
('SP0017', 'Điện thoại Samsung Galaxy', 3, 'Điện thoại Samsung Galaxy, màn hình 6.5 inch, camera 64MP, pin 5000mAh.', 1, 8000000, 'active', false, 20, NOW(), NOW()),
('SP0018', 'Điện thoại iPhone 14', 3, 'Điện thoại iPhone 14, màn hình Super Retina XDR, camera 12MP, chip A15 Bionic.', 1, 24000000, 'active', false, 15, NOW(), NOW()),
('SP0019', 'Tai nghe Bluetooth', 3, 'Tai nghe Bluetooth chống ồn, âm thanh chất lượng cao, kết nối ổn định.', 1, 1200000, 'active', false, 50, NOW(), NOW()),
('SP0020', 'Cáp sạc Lightning', 3, 'Cáp sạc Lightning chính hãng, dài 1m, tương thích với các dòng iPhone.', 1, 150000, 'active', false, 100, NOW(), NOW()),
('SP0021', 'Sạc nhanh 18W', 3, 'Sạc nhanh 18W, tương thích với nhiều dòng điện thoại Android và iPhone.', 1, 300000, 'active', false, 80, NOW(), NOW()),
('SP0022', 'Laptop Dell Inspiron', 4, 'Laptop Dell Inspiron 15.6 inch, chip Intel Core i5, 8GB RAM, 512GB SSD.', 1, 15000000, 'active', false, 10, NOW(), NOW()),
('SP0023', 'Laptop HP Pavilion', 4, 'Laptop HP Pavilion 14 inch, chip Intel Core i7, 16GB RAM, 1TB HDD.', 1, 18000000, 'active', false, 8, NOW(), NOW()),
('SP0024', 'Laptop MacBook Air', 4, 'MacBook Air 13 inch, chip M1, 8GB RAM, 256GB SSD, màn hình Retina.', 1, 22000000, 'active', false, 5, NOW(), NOW()),
('SP0025', 'Máy tính để bàn Acer', 4, 'Máy tính để bàn Acer, chip Intel Core i5, 16GB RAM, 1TB HDD.', 1, 10000000, 'active', false, 12, NOW(), NOW()),
('SP0026', 'Máy tính bảng Samsung Galaxy Tab', 4, 'Máy tính bảng Samsung Galaxy Tab S7, màn hình 11 inch, chip Snapdragon 865+, 6GB RAM.', 1, 8000000, 'active', false, 20, NOW(), NOW()),
('SP0027', 'Máy giặt cửa ngang', 5, 'Máy giặt cửa ngang, 7kg, tiết kiệm điện.', 1, 8000000, 'active', false, 15, NOW(), NOW()),
('SP0028', 'Nồi chiên không dầu', 5, 'Nồi chiên không dầu 3.5L, chế biến món ăn lành mạnh.', 1, 1500000, 'active', false, 25, NOW(), NOW()),
('SP0029', 'Tủ lạnh 150L', 5, 'Tủ lạnh 150L, tiết kiệm điện năng, thích hợp cho gia đình nhỏ.', 1, 4500000, 'active', false, 20, NOW(), NOW()),
('SP0030', 'Bàn ủi hơi nước', 5, 'Bàn ủi hơi nước, công suất mạnh mẽ, giúp là ủi nhanh chóng.', 1, 500000, 'active', false, 40, NOW(), NOW()),
('SP0031', 'Máy lọc không khí', 5, 'Máy lọc không khí, giúp không gian trong lành, sạch khuẩn.', 1, 3000000, 'active', false, 12, NOW(), NOW()),
('SP0032', 'Máy ép trái cây', 5, 'Máy ép trái cây, ép nhanh, tiết kiệm thời gian.', 1, 850000, 'active', false, 50, NOW(), NOW()),
('SP0033', 'Quạt điện 3 cánh', 5, 'Quạt điện 3 cánh, công suất mạnh, làm mát hiệu quả.', 1, 450000, 'active', false, 30, NOW(), NOW()),
('SP0034', 'Máy xay sinh tố', 5, 'Máy xay sinh tố, xay nhuyễn nhanh chóng các loại trái cây.', 1, 350000, 'active', false, 18, NOW(), NOW()),
('SP0035', 'Lò vi sóng 20L', 5, 'Lò vi sóng 20L, chế biến thức ăn nhanh gọn, tiết kiệm thời gian.', 1, 1200000, 'active', false, 10, NOW(), NOW()),
('SP0036', 'Bếp từ đơn', 5, 'Bếp từ đơn, dễ sử dụng, tiết kiệm năng lượng.', 1, 1000000, 'active', false, 35, NOW(), NOW()),
('SP0037', 'Tủ lạnh mini', 5, 'Tủ lạnh mini, dung tích 50L, thích hợp cho sinh viên.', 1, 2500000, 'active', false, 8, NOW(), NOW()),
('SP0038', 'Máy rửa bát', 5, 'Máy rửa bát, tiết kiệm thời gian và năng lượng cho gia đình.', 1, 7000000, 'active', false, 5, NOW(), NOW()),
('SP0039', 'Sữa rửa mặt', 6, 'Sữa rửa mặt cho da nhạy cảm, làm sạch sâu.', 1, 150000, 'active', false, 20, NOW(), NOW()),
('SP0040', 'Kem dưỡng da', 6, 'Kem dưỡng da ban đêm, chống lão hóa và nuôi dưỡng da.', 1, 300000, 'active', false, 18, NOW(), NOW()),
('SP0041', 'Mặt nạ giấy', 6, 'Mặt nạ giấy dưỡng da, giúp da mềm mại, sáng khỏe.', 1, 50000, 'active', false, 50, NOW(), NOW()),
('SP0042', 'Son môi', 6, 'Son môi nhiều màu sắc, lâu trôi và không làm khô môi.', 1, 120000, 'active', false, 30, NOW(), NOW()),
('SP0043', 'Nước hoa', 6, 'Nước hoa nhẹ nhàng, quyến rũ, phù hợp cho mọi dịp.', 1, 350000, 'active', false, 25, NOW(), NOW()),
('SP0044', 'Kem chống nắng', 6, 'Kem chống nắng SPF 50+, bảo vệ da khỏi tác hại của tia UV.', 1, 200000, 'active', false, 15, NOW(), NOW()),
('SP0045', 'Máy massage mặt', 6, 'Máy massage mặt giúp thư giãn và tái tạo da.', 1, 700000, 'active', false, 10, NOW(), NOW()),
('SP0046', 'Nước tẩy trang', 6, 'Nước tẩy trang làm sạch sâu, giúp làn da mịn màng.', 1, 100000, 'active', false, 40, NOW(), NOW()),
('SP0047', 'Dầu gội', 6, 'Dầu gội giúp tóc chắc khỏe, giảm gãy rụng.', 1, 150000, 'active', false, 22, NOW(), NOW()),
('SP0048', 'Kem trị mụn', 6, 'Kem trị mụn, làm giảm mụn và vết thâm hiệu quả.', 1, 180000, 'active', false, 20, NOW(), NOW()),
('SP0049', 'Nước lau sàn', 7, 'Nước lau sàn công nghiệp, dễ dàng lau sạch và khử mùi.', 1, 50000, 'active', false, 200, NOW(), NOW()),
('SP0050', 'Bình xịt côn trùng', 7, 'Bình xịt diệt côn trùng, hiệu quả nhanh chóng.', 1, 25000, 'active', false, 150, NOW(), NOW()),
('SP0051', 'Bàn chải vệ sinh', 7, 'Bàn chải vệ sinh đa năng, sử dụng cho nhà tắm, nhà bếp.', 1, 10000, 'active', false, 200, NOW(), NOW()),
('SP0052', 'Giấy thấm dầu', 7, 'Giấy thấm dầu dùng trong nấu ăn, hút mỡ hiệu quả.', 1, 15000, 'active', false, 100, NOW(), NOW()),
('SP0053', 'Màng bọc thực phẩm', 7, 'Màng bọc thực phẩm tiện lợi, bảo quản thực phẩm lâu hơn.', 1, 12000, 'active', false, 300, NOW(), NOW()),
('SP0054', 'Dụng cụ cắt giấy', 7, 'Dụng cụ cắt giấy chính xác, dễ sử dụng.', 1, 20000, 'active', false, 120, NOW(), NOW()),
('SP0055', 'Nước giặt quần áo', 7, 'Nước giặt quần áo giữ quần áo sạch sẽ, thơm mát.', 1, 45000, 'active', false, 500, NOW(), NOW()),
('SP0056', 'Khay đựng thực phẩm', 7, 'Khay đựng thực phẩm bằng nhựa, dễ dàng bảo quản.', 1, 30000, 'active', false, 150, NOW(), NOW()),
('SP0057', 'Túi đựng rác', 7, 'Túi đựng rác chắc chắn, không rách khi sử dụng.', 1, 10000, 'active', false, 500, NOW(), NOW()),
('SP0058', 'Bình đựng nước', 7, 'Bình đựng nước tiện lợi, dễ dàng mang theo khi đi làm.', 1, 35000, 'active', false, 100, NOW(), NOW()),
('SP0059', 'Thùng rác', 7, 'Thùng rác thông minh, dễ dàng sử dụng trong gia đình.', 1, 70000, 'active', false, 200, NOW(), NOW()),
('SP0060', 'Chổi quét nhà', 7, 'Chổi quét nhà làm bằng chất liệu cao cấp, bền bỉ theo thời gian.', 1, 25000, 'active', false, 150, NOW(), NOW()),
('SP0061', 'Giỏ đựng đồ', 7, 'Giỏ đựng đồ bằng nhựa, dễ dàng sắp xếp và di chuyển.', 1, 40000, 'active', false, 80, NOW(), NOW()),
('SP0062', 'Nắp đậy thực phẩm', 7, 'Nắp đậy thực phẩm bằng nhựa bền, dễ dàng sử dụng.', 1, 18000, 'active', false, 200, NOW(), NOW()),
('SP0063', 'Thảm lau chân', 7, 'Thảm lau chân mềm mại, hút ẩm nhanh chóng, giữ sàn sạch.', 1, 25000, 'active', false, 120, NOW(), NOW()),
('SP0064', 'Dụng cụ bào rau củ', 7, 'Dụng cụ bào rau củ tiện lợi, giúp chuẩn bị món ăn nhanh chóng.', 1, 30000, 'active', false, 150, NOW(), NOW()),
('SP0065', 'Bộ dụng cụ nhà bếp', 7, 'Bộ dụng cụ nhà bếp đầy đủ, giúp bạn nấu ăn hiệu quả.', 1, 150000, 'active', false, 100, NOW(), NOW()),
('SP0066', 'Dép đi trong nhà', 7, 'Dép đi trong nhà bằng cao su mềm, êm ái, thoải mái.', 1, 50000, 'active', false, 150, NOW(), NOW()),
('SP0067', 'Nón bảo hiểm', 7, 'Nón bảo hiểm chất lượng cao, bảo vệ đầu hiệu quả.', 1, 120000, 'active', false, 50, NOW(), NOW()),
('SP0068', 'Giấy ăn', 7, 'Giấy ăn mềm mại, sử dụng tiện lợi trong mọi bữa ăn.', 1, 20000, 'active', false, 300, NOW(), NOW()),
('SP0069', 'Bình giữ nhiệt', 7, 'Bình giữ nhiệt bằng inox, giữ nước nóng lạnh lâu dài.', 1, 70000, 'active', false, 100, NOW(), NOW()),
('SP0070', 'Găng tay cao su', 7, 'Găng tay cao su giúp bảo vệ tay khi làm việc trong nhà.', 1, 25000, 'active', false, 200, NOW(), NOW()),
('SP0071', 'Cây lau nhà', 7, 'Cây lau nhà tiện lợi, dễ dàng vệ sinh mọi ngóc ngách.', 1, 35000, 'active', false, 150, NOW(), NOW()),
('SP0072', 'Cốc thủy tinh', 7, 'Cốc thủy tinh đẹp, bền, thích hợp cho các bữa tiệc.', 1, 40000, 'active', false, 120, NOW(), NOW()),
('SP0073', 'Chảo chống dính', 7, 'Chảo chống dính an toàn, tiết kiệm dầu mỡ khi nấu ăn.', 1, 80000, 'active', false, 90, NOW(), NOW()),
('SP0074', 'Lồng hấp', 7, 'Lồng hấp thực phẩm bằng inox, giữ trọn hương vị món ăn.', 1, 50000, 'active', false, 150, NOW(), NOW()),
('SP0075', 'Bộ tô chén', 7, 'Bộ tô chén bằng sứ, sang trọng, dễ dàng vệ sinh.', 1, 150000, 'active', false, 100, NOW(), NOW());




-- INSERT INTO batches (id, code, product_id, price, manufacturing_date, expiry_date,created_at, updated_at) VALUES

-- INSERT INTO inventories (id, batch_id,warehouse_id, quantity_available, created_at, updated_at) VALUES 

-- INSERT INTO images (id, url, imageable_id, imageable_type, created_at, updated_at) VALUES

-- INSERT INTO goods_issues(id, code, creator_id, warehouse_id, customer_id,created_at,updated_at) VALUES 

-- INSERT INTO goods_issue_details( id, goods_issue_id,product_id, quantity, unit_price, discount) VALUES


-- INSERT INTO goods_receipts(id, code,warehouse_id, creator_id,provider_id,created_at,updated_at) VALUES

-- INSERT INTO goods_receipt_details(id, goods_receipt_id, product_id, quantity, unit_price, discount, manufacturing_date,expiry_date,created_at,updated_at) VALUES

-- INSERT INTO attributes (id,category_id, name, created_at,updated_at) VALUES

-- INSERT INTO attribute_values( id, attribute_id, value,created_at,updated_at) VALUES

-- INSERT INTO product_values( id, product_id, attribute_value_id,created_at,updated_at) VALUES

-- INSERT INTO stock_takes( id,code, date, user_id, warehouse_id,notes) VALUES 

-- INSERT INTO stock_take_details(id, stock_take_id, product_id,inventory_quantity, actual_quantity, price,created_at,updated_at) VALUES

-- INSERT INTO goods_issue_batches (id, goods_issue_detail_id, warehouse_id, batch_id, quantity,created_at,updated_at) VALUES
