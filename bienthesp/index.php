<?php
include __DIR__ . '/../includes/check_login.php';
include __DIR__ . '/../includes/connect.php';

$data = callAPI("getAllBienTheSanPham") ?? [];
$dsSanPham = callAPI("getallSanPham") ?? [];
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Quản lý biến thể SP</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
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
            border: 1px solid #ccc;
            padding: 12px 10px;
            text-align: center;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        td {
            color: #333;
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
    </style>
</head>

<body>
    <div class="main-content">
        <h2><i class="fas fa-warehouse"></i> Quản lý kho sản phẩm</h2>
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <label for="selectFilter">Lọc theo sản phẩm:</label>
                <select id="selectFilter" onchange="filterByProduct()" style="padding: 8px; border-radius: 6px; border: 1px solid #ccc; width: 250px; margin-bottom: 10px;">
                    <option value="">Tất cả sản phẩm</option>
                    <?php foreach ($dsSanPham as $sp): ?>
                        <option value="<?= htmlspecialchars($sp['ten_san_pham']) ?>"><?= htmlspecialchars($sp['ten_san_pham']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div style="text-align: right; margin-bottom: 10px;">
                <button type="button" class="btn-export" onclick="downloadExcel()">📤 Xuất Excel</button>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th style="width: 150px;">STT</th>
                    <th style="width: 300px;">Sản phẩm</th>
                    <th style="width: 200px;">Kích thước</th>
                    <th style="width: 200px;">Màu sắc</th>
                    <th style="width: 295.5px;">Số lượng tồn</th>
                </tr>
            </thead>
            <tbody id="tableBody"></tbody>
        </table>

        <div style="margin-top: 20px; display: flex; justify-content: space-between;" id="paginationWrapper">
            <ul class="pagination" style="display: flex; list-style: none; padding: 0; gap: 4px;"></ul>
        </div>
    </div>

    <script>
        const data = <?= json_encode($data) ?>;
        let currentPage = 1;
        const rowsPerPage = 10;
        let filteredData = [];

        const tableBody = document.getElementById("tableBody");
        const pagination = document.querySelector(".pagination");

        function renderTablePage() {
            const totalPages = Math.ceil(filteredData.length / rowsPerPage);
            const start = (currentPage - 1) * rowsPerPage;
            const end = start + rowsPerPage;

            tableBody.innerHTML = "";

            filteredData.slice(start, end).forEach((item, index) => {
                const row = document.createElement("tr");
                row.innerHTML = `
                    <td>${start + index + 1}</td>
                    <td>${item.ten_san_pham}</td>
                    <td>${item.kich_thuoc}</td>
                    <td>${item.ten_mau}</td>
                    <td style="color: ${item.so_luong_ton < 5 ? 'red' : 'inherit'}">${item.so_luong_ton}</td>
                `;
                tableBody.appendChild(row);
            });

            renderPagination(totalPages);
        }

        function renderPagination(totalPages) {
            pagination.innerHTML = "";
            if (totalPages <= 1) return;

            const prevLi = document.createElement("li");
            prevLi.className = currentPage === 1 ? "disabled" : "";
            prevLi.innerHTML = `<a href="#" onclick="event.preventDefault(); changePage(${currentPage - 1})">Previous</a>`;
            pagination.appendChild(prevLi);

            for (let i = 1; i <= totalPages; i++) {
                const li = document.createElement("li");
                li.className = i === currentPage ? "active" : "";
                li.innerHTML = `<a href="#" onclick="event.preventDefault(); changePage(${i})">${i}</a>`;
                pagination.appendChild(li);
            }

            const nextLi = document.createElement("li");
            nextLi.className = currentPage === totalPages ? "disabled" : "";
            nextLi.innerHTML = `<a href="#" onclick="event.preventDefault(); changePage(${currentPage + 1})">Next</a>`;
            pagination.appendChild(nextLi);
        }

        function changePage(page) {
            const totalPages = Math.ceil(filteredData.length / rowsPerPage);
            if (page < 1 || page > totalPages) return;
            currentPage = page;
            renderTablePage();
        }

        function filterByProduct() {
            const selectedName = document.getElementById("selectFilter").value.toLowerCase();
            filteredData = selectedName ?
                data.filter(item => item.ten_san_pham.toLowerCase() === selectedName) : [...data];

            currentPage = 1;
            renderTablePage();
        }

        window.addEventListener("DOMContentLoaded", () => {
            filteredData = [...data];
            renderTablePage();
        });

        function downloadExcel() {
            fetch('bienthesp/xuat_excel.php')
                .then(response => response.blob())
                .then(blob => {
                    const url = window.URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = 'kho_sanpham.xlsx'; // đổi tên file .xlsx
                    document.body.appendChild(a);
                    a.click();
                    a.remove();
                    window.URL.revokeObjectURL(url);
                })
                .catch(err => alert("Lỗi khi tải file!"));
        }
    </script>
</body>

</html>