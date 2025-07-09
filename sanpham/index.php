<?php
include __DIR__ . '/../includes/check_login.php';

include __DIR__ . '/../includes/connect.php';
$products = callAPI("admin/getallSanPham");
$dsDanhMuc = callAPI("getAllMaDanhMuc")["danh_sach_danh_muc"] ?? [];
$dsMauSac = callAPI("getAllMauSac") ?? [];
$dsDanhGia = callAPI("get/thongKeDanhGia");

function getTenDanhMucTheoMa($dsDanhMuc, $ma)
{
    foreach ($dsDanhMuc as $dm) {
        if ($dm['ma_danh_muc'] == $ma) {
            return $dm['ten_danh_muc'];
        }
    }
    return 'Không xác định';
}

?>

<!DOCTYPE html>

<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Danh sách sản phẩm</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            background: #f8f8f8;
        }

        .main-content {
            margin-left: 130px;
        }

        h2 {
            margin-top: 20px;
            color: #333;
        }

        .header-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        a.add-btn {
            display: inline-block;
            margin-bottom: 20px;
            padding: 10px 18px;
            background: #007bff;
            color: white;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
            box-shadow: 0 0 0 1px #0056b3;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            background: #fff;
            border: 2px solid #ccc;
            border-radius: 8px;
            overflow: hidden;
        }

        th {
            background-color: #007bff;
            color: white;
            padding: 12px 10px;
            border: 1px solid #ccc;
        }

        td {
            border: 1px solid #ccc;
            padding: 12px 10px;
            color: #333;
            vertical-align: middle;
        }

        td.center {
            text-align: center;
        }

        img {
            max-height: 70px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }

        td.actions {
            text-align: center;
        }

        .btn-icon {
            display: inline-flex;
            width: 36px;
            height: 36px;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            margin: 2px;
            color: white;
            text-decoration: none;
        }

        .btn-edit {
            background-color: #28a745;
        }

        .btn-delete {
            background-color: #dc3545;
        }

        .popup-form {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.4);
            z-index: 1000;

            /* ✅ Căn giữa form */
            display: flex;
            justify-content: center;
            align-items: center;
        }


        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .form-container {
            background: white;
            padding: 24px;
            border-radius: 12px;
            width: 1100px;
            /* 👈 Tăng thêm chiều rộng */
            max-width: 96vw;
            /* 👈 Cho phép hiển thị gần full */
            margin: auto;
            position: relative;
            border: 2px solid #007bff;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
        }


        @keyframes slideDown {
            from {
                transform: translateY(-30px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .form-container h3 {
            margin-top: 0;
            margin-bottom: 24px;
            color: #007bff;
            font-size: 22px;
            text-align: center;
        }

        .form-container label {
            font-weight: 600;
            display: block;
            margin-bottom: 6px;
            color: #333;
        }

        .form-container input,
        .form-container select {
            width: 100%;
            padding: 10px 12px;
            margin-bottom: 16px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 15px;
            transition: border-color 0.2s;
        }

        .form-container input:focus,
        .form-container select:focus {
            outline: none;
            border-color: #007bff;
        }

        .form-container button {
            padding: 10px 20px;
            margin-right: 8px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            font-size: 15px;
        }

        .form-container button[type="submit"] {
            background-color: #28a745;
            color: white;
        }

        .form-container button[type="button"] {
            background-color: #dc3545;
            color: white;
        }

        .input-select {
            width: 100%;
            padding: 10px 12px;
            margin-bottom: 16px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 15px;
        }

        .anh-bien-the-row {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 10px;
        }

        .anh-bien-the-row select,
        .anh-bien-the-row input[type="file"] {
            flex: 1;
            /* Để 2 ô này chia đều */
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
        }

        .anh-bien-the-row button {
            padding: 6px 10px;
            background-color: #dc3545;
            color: white;
            border: none;
            border-radius: 4px;
            font-weight: bold;
            cursor: pointer;
            flex-shrink: 0;
        }



        .pagination li a,
        .pagination li span {
            display: block;
            padding: 6px 12px;
            text-decoration: none;
            border: 1px solid #ddd;
            border-radius: 4px;
            background-color: white;
            color: #007bff;
        }

        .pagination li.active a {
            background-color: #007bff;
            color: white;
            border-color: #007bff;
        }

        .pagination li.disabled span {
            pointer-events: none;
            color: #aaa;
            border-color: #ccc;
            background-color: #f0f0f0;
        }


        .btn-export {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 8px 16px;
            font-size: 14px;
            border-radius: 4px;
            cursor: pointer;
        }

        .btn-export:hover {
            background-color: #218838;
        }

        .form-edit {
            background: white;
            padding: 24px;
            border-radius: 12px;
            width: 1100px;
            max-width: 96vw;
            margin: auto;
            position: relative;
            border: 2px solid #007bff;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
        }

        .form-edit h3 {
            margin-top: 0;
            margin-bottom: 24px;
            color: #007bff;
            font-size: 22px;
            text-align: center;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .form-buttons {
            grid-column: span 2;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 10px;
        }

        .btn-submit {
            padding: 10px 16px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
        }

        .btn-cancel {
            padding: 10px 16px;
            background-color: #dc3545;
            color: white;
            border: none;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
        }

        .form-row {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .form-group {
            flex: 1 1 calc(50% - 20px);
            display: flex;
            flex-direction: column;
        }

        .form-group.full-width {
            flex: 1 1 100%;
        }

        .form-edit input,
        .form-edit select {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 15px;
            margin-top: 6px;
        }

        .form-buttons {
            margin-top: 20px;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }

        .btn-submit,
        .btn-cancel {
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 8px;
            border: none;
            font-weight: bold;
            cursor: pointer;
        }

        .btn-submit {
            background-color: #28a745;
            color: white;
        }

        .btn-cancel {
            background-color: #dc3545;
            color: white;
        }
    </style>
</head>

<body>

    <div class="main-content">
        <h2><i class="fas fa-box"></i> Danh sách sản phẩm</h2>
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">

            <!-- Bộ lọc -->
            <div style="display: flex; gap: 16px; align-items: center; padding: 10px 0;">
                <!-- Lọc theo danh mục -->
                <div style="display: flex; flex-direction: column;">
                    <label for="filterDanhMuc" style="font-weight: bold; margin-bottom: 4px;">Danh mục:</label>
                    <select id="filterDanhMuc" onchange="locSanPham()"
                        style="padding: 6px 12px; border-radius: 6px; border: 1px solid #ccc; min-width: 180px;">
                        <option value="">-- Tất cả danh mục --</option>
                        <?php foreach ($dsDanhMuc as $dm): ?>
                            <option value="<?= $dm['ma_danh_muc'] ?>"><?= htmlspecialchars($dm['ten_danh_muc']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Lọc theo trạng thái -->
                <div style="display: flex; flex-direction: column;">
                    <label for="filterTrangThai" style="font-weight: bold; margin-bottom: 4px;">Trạng thái:</label>
                    <select id="filterTrangThai" onchange="locSanPham()"
                        style="padding: 6px 12px; border-radius: 6px; border: 1px solid #ccc; min-width: 150px;">
                        <option value="">-- Tất cả trạng thái --</option>
                        <option value="1">Hiện</option>
                        <option value="0">Không hiện</option>
                    </select>
                </div>
            </div>

            <!-- Nút thêm sản phẩm -->
            <div style="text-align: right;">
                <a href="#" class="add-btn" onclick="openFormPopup(); return false;">Thêm sản phẩm</a>
            </div>
        </div>

        <div style="text-align: right; margin-bottom: 10px;">
            <button type="button" class="btn-export" onclick="downloadExcel()">📤 Xuất Excel</button>
        </div>

        <div id="popupForm" class="popup-form" style="display: none;">
            <div class="form-container">
                <h3 style="margin-top: 0; font-size: 20px; color: #007bff;">Thêm sản phẩm + ảnh biến thể</h3>
                <form action="/admin/sanpham/them_full.php" method="post" enctype="multipart/form-data" id="productForm">
                    <div style="display: flex; gap: 24px; flex-wrap: wrap;">
                        <!-- CỘT TRÁI: Thông tin sản phẩm -->
                        <div style="flex: 1 1 45%;">
                            <label>Tên sản phẩm:</label>
                            <input type="text" name="ten_san_pham" required
                                oninvalid="this.setCustomValidity('Vui lòng nhập tên sản phẩm')"
                                oninput="this.setCustomValidity('')">

                            <label>Mô tả:</label>
                            <input type="text" name="mo_ta">

                            <label>Giá:</label>
                            <input type="text" id="gia_hien_thi" placeholder="Nhập giá..." required
                                oninvalid="this.setCustomValidity('Vui lòng nhập giá')"
                                oninput="this.setCustomValidity('')">
                            <input type="hidden" name="gia" id="gia">

                            <label>Danh mục:</label>
                            <select name="ma_danh_muc" class="styled-select" required
                                oninvalid="this.setCustomValidity('Vui lòng chọn danh mục')"
                                oninput="this.setCustomValidity('')">
                                <option value="">-- Chọn danh mục --</option>
                                <?php foreach ($dsDanhMuc as $dm): ?>
                                    <option value="<?= $dm['ma_danh_muc'] ?>"><?= htmlspecialchars($dm['ten_danh_muc']) ?></option>
                                <?php endforeach; ?>
                            </select>

                            <label>Ảnh đại diện:</label>
                            <input type="file" name="anh_dai_dien" accept="image/*" required
                                oninvalid="this.setCustomValidity('Vui lòng chọn ảnh đại diện')"
                                oninput="this.setCustomValidity('')">
                        </div>

                        <!-- CỘT PHẢI: Ảnh biến thể -->
                        <!-- Ảnh biến thể -->
                        <div style="flex: 1 1 45%;">
                            <label>Ảnh biến thể:</label>
                            <div id="anhBienTheContainer">
                                <div class="anh-bien-the-row">
                                    <select name="ma_mau" class="styled-select" required>
                                        <option value="">-- Chọn màu sắc --</option>
                                        <?php foreach ($dsMauSac as $mau): ?>
                                            <option value="<?= $mau['ma_mau'] ?>"><?= htmlspecialchars($mau['ten_mau']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <input type="file" name="files[]" multiple accept="image/*" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div style="text-align: right; margin-top: 20px;">
                        <button type="button" onclick="closeFormPopup()" style="background-color: #dc3545; color: white;">Hủy</button>
                        <button type="submit" style="background-color: #28a745; color: white;">Thêm</button>
                    </div>
                </form>

                <script>
                    document.getElementById('productForm').addEventListener('submit', function(e) {
                        const giaHienThi = document.getElementById('gia_hien_thi').value.trim();
                        const gia = giaHienThi.replace(/\D/g, ''); // Lấy số (bỏ dấu chấm, chữ...)

                        if (!gia) {
                            alert('Vui lòng nhập đúng định dạng giá (chỉ bao gồm số)');
                            e.preventDefault();
                            return;
                        }

                        // Gán giá trị đã xử lý vào input ẩn
                        document.getElementById('gia').value = gia;
                    });
                </script>

            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th class="center" style="width: 50px;">STT</th>
                    <th style="width: 200px;">Tên sản phẩm</th>
                    <th style="width: 100px;">Danh mục</th>
                    <th style="width: 150px;">Giá</th>
                    <th style="width: 80px;">Số sao</th>
                    <th style="width: 350px;">Mô tả</th>
                    <th style="width: 100px;">Ảnh</th>
                    <th style="width: 100px;">Trạng thái</th>
                    <th style="width: 120px;">Thao tác</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                <?php
                $dsDanhGia = callAPI("get/thongKeDanhGia");
                $mapSao = [];
                if (is_array($dsDanhGia)) {
                    foreach ($dsDanhGia as $dg) {
                        $mapSao[$dg['ma_san_pham']] = $dg['diem_trung_binh'];
                    }
                }
                ?>

                <?php if (is_array($products) && !empty($products)): ?>
                    <?php $i = 1;
                    foreach ($products as $sp): ?>
                        <?php
                        $ten = $sp['ten_san_pham'] ?? '[Không có tên]';
                        $danhmuc = $sp['ma_danh_muc'] ?? 0;
                        $tenDanhMuc = getTenDanhMucTheoMa($dsDanhMuc, $danhmuc);
                        $gia = $sp['gia'] ?? 0;
                        $trangthai = $sp['trang_thai'] ?? 0;
                        $hienTrangThai = ($trangthai == 1) ? 'Hiện' : 'Không hiện';
                        $anh = $sp['anh_san_pham'] ?? '';
                        $mota = $sp['mo_ta'] ?? '';
                        $ma = $sp['ma_san_pham'] ?? 0;
                        $soSao = $mapSao[$ma] ?? 0;
                        ?>
                        <tr>
                            <td class="center"><?= $i++ ?></td>
                            <td><?= htmlspecialchars($ten) ?></td>
                            <td><?= htmlspecialchars($tenDanhMuc) ?></td>
                            <td><?= number_format($gia, 0, '', '.') ?> VND</td>
                            <td class="center"><?= number_format($soSao, 1) ?> ★</td>
                            <td><?= htmlspecialchars($mota) ?></td>
                            <td>
                                <?php if ($anh): ?>
                                    <img src="https://cuddly-exotic-snake.ngrok-free.app<?= htmlspecialchars($anh) ?>" alt="Ảnh sản phẩm">
                                <?php else: ?>
                                    <em>Không có ảnh</em>
                                <?php endif; ?>
                            </td>
                            <td><?= $hienTrangThai ?></td>
                            <td class="actions">
                                <a href="#" class="btn-icon btn-detail" title="Chi tiết"
                                    onclick="xemChiTietSanPham(<?= $ma ?>, <?= number_format($soSao, 1, '.', '') ?>)">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="#" class="btn-icon btn-edit" title="Sửa" onclick="openEditPopup(<?= $ma ?>); return false;">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="9">Không có sản phẩm nào để hiển thị.</td>
                    </tr>
                <?php endif; ?>
                <!-- Popup hiển thị chi tiết sản phẩm -->
                <div id="popupChiTiet" class="popup-overlay" style="display:none; position:fixed; inset:0; background:#000000aa; justify-content:center; align-items:center; z-index:1000;">
                    <div class="popup-content" style="background:#fff; padding:24px; width:800px; max-height:90vh; overflow:auto; border-radius:8px; position:relative;">
                        <h3>Chi tiết sản phẩm</h3>
                        <div id="thongTinSanPham" style="margin-bottom: 20px;"></div>
                        <div id="anhBienTheSanPham"></div>
                        <button onclick="document.getElementById('popupChiTiet').style.display='none'" style="position:absolute; top:10px; right:10px; background:#dc3545; color:#fff; border:none; padding:6px 12px; border-radius:4px;">Đóng</button>
                    </div>
                </div>
                <!-- 🛠️ Popup Sửa sản phẩm -->
                <div id="popupEditForm" class="popup-form" style="display: none;">
                    <div class="form-edit">
                        <h3 id="editFormTitle">Chỉnh sửa sản phẩm</h3>
                        <form action="sanpham/sua.php" method="post" enctype="multipart/form-data" id="editProductForm">
                            <input type="hidden" name="ma_san_pham" id="edit_ma_san_pham">

                            <div class="form-row">
                                <div class="form-group">
                                    <label>Tên sản phẩm:</label>
                                    <input type="text" name="ten_san_pham" id="edit_ten_san_pham" required>
                                </div>

                                <div class="form-group">
                                    <label>Mô tả:</label>
                                    <input type="text" name="mo_ta" id="edit_mo_ta">
                                </div>

                                <div class="form-group">
                                    <label>Giá:</label>
                                    <input type="text" id="edit_gia_hien_thi" placeholder="Nhập giá..." required>
                                    <input type="hidden" name="gia" id="edit_gia">
                                </div>

                                <div class="form-group">
                                    <label>Danh mục:</label>
                                    <select name="ma_danh_muc" id="edit_ma_danh_muc" required>
                                        <option value="">-- Chọn danh mục --</option>
                                        <?php foreach ($dsDanhMuc as $dm): ?>
                                            <option value="<?= $dm['ma_danh_muc'] ?>"><?= htmlspecialchars($dm['ten_danh_muc']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>Trạng thái:</label>
                                    <select name="trang_thai" id="edit_trang_thai" required>
                                        <option value="1">Hiện</option>
                                        <option value="0">Không hiện</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>Ảnh đại diện mới (nếu muốn thay):</label>
                                    <input type="file" name="anh_dai_dien" accept="image/*">
                                </div>

                                <div class="form-group full-width">
                                    <label>Ảnh hiện tại:</label>
                                    <div id="editPreview" style="margin-top: 10px;"></div>
                                </div>
                            </div>

                            <div class="form-buttons">
                                <button type="submit" class="btn-submit">Cập nhật</button>
                                <button type="button" class="btn-cancel" onclick="closeEditPopup()">Hủy</button>
                            </div>
                        </form>
                    </div>
                </div>
            </tbody>
        </table>
        <div style="margin-top: 20px; display: flex; justify-content: space-between;" id="paginationWrapper">
            <ul class="pagination" style="display: flex; list-style: none; padding: 0; gap: 4px;"></ul>
        </div>


    </div>

    <script>
        // Gán danh sách màu sắc từ PHP
        const dsMauSac = <?= json_encode($dsMauSac) ?>;

        function openFormPopup() {
            document.getElementById('popupForm').style.display = 'block';
            document.getElementById('productForm').reset();
        }

        function closeFormPopup() {
            document.getElementById('popupForm').style.display = 'none';
        }

        function closeEditPopup() {
            document.getElementById('popupEditForm').style.display = 'none';
            document.getElementById('editProductForm').reset();
            document.getElementById('editPreview').innerHTML = '';
        }

        function openEditPopup(maSanPham) {
            fetch('sanpham/lay.php?maSanPham=' + maSanPham)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const sp = data.data;
                        document.getElementById('edit_ma_san_pham').value = sp.ma_san_pham;
                        document.getElementById('edit_ten_san_pham').value = sp.ten_san_pham;
                        document.getElementById('edit_mo_ta').value = sp.mo_ta;
                        document.getElementById('edit_gia').value = sp.gia;
                        document.getElementById('edit_gia_hien_thi').value = Number(sp.gia).toLocaleString("vi-VN") + ' VND';
                        document.getElementById('edit_ma_danh_muc').value = sp.ma_danh_muc;
                        document.getElementById('edit_trang_thai').value = sp.trang_thai;

                        const imgHtml = sp.anh_san_pham ?
                            `<img src="https://cuddly-exotic-snake.ngrok-free.app${sp.anh_san_pham}" style="max-height:100px; border:1px solid #ccc; border-radius:6px;" />` :
                            `<em>Không có ảnh</em>`;
                        document.getElementById('editPreview').innerHTML = imgHtml;
                        document.getElementById('popupEditForm').style.display = 'flex';
                    } else {
                        alert('Không tìm thấy sản phẩm');
                    }
                });
        }

        document.getElementById("edit_gia_hien_thi").addEventListener("input", function() {
            const rawValue = this.value.replace(/[^\d]/g, "");
            document.getElementById("edit_gia").value = rawValue;
            this.value = rawValue ? `${Number(rawValue).toLocaleString("vi-VN")} VND` : "";
        });

        document.getElementById("gia_hien_thi").addEventListener("input", function() {
            const rawValue = this.value.replace(/[^\d]/g, "");
            document.getElementById("gia").value = rawValue;
            this.value = rawValue ? `${Number(rawValue).toLocaleString("vi-VN")} VND` : "";
        });

        let currentPage = 1;
        const rowsPerPage = 5;

        function getAllRows() {
            return Array.from(document.querySelectorAll("#tableBody tr"));
        }

        function getFilteredRows() {
            const selectedDanhMuc = document.getElementById("filterDanhMuc").value;
            const selectedTrangThai = document.getElementById("filterTrangThai").value;
            const allRows = getAllRows();

            return allRows.filter(row => {
                const danhMucCell = row.children[2]?.textContent.trim();
                const trangThaiCell = row.children[7]?.textContent.trim();

                const matchDanhMuc = !selectedDanhMuc || danhMucCell === getTenDanhMucByValue(selectedDanhMuc);
                const matchTrangThai = selectedTrangThai === "" ||
                    (selectedTrangThai === "1" && trangThaiCell === "Hiện") ||
                    (selectedTrangThai === "0" && trangThaiCell === "Không hiện");

                return matchDanhMuc && matchTrangThai;
            });
        }

        function renderTablePage() {
            const allRows = getAllRows();
            const filteredRows = getFilteredRows();
            const totalPages = Math.ceil(filteredRows.length / rowsPerPage);

            allRows.forEach(row => row.style.display = "none");

            const start = (currentPage - 1) * rowsPerPage;
            const end = start + rowsPerPage;
            filteredRows.slice(start, end).forEach(row => row.style.display = "");

            renderPagination(totalPages);
        }

        function renderPagination(totalPages) {
            const pagination = document.querySelector(".pagination");
            pagination.innerHTML = "";

            const addPageButton = (page, text = page) => {
                const li = document.createElement("li");
                li.className = page === currentPage ? "active" : "";
                li.innerHTML = `<a href="#" onclick="changePage(${page}); return false;">${text}</a>`;
                pagination.appendChild(li);
            };

            const addDisabled = (text) => {
                const li = document.createElement("li");
                li.className = "disabled";
                li.innerHTML = `<span>${text}</span>`;
                pagination.appendChild(li);
            };

            if (currentPage > 1) {
                addPageButton(currentPage - 1, "Previous");
            } else {
                addDisabled("Previous");
            }

            if (totalPages <= 5) {
                for (let i = 1; i <= totalPages; i++) {
                    addPageButton(i);
                }
            } else {
                addPageButton(1);
                if (currentPage > 3) addDisabled("...");
                for (let i = currentPage - 1; i <= currentPage + 1; i++) {
                    if (i > 1 && i < totalPages) addPageButton(i);
                }
                if (currentPage < totalPages - 2) addDisabled("...");
                addPageButton(totalPages);
            }

            if (currentPage < totalPages) {
                addPageButton(currentPage + 1, "Next");
            } else {
                addDisabled("Next");
            }
        }

        function changePage(page) {
            const filteredRows = getFilteredRows();
            const totalPages = Math.ceil(filteredRows.length / rowsPerPage);
            if (page < 1 || page > totalPages) return;
            currentPage = page;
            renderTablePage();
        }

        function locSanPham() {
            currentPage = 1;
            renderTablePage();
        }

        function getTenDanhMucByValue(maDanhMuc) {
            const select = document.getElementById("filterDanhMuc");
            const option = Array.from(select.options).find(opt => opt.value === maDanhMuc);
            return option ? option.textContent.trim() : "";
        }

        function showToast(message, isError = false) {
            const toast = document.createElement("div");
            toast.textContent = message;
            toast.style.position = "fixed";
            toast.style.top = "20px";
            toast.style.right = "20px";
            toast.style.padding = "12px 20px";
            toast.style.backgroundColor = isError ? "#dc3545" : "#28a745";
            toast.style.color = "#fff";
            toast.style.borderRadius = "6px";
            toast.style.fontWeight = "bold";
            toast.style.boxShadow = "0 0 8px rgba(0,0,0,0.2)";
            toast.style.zIndex = 9999;
            toast.style.opacity = "0";
            toast.style.transition = "opacity 0.3s ease";
            document.body.appendChild(toast);

            setTimeout(() => toast.style.opacity = "1", 100);
            setTimeout(() => {
                toast.style.opacity = "0";
                setTimeout(() => document.body.removeChild(toast), 500);
            }, 3000);
        }

        window.addEventListener("DOMContentLoaded", () => {
            const successMsg = sessionStorage.getItem("toastSuccess");
            const errorMsg = sessionStorage.getItem("toastError");

            if (successMsg) {
                showToast(successMsg);
                sessionStorage.removeItem("toastSuccess");
            }

            if (errorMsg) {
                showToast(errorMsg, true);
                sessionStorage.removeItem("toastError");
            }

            renderTablePage();
        });

        function xemChiTietSanPham(maSanPham, soSao) {
            fetch('sanpham/lay_chi_tiet.php?ma_san_pham=' + maSanPham)
                .then(res => res.json())
                .then(data => {
                    if (!data.success) {
                        alert(data.message || "Không thể tải chi tiết sản phẩm.");
                        return;
                    }

                    const sp = data.san_pham;
                    const anh = data.anh_bien_the;

                    // ✅ Dùng số sao đã truyền từ PHP
                    document.getElementById("thongTinSanPham").innerHTML = `
                <p><strong>Tên:</strong> ${sp.ten_san_pham}</p>
                <p><strong>Giá:</strong> ${Number(sp.gia).toLocaleString()} đ</p>
                <p><strong>Mô tả:</strong> ${sp.mo_ta}</p>
                <p><strong>Số sao trung bình:</strong> ${Number(soSao).toFixed(1)} ★</p>
                <p><strong>Ảnh đại diện:</strong></p>
                <img src="https://cuddly-exotic-snake.ngrok-free.app${sp.anh_san_pham}" 
                    style="max-width: 150px; max-height: 120px; border-radius: 6px; border:1px solid #ccc;" />
            `;

                    // Gọi màu sắc và ảnh biến thể
                    fetch('sanpham/get_all_mau_sac.php')
                        .then(res => res.json())
                        .then(mauData => {
                            const mauMap = {};
                            mauData.forEach(m => mauMap[m.ma_mau] = m.ten_mau);

                            const grouped = {};
                            anh.forEach(a => {
                                if (!grouped[a.ma_mau]) grouped[a.ma_mau] = [];
                                grouped[a.ma_mau].push(a);
                            });

                            let html = '<p><strong>Biến thể sản phẩm:</strong></p>';
                            for (const maMau in grouped) {
                                const tenMau = mauMap[maMau] || `Màu ${maMau}`;
                                html += `<div style="margin-bottom: 20px;">
                            <div style="font-weight:bold; margin-bottom:5px;">${tenMau}</div>
                            <div style="display: flex; flex-wrap: wrap; gap: 10px;">`;

                                grouped[maMau].forEach(anhItem => {
                                    html += `<img src="https://cuddly-exotic-snake.ngrok-free.app${anhItem.duong_dan}" 
                                style="height: 70px; margin: 4px; border-radius: 6px; border: 1px solid #ccc;">`;
                                });

                                html += `</div></div>`;
                            }

                            document.getElementById("anhBienTheSanPham").innerHTML = html;
                            document.getElementById("popupChiTiet").style.display = "flex";
                        });
                })
                .catch(err => {
                    alert("Lỗi khi tải chi tiết sản phẩm");
                    console.error(err);
                });
        }



        function confirmXoaSanPham(id) {
            Swal.fire({
                title: 'Bạn có chắc chắn?',
                text: 'Sản phẩm sẽ bị xoá vĩnh viễn!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Xoá',
                cancelButtonText: 'Hủy'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '/admin/sanpham/xoa.php?id=' + id;
                }
            });
        }
    </script>
    <?php
    if (isset($_SESSION['toastSuccess'])) {
        $successMsg = $_SESSION['toastSuccess'];
        echo "<script>sessionStorage.setItem('toastSuccess', " . json_encode($successMsg) . ");</script>";
        unset($_SESSION['toastSuccess']);
    }

    if (isset($_SESSION['toastError'])) {
        $errorMsg = $_SESSION['toastError'];
        echo "<script>sessionStorage.setItem('toastError', " . json_encode($errorMsg) . ");</script>";
        unset($_SESSION['toastError']);
    }
    ?>
</body>

</html>