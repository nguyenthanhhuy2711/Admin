<?php
include __DIR__ . '/../includes/check_login.php';
include __DIR__ . '/../includes/connect.php';

callAPI("autoUpdateTrangThaiVoucher", "PUT");

$dsVoucher = callAPI("getAllVoucher") ?? [];
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Quản lý voucher</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
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

        .add-btn {
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

        th,
        td {
            padding: 10px;
            border: 1px solid #ccc;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        td.center {
            text-align: center;
        }

        img {
            max-height: 60px;
            border-radius: 6px;
            border: 1px solid #ccc;
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

        .pagination li a {
            display: block;
            padding: 6px 12px;
            color: #007bff;
            text-decoration: none;
            border: 1px solid #ddd;
            border-radius: 4px;
            background-color: white;
        }

        .pagination li.active a {
            background-color: #007bff;
            color: white;
            border-color: #007bff;
        }

        .pagination li.disabled a {
            pointer-events: none;
            color: #aaa;
            border-color: #ccc;
            background-color: #f0f0f0;
        }

        .popup-form {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.4);
            z-index: 999;
        }

        .form-container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            width: 500px;
            margin: 50px auto;
            position: relative;
            border: 2px solid #007bff;
        }

        .form-container h3 {
            margin-top: 0;
            margin-bottom: 20px;
            color: #333;
        }

        .form-container label {
            font-weight: 600;
            display: block;
            margin-bottom: 6px;
        }

        .form-container input,
        .form-container select {
            width: 100%;
            padding: 10px;
            margin-bottom: 16px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        .form-container button {
            padding: 10px 16px;
            margin-right: 8px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
        }

        .form-container button[type="submit"] {
            background-color: #28a745;
            color: white;
        }

        .form-container button[type="button"] {
            background-color: #dc3545;
            color: white;
        }

        .grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .input-with-unit {
            position: relative;
            display: inline-block;
            width: 200px;
        }

        .input-with-unit input {
            width: 100%;
            padding-right: 40px;
            height: 36px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-sizing: border-box;
        }

        .input-with-unit .unit {
            position: absolute;
            top: 0;
            bottom: 0;
            right: 10px;
            display: flex;
            align-items: center;
            font-size: 14px;
            color: #777;
            pointer-events: none;
        }

        .popup-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.4);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 999;
        }

        .popup-content {
            background: white;
            padding: 24px;
            border-radius: 10px;
            width: 80%;
            max-height: 90%;
            overflow-y: auto;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.3);
        }

        .voucher-info {
            display: flex;
            justify-content: space-between;
            gap: 40px;
            margin-bottom: 20px;
        }

        .info-left {
            flex: 1;
            font-size: 15px;
        }

        .info-right img {
            max-height: 160px;
        }

        .styled-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }

        .styled-table th,
        .styled-table td {
            border: 1px solid #ccc;
            padding: 8px 12px;
            text-align: center;
        }

        .styled-table thead {
            background-color: #f0f0f0;
        }

        .styled-table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .btn-close-popup {
            background-color: #e74c3c;
            /* Đỏ tươi */
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.2s ease;
        }

        .btn-close-popup:hover {
            background-color: #c0392b;
            /* Đỏ đậm hơn khi hover */
        }
    </style>
</head>

<body>
    <div class="main-content">
        <h2><i class="fas fa-ticket-alt"></i> Danh sách Voucher</h2>
        <!-- Nút mở popup -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <div>
                <label for="filterStatus">Lọc theo trạng thái:</label>
                <select id="filterStatus" onchange="filterByStatus()" style="padding: 6px 12px; border-radius: 6px; border: 1px solid #ccc;">
                    <option value="">Tất cả</option>
                    <option value="hoat_dong">Hoạt động</option>
                    <option value="tam_ngung">Tạm ngưng</option>
                </select>
            </div>
            <a href="#" class="add-btn" onclick="openFormPopup(); return false;">Thêm voucher</a>
        </div>

        <!-- POPUP SỬA VOUCHER -->
        <div id="editPopup" class="popup-form" style="display: none;">
            <div class="form-container">
                <h3>Sửa thời gian voucher</h3>
                <form action="voucher/sua.php" method="post" onsubmit="return validateNgayVoucher()">
                    <input type="hidden" name="id" id="edit_id">

                    <label>Ngày bắt đầu:</label>
                    <input type="datetime-local" name="ngay_bat_dau" id="edit_ngay_bat_dau" required>

                    <label>Ngày kết thúc:</label>
                    <input type="datetime-local" name="ngay_ket_thuc" id="edit_ngay_ket_thuc" required>

                    <label>Số lượng:</label>
                    <input type="number" name="so_luong" id="edit_so_luong" min="1" required>

                    <div style="text-align:right; margin-top: 10px">
                        <button type="submit">Cập nhật</button>
                        <button type="button" onclick="closeEditPopup()">Hủy</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- POPUP CHI TIẾT VOUCHER -->
        <div id="voucherDetailPopup" class="popup-overlay" style="display:none;">
            <div class="popup-content">
                <h2>Chi tiết Voucher</h2>
                <div class="voucher-info">
                    <div class="info-left">
                        <p><strong>Mã voucher:</strong> <span id="detail_ma_voucher"></span></p>
                        <p><strong>Mô tả hiển thị:</strong> <span id="detail_mo_ta_hien_thi"></span></p>
                        <p><strong>Loại:</strong> <span id="detail_loai"></span></p>
                        <p><strong>Kiểu giảm:</strong> <span id="detail_kieu_giam"></span></p>
                        <p><strong>Giá trị:</strong> <span id="detail_gia_tri"></span></p>
                        <p><strong>Điều kiện áp dụng:</strong> <span id="detail_dieu_kien"></span></p>
                        <p><strong>Số lượng:</strong> <span id="detail_so_luong"></span></p>
                        <p><strong>Ngày bắt đầu:</strong> <span id="detail_ngay_bat_dau"></span></p>
                        <p><strong>Ngày kết thúc:</strong> <span id="detail_ngay_ket_thuc"></span></p>
                        <p><strong>Trạng thái:</strong> <span id="detail_trang_thai"></span></p>
                    </div>
                </div>

                <hr>

                <h3>Người dùng đã nhận voucher</h3>
                <table id="ds_nguoi_dung_voucher" class="styled-table">
                    <thead>
                        <tr>
                            <th>STT</th>
                            <th>Tên</th>
                            <th>Đã sử dụng</th>
                            <th>Ngày sử dụng</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Dữ liệu đổ bằng JS -->
                    </tbody>
                </table>
                <div style="text-align: right; margin-top: 20px;">
                    <button class="btn-close-popup" onclick="document.getElementById('voucherDetailPopup').style.display='none'">Đóng</button>
                </div>

            </div>
        </div>

        <!-- Popup form -->
        <div id="popupForm" class="popup-form">
            <div class="form-container">
                <h3 id="formTitle">Thêm voucher</h3>
                <form id="voucherForm" action="voucher/them.php" method="post" enctype="multipart/form-data">
                    <div class="grid-2">
                        <div>
                            <label>Mã voucher:</label>
                            <input type="text" name="ma_voucher" required
                                oninvalid="this.setCustomValidity('Vui lòng nhập mã voucher')"
                                oninput="this.setCustomValidity('')">

                            <label>Mô tả hiển thị:</label>
                            <input type="text" name="mo_ta_hien_thi" required
                                oninvalid="this.setCustomValidity('Vui lòng nhập mô tả hiển thị')"
                                oninput="this.setCustomValidity('')">

                            <label>Loại:</label>
                            <select name="loai" required
                                oninvalid="this.setCustomValidity('Vui lòng chọn loại voucher')"
                                oninput="this.setCustomValidity('')">
                                <option value="">-- Chọn loại --</option>
                                <option value="order">Giảm đơn</option>
                                <option value="ship">Giảm ship</option>
                            </select>

                            <label>Kiểu giảm:</label>
                            <select name="kieu_giam" id="kieu_giam" required
                                oninvalid="this.setCustomValidity('Vui lòng chọn kiểu giảm')"
                                oninput="this.setCustomValidity(''); updateDonViGiam();">
                                <option value="">-- Chọn kiểu giảm --</option>
                                <option value="phan_tram">Phần trăm</option>
                                <option value="tien_mat">Tiền mặt</option>
                            </select>

                            <label>Giá trị giảm:</label>
                            <div class="input-with-unit">
                                <input type="number" step="1" name="gia_tri" id="gia_tri" required
                                    oninvalid="this.setCustomValidity('Vui lòng nhập giá trị giảm')"
                                    oninput="this.setCustomValidity('')">
                                <span class="unit" id="don_vi_giam"></span>
                            </div>

                            <label>Điều kiện áp dụng:</label>
                            <input type="number" step="0.01" name="dieu_kien_ap_dung">
                        </div>

                        <div>
                            <label>Số lượng:</label>
                            <input type="number" name="so_luong" value="1" required
                                oninvalid="this.setCustomValidity('Vui lòng nhập số lượng')"
                                oninput="this.setCustomValidity('')">

                            <label>Ngày bắt đầu:</label>
                            <input type="datetime-local" name="ngay_bat_dau"
                                oninvalid="this.setCustomValidity('Vui lòng chọn ngày bắt đầu')"
                                oninput="this.setCustomValidity('')">

                            <label>Ngày kết thúc:</label>
                            <input type="datetime-local" name="ngay_ket_thuc"
                                oninvalid="this.setCustomValidity('Vui lòng chọn ngày kết thúc')"
                                oninput="this.setCustomValidity('')">

                            <label>Hình ảnh (upload):</label>
                            <input type="file" name="hinh_anh"
                                oninvalid="this.setCustomValidity('Vui lòng chọn hình ảnh')"
                                oninput="this.setCustomValidity('')">

                            <label>Hiển thị tự động:</label>
                            <select name="hien_thi_auto"
                                oninvalid="this.setCustomValidity('Vui lòng chọn hiển thị tự động')"
                                oninput="this.setCustomValidity('')">
                                <option value="0">Không</option>
                                <option value="1">Có</option>
                            </select>
                        </div>
                    </div>

                    <div style="text-align:right; margin-top: 10px">

                        <button type="button" onclick="closeFormPopup()">Hủy</button>
                        <button type="submit">Thêm</button>
                    </div>
                </form>
            </div>
        </div>
        <table>
            <thead>
                <tr>
                    <th style="width: 50px">STT</th>
                    <th style="width: 100px">Mã voucher</th>
                    <th style="width: 100px">Mô tả</th>
                    <th style="width: 100px">Loại</th>
                    <th style="width: 100px">Kiểu giảm</th>
                    <th style="width: 80px">Giá trị</th>
                    <th style="width: 50px">Điều kiện</th>
                    <th style="width: 50px">Số lượng</th>
                    <th style="width: 140px">Ngày bắt đầu</th>
                    <th style="width: 140px">Ngày kết thúc</th>
                    <th style="width: 100px">Ảnh</th>
                    <th style="width: 108px">Trạng thái</th>
                    <th style="width: 110px">Thao tác</th>
                </tr>
            </thead>

            <tbody id="tableBody">
                <?php if (is_array($dsVoucher) && !empty($dsVoucher)): ?>
                    <?php $i = 1;
                    foreach ($dsVoucher as $v): ?>
                        <tr>
                            <td class="center"><?= $i++ ?></td>
                            <td class="center"><?= htmlspecialchars($v['ma_voucher']) ?></td>
                            <td><?= htmlspecialchars($v['mo_ta_hien_thi']) ?></td>
                            <td class="center">
                                <?php
                                echo $v['loai'] === 'order' ? 'Giảm đơn' : ($v['loai'] === 'ship' ? 'Giảm ship' : '---');
                                ?>
                            </td>
                            <td class="center">
                                <?php
                                echo $v['kieu_giam'] === 'phan_tram' ? 'Phần trăm' : ($v['kieu_giam'] === 'tien_mat' ? 'Tiền mặt' : '---');
                                ?>
                            </td>

                            <td class="center"><?= number_format($v['gia_tri'], 0, ',', '.') ?></td>
                            <td class="center"><?= number_format($v['dieu_kien_ap_dung'], 0, ',', '.') ?></td>
                            <td class="center"><?= $v['so_luong'] ?></td>
                            <td class="center"><?= $v['ngay_bat_dau'] ?? '-' ?></td>
                            <td class="center"><?= $v['ngay_ket_thuc'] ?? '-' ?></td>
                            <td class="center">
                                <?php if (!empty($v['hinh_anh'])): ?>
                                    <img src="https://cuddly-exotic-snake.ngrok-free.app<?= htmlspecialchars($v['hinh_anh']) ?>" alt="Ảnh voucher">
                                <?php else: ?>
                                    Không có
                                <?php endif; ?>
                            </td>
                            <td class="center" data-trang-thai="<?= $v['trang_thai'] ?>">
                                <?= $v['trang_thai'] === 'tam_ngung' ? 'Tạm ngưng' : ($v['trang_thai'] === 'hoat_dong' ? 'Hoạt động' : $v['trang_thai']) ?>
                            </td>

                            <td class="center">
                                <a href="#" class="btn-icon btn-detail" title="Chi tiết"
                                    onclick="xemChiTietVoucher(<?= htmlspecialchars($v['id']) ?>); return false;">
                                    <i class="fas fa-eye"></i>
                                </a>

                                <a href="#" class="btn-icon btn-edit" title="Sửa"
                                    onclick="openEditPopup(<?= $v['id'] ?>, '<?= $v['ngay_bat_dau'] ?>', '<?= $v['ngay_ket_thuc'] ?>'); return false;">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="13">Không có voucher nào.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <div style="margin-top: 20px; display: flex; justify-content: space-between;" id="paginationWrapper">
            <ul class="pagination" style="display: flex; list-style: none; padding: 0; gap: 4px;"></ul>
        </div>
    </div>
    <script>
        function openFormPopup() {
            document.getElementById('popupForm').style.display = 'block';
            document.getElementById('voucherForm').reset();
        }

        function closeFormPopup() {
            document.getElementById('popupForm').style.display = 'none';
        }

        function openEditPopup(id, start, end, soLuong) {
            document.getElementById('edit_id').value = id;

            const startInput = document.getElementById('edit_ngay_bat_dau');
            const endInput = document.getElementById('edit_ngay_ket_thuc');
            const soLuongInput = document.getElementById('edit_so_luong');

            startInput.value = start ? start.replace(' ', 'T') : '';
            endInput.value = end ? end.replace(' ', 'T') : '';
            soLuongInput.value = soLuong || '';

            document.getElementById('editPopup').style.display = 'block';
        }



        function closeEditPopup() {
            document.getElementById('editPopup').style.display = 'none';
        }

        let currentPage = 1;
        const rowsPerPage = 6;

        function getFilteredRows() {
            const status = document.getElementById("filterStatus").value;
            const allRows = Array.from(document.querySelectorAll("#tableBody tr"));
            return allRows.filter(row => {
                const statusAttr = row.children[11]?.getAttribute("data-trang-thai");
                return !status || statusAttr === status;

            });
        }

        function renderTablePage() {
            const filteredRows = getFilteredRows();
            const totalPages = Math.ceil(filteredRows.length / rowsPerPage);

            // Ẩn tất cả dòng trước
            document.querySelectorAll("#tableBody tr").forEach(row => row.style.display = "none");

            // Hiện dòng phù hợp theo trang
            filteredRows.forEach((row, index) => {
                if (index >= (currentPage - 1) * rowsPerPage && index < currentPage * rowsPerPage) {
                    row.style.display = "";
                }
            });

            renderPagination(totalPages);
        }

        function renderPagination(totalPages) {
            const pagination = document.querySelector(".pagination");
            pagination.innerHTML = "";

            const prevLi = document.createElement("li");
            prevLi.className = currentPage === 1 ? "disabled" : "";
            prevLi.innerHTML = `<a href="#" onclick="changePage(${currentPage - 1})">Previous</a>`;
            pagination.appendChild(prevLi);

            for (let i = 1; i <= totalPages; i++) {
                const li = document.createElement("li");
                li.className = i === currentPage ? "active" : "";
                li.innerHTML = `<a href="#" onclick="changePage(${i})">${i}</a>`;
                pagination.appendChild(li);
            }

            const nextLi = document.createElement("li");
            nextLi.className = currentPage === totalPages ? "disabled" : "";
            nextLi.innerHTML = `<a href="#" onclick="changePage(${currentPage + 1})">Next</a>`;
            pagination.appendChild(nextLi);
        }

        function changePage(page) {
            const filteredRows = getFilteredRows();
            const totalPages = Math.ceil(filteredRows.length / rowsPerPage);
            if (page < 1 || page > totalPages) return;
            currentPage = page;
            renderTablePage();
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
                showToast(successMsg, false);
                sessionStorage.removeItem("toastSuccess");
            }

            if (errorMsg) {
                showToast(errorMsg, true);
                sessionStorage.removeItem("toastError");
            }

            renderTablePage(); // render lần đầu
        });

        function filterByStatus() {
            currentPage = 1;
            renderTablePage();
        }

        function updateDonViGiam() {
            const kieuGiam = document.getElementById('kieu_giam').value;
            const donVi = document.getElementById('don_vi_giam');

            if (kieuGiam === 'phan_tram') {
                donVi.textContent = '%';
            } else if (kieuGiam === 'tien_mat') {
                donVi.textContent = 'VND';
            } else {
                donVi.textContent = '';
            }
        }

        window.addEventListener('DOMContentLoaded', updateDonViGiam);

        function validateNgayVoucher() {
            const batDau = document.getElementById("edit_ngay_bat_dau").value;
            const ketThuc = document.getElementById("edit_ngay_ket_thuc").value;

            if (!batDau || !ketThuc) return true;

            const start = new Date(batDau);
            const end = new Date(ketThuc);

            if (end <= start) {
                showToast("Ngày kết thúc phải sau hơn ngày bắt đầu!", true);
                return false;
            }

            return true;
        }
        document.getElementById('voucherForm').addEventListener('submit', function(e) {
            let valid = true;
            const form = this;

            // ===== 1. Ngày bắt đầu =====
            const ngayBatDau = form.querySelector('[name="ngay_bat_dau"]');
            if (!ngayBatDau.value) {
                ngayBatDau.setCustomValidity('Vui lòng chọn ngày bắt đầu');
                ngayBatDau.reportValidity();
                valid = false;
            } else {
                ngayBatDau.setCustomValidity('');
            }

            // ===== 2. Ngày kết thúc =====
            const ngayKetThuc = form.querySelector('[name="ngay_ket_thuc"]');
            if (!ngayKetThuc.value) {
                if (valid) {
                    ngayKetThuc.setCustomValidity('Vui lòng chọn ngày kết thúc');
                    ngayKetThuc.reportValidity();
                }
                valid = false;
            } else {
                ngayKetThuc.setCustomValidity('');
            }

            // So sánh ngày bắt đầu và ngày kết thúc
            if (ngayBatDau.value && ngayKetThuc.value) {
                const start = new Date(ngayBatDau.value);
                const end = new Date(ngayKetThuc.value);
                if (end <= start) {
                    if (valid) {
                        ngayKetThuc.setCustomValidity('Ngày kết thúc phải sau ngày bắt đầu');
                        ngayKetThuc.reportValidity();
                    }
                    valid = false;
                } else {
                    ngayKetThuc.setCustomValidity('');
                }
            }

            // ===== 3. Hình ảnh =====
            const hinhAnh = form.querySelector('[name="hinh_anh"]');
            if (!hinhAnh.files || hinhAnh.files.length === 0) {
                if (valid) {
                    hinhAnh.setCustomValidity('Vui lòng chọn hình ảnh');
                    hinhAnh.reportValidity();
                }
                valid = false;
            } else {
                hinhAnh.setCustomValidity('');
            }

            // ===== 4. Hiển thị tự động =====
            const hienThi = form.querySelector('[name="hien_thi_auto"]');
            if (!hienThi.value) {
                hienThi.setCustomValidity('Vui lòng chọn hiển thị tự động');
                hienThi.reportValidity();
                valid = false;
            } else {
                hienThi.setCustomValidity('');
            }

            // ===== 5. Điều kiện áp dụng (nếu có thì phải >= 0) =====
            const dieuKien = form.querySelector('[name="dieu_kien_ap_dung"]');
            if (dieuKien.value && parseFloat(dieuKien.value) < 0) {
                dieuKien.setCustomValidity('Điều kiện áp dụng phải >= 0');
                dieuKien.reportValidity();
                valid = false;
            } else {
                dieuKien.setCustomValidity('');
            }

            // Ngăn submit nếu có lỗi
            if (!valid) e.preventDefault();
        });

        function xemChiTietVoucher(id) {
            fetch('voucher/chi_tiet_voucher.php?id=' + id)
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        const v = data.voucher;
                        const dsNguoiDung = data.ds_nguoi_dung ?? [];

                        // --- HIỂN THỊ THÔNG TIN VOUCHER ---
                        document.getElementById('detail_ma_voucher').innerText = v.ma_voucher;
                        document.getElementById('detail_mo_ta_hien_thi').innerText = v.mo_ta_hien_thi;
                        document.getElementById('detail_loai').innerText = v.loai === 'order' ? 'Giảm đơn' : 'Giảm ship';
                        document.getElementById('detail_kieu_giam').innerText = v.kieu_giam === 'phan_tram' ? 'Phần trăm' : 'Tiền mặt';
                        document.getElementById('detail_gia_tri').innerText = new Intl.NumberFormat('vi-VN').format(v.gia_tri);
                        document.getElementById('detail_dieu_kien').innerText = new Intl.NumberFormat('vi-VN').format(v.dieu_kien_ap_dung);
                        document.getElementById('detail_so_luong').innerText = v.so_luong;
                        document.getElementById('detail_ngay_bat_dau').innerText = v.ngay_bat_dau ?? '-';
                        document.getElementById('detail_ngay_ket_thuc').innerText = v.ngay_ket_thuc ?? '-';
                        document.getElementById('detail_trang_thai').innerText =
                            v.trang_thai === 'hoat_dong' ? 'Hoạt động' : (v.trang_thai === 'tam_ngung' ? 'Tạm ngưng' : 'Hết hạn');;
                        // --- DANH SÁCH NGƯỜI DÙNG ---
                        const tbody = document.querySelector('#ds_nguoi_dung_voucher tbody');
                        tbody.innerHTML = '';
                        if (dsNguoiDung.length === 0) {
                            tbody.innerHTML = '<tr><td colspan="5">Chưa có ai sử dụng</td></tr>';
                        } else {
                            dsNguoiDung.forEach((nguoi, index) => {
                                const tr = document.createElement('tr');
                                tr.innerHTML = `
                            <td>${index + 1}</td>
                            <td>${nguoi.ten_nguoi_dung}</td>
                            <td>${nguoi.da_su_dung ? '✓' : 'Chưa'}</td>
                            <td>${nguoi.ngay_su_dung ?? '-'}</td>
                        `;
                                tbody.appendChild(tr);
                            });
                        }

                        // --- HIỆN POPUP ---
                        document.getElementById('voucherDetailPopup').style.display = 'flex';
                    } else {
                        showToast(data.message || 'Không thể lấy dữ liệu voucher', 'error');
                    }
                })
                .catch(err => {
                    console.error(err);
                    showToast('Lỗi hệ thống khi lấy chi tiết voucher', 'error');
                });
        }

        function closeVoucherDetailPopup() {
            document.getElementById('voucherDetailPopup').style.display = 'none';
        }
    </script>

</body>

</html>