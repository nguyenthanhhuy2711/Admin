<?php
include __DIR__ . '/../includes/check_login.php';
include __DIR__ . '/../includes/connect.php';

$thongKeDanhGia = callAPI('get/thongKeDanhGia');
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Quản lý đánh giá</title>
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
            margin-left: 110px;
            padding: 20px;
        }

        h2 {
            margin-top: 0;
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
            text-align: center;
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

        tbody tr:hover {
            background-color: #f0f8ff;
            transition: background-color 0.2s ease;
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
            font-size: 14px;
            font-weight: 600;
        }

        .btn-view {
            background-color: #17a2b8;
        }

        .btn-view:hover {
            background-color: #138496;
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
        }

        .form-container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            width: 700px;
            margin: 50px auto;
            max-height: 90vh;
            overflow-y: auto;
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

        .form-container input {
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
    </style>

</head>

<body>

    <div class="main-content">
        <h2><i class="fas fa-star"></i> Thống kê đánh giá sản phẩm</h2>
        <table>
            <thead>
                <tr>
                    <th style="width: 150px;">STT</th> <!-- Nhỏ nhất -->
                    <th style="width: 550px;">Tên sản phẩm</th> <!-- Dài nhất -->
                    <th style="width: 200px;">Số lượt đánh giá</th>
                    <th style="width: 200px;">Sao trung bình</th>
                    <th style="width: 150px;">Thao tác</th>
                </tr>

            </thead>
            <tbody id="tableBody">
                <?php if (!empty($thongKeDanhGia)): ?>
                    <?php $i = 1;
                    foreach ($thongKeDanhGia as $sp): ?>
                        <tr>
                            <td class="center"><?= $i++ ?></td>
                            <td><?= htmlspecialchars($sp['ten_san_pham']) ?></td>
                            <td class="center"><?= $sp['so_luong_danh_gia'] ?></td>
                            <td class="center"><?= number_format($sp['diem_trung_binh'], 1) ?> ★</td>
                            <td class="center">
                                <a href="#" class="btn-icon" onclick="openChiTietPopup(<?= $sp['ma_san_pham'] ?>); return false;">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">Không có dữ liệu.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <div style="margin-top: 20px; display: flex; justify-content: space-between;" id="paginationWrapper">
            <ul class="pagination" style="display: flex; list-style: none; padding: 0; gap: 4px;"></ul>
        </div>
    </div>

    <!-- POPUP -->
    <div id="popupChiTiet" class="popup-form">
        <div class="form-container">
            <h3 id="popupTenSanPham">Chi tiết đánh giá</h3>
            <p><strong>Tổng đánh giá:</strong> <span id="popupTongDanhGia"></span></p>
            <p><strong>Điểm trung bình:</strong> <span id="popupDiemTrungBinh"></span></p>
            <hr>
            <div id="popupDanhSach"></div>
            <div style="text-align: right; margin-top: 20px;">
                <button onclick="closeChiTietPopup()">Đóng</button>
            </div>
        </div>
    </div>

    <script>
        function openChiTietPopup(maSanPham) {
            fetch("danhgia/lay_chi_tiet.php?ma_san_pham=" + maSanPham)
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById("popupChiTiet").style.display = "block";
                        document.getElementById("popupTenSanPham").textContent = "Chi tiết đánh giá - " + data.thong_tin.ten_san_pham;
                        document.getElementById("popupTongDanhGia").textContent = data.thong_tin.tong_danh_gia;
                        document.getElementById("popupDiemTrungBinh").textContent = data.thong_tin.diem_trung_binh + " ★";

                        let html = "";
                        data.danh_sach_danh_gia.forEach(dg => {
                            html += `
                        <div style="margin-bottom: 10px; border-bottom: 1px solid #ccc; padding-bottom: 8px;">
                            <strong>${dg.ten_nguoi_dung}</strong> - ${dg.so_sao} ★<br>
                            <span>${dg.binh_luan || "(Không có bình luận)"}</span><br>
                            <small style="color: gray">${new Date(dg.ngay_danh_gia).toLocaleString()}</small>
                        </div>
                    `;
                        });

                        document.getElementById("popupDanhSach").innerHTML = html;
                    } else {
                        alert(data.message || "Không có dữ liệu");
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert("Lỗi gọi API");
                });
        }

        function closeChiTietPopup() {
            document.getElementById("popupChiTiet").style.display = "none";
        }
        let currentPage = 1;
        const rowsPerPage = 8;
        const table = document.getElementById("tableBody");
        const rows = Array.from(table.querySelectorAll("tr"));
        const totalPages = Math.ceil(rows.length / rowsPerPage);

        function renderTablePage() {
            rows.forEach((row, index) => {
                row.style.display = (index >= (currentPage - 1) * rowsPerPage && index < currentPage * rowsPerPage) ? "" : "none";
            });
            renderPagination();
        }

        function renderPagination() {
            const pagination = document.querySelector(".pagination");
            pagination.innerHTML = "";

            // Previous
            const prevLi = document.createElement("li");
            prevLi.className = currentPage === 1 ? "disabled" : "";
            prevLi.innerHTML = `<a href="#" onclick="changePage(${currentPage - 1})">Previous</a>`;
            pagination.appendChild(prevLi);

            // Số trang
            for (let i = 1; i <= totalPages; i++) {
                const li = document.createElement("li");
                li.className = i === currentPage ? "active" : "";
                li.innerHTML = `<a href="#" onclick="changePage(${i})">${i}</a>`;
                pagination.appendChild(li);
            }

            // Next
            const nextLi = document.createElement("li");
            nextLi.className = currentPage === totalPages ? "disabled" : "";
            nextLi.innerHTML = `<a href="#" onclick="changePage(${currentPage + 1})">Next</a>`;
            pagination.appendChild(nextLi);
        }

        function changePage(page) {
            if (page < 1 || page > totalPages) return;
            currentPage = page;
            renderTablePage();
        }
        renderTablePage();
    </script>

</body>

</html>