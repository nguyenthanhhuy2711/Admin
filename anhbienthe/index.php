<?php
include __DIR__ . '/../includes/check_login.php';
include __DIR__ . '/../includes/connect.php';
$baseUrl = "https://cuddly-exotic-snake.ngrok-free.app";
$dsAnh = callAPI("getallAnhBienThe");
$dsSanPham = callAPI("getallSanPham") ?? [];
$dsMauSac = callAPI("getAllMauSac") ?? [];
?>

<!DOCTYPE html>
<html lang="vi">
<script>
    const dsAnhRaw = <?= json_encode($dsAnh) ?>;
    const dsSanPham = <?= json_encode($dsSanPham) ?>;
    const dsMauSac = <?= json_encode($dsMauSac) ?>;
</script>

<head>
    <meta charset="UTF-8">
    <title>Quản lý ảnh biến thể</title>
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
        }

        .form-container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            width: 450px;
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

        .toast {
            position: fixed;
            top: 20px;
            right: 20px;
            background-color: #28a745;
            color: white;
            padding: 14px 20px;
            border-radius: 6px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
            z-index: 9999;
            opacity: 0;
            transition: opacity 0.4s ease, transform 0.4s ease;
            transform: translateY(-20px);
            font-weight: bold;
        }

        .toast.show {
            opacity: 1;
            transform: translateY(0);
        }

        .toast.hidden {
            display: none;
        }

        .toast.error {
            background-color: #dc3545;
        }

        .styled-select {
            width: 100%;
            padding: 8px 12px;
            margin-bottom: 12px;
            border: 1px solid #ccc;
            border-radius: 8px;
            background-color: #fff;
            font-size: 14px;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .styled-select:focus {
            outline: none;
            border-color: #4a90e2;
            box-shadow: 0 0 5px rgba(74, 144, 226, 0.5);
        }

        .styled-select option {
            padding: 8px;
        }
    </style>
</head>

<body>
    <div class="main-content">
        <h2><i class="fas fa-images"></i> Danh sách ảnh biến thể</h2>
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <label for="selectFilter">Lọc theo sản phẩm:</label>
                <select id="selectFilter" onchange="filterByProduct()" style="padding: 8px; border-radius: 6px; border: 1px solid #ccc; width: 250px;">
                    <option value="">Tất cả sản phẩm</option>
                    <?php foreach ($dsSanPham as $sp): ?>
                        <option value="<?= htmlspecialchars($sp['ten_san_pham']) ?>"><?= htmlspecialchars($sp['ten_san_pham']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <a href="#" class="add-btn" onclick="openFormPopup(); return false;">Thêm ảnh biến thể</a>
            </div>
        </div>

        <div id="popupForm" class="popup-form" style="display: none;">
            <div class="form-container">
                <h3>Thêm ảnh biến thể</h3>
                <form action="/admin/anhbienthe/them.php" method="post" enctype="multipart/form-data">
                    <label>Sản phẩm:</label>
                    <select name="ma_san_pham" class="styled-select" required>
                        <option value="">-- Chọn sản phẩm --</option>
                        <?php foreach ($dsSanPham as $sp): ?>
                            <option value="<?= $sp['ma_san_pham'] ?>"><?= htmlspecialchars($sp['ten_san_pham']) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <label>Màu sắc:</label>
                    <select name="ma_mau" class="styled-select" required>
                        <option value="">-- Chọn màu sắc --</option>
                        <?php foreach ($dsMauSac as $mau): ?>
                            <option value="<?= $mau['ma_mau'] ?>"><?= htmlspecialchars($mau['ten_mau']) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <label>Chọn ảnh:</label>
                    <input type="file" name="files[]" accept="image/*" multiple required>
                    <div style="text-align: right; margin-top: 16px;">
                        <button type="button" onclick="closeFormPopup()">Hủy</button>
                        <button type="submit">Thêm</button>
                    </div>
                </form>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th style="width: 100px;">STT</th>
                    <th style="width: 300px;">Tên sản phẩm</th>
                    <th style="width: 200px;">Màu sắc</th>
                    <th style="width: 500px;">Ảnh</th>
                    <th style="width: 150px;">Thao tác</th>
                </tr>
            </thead>
            <tbody id="tableBody"></tbody>
        </table>
        <div id="toast" class="toast hidden"></div>
        <div style="margin-top: 20px; display: flex; justify-content: space-between;" id="paginationWrapper">
            <ul class="pagination" style="display: flex; list-style: none; padding: 0; gap: 4px;"></ul>
        </div>
    </div>

    <script>
        function openFormPopup() {
            document.getElementById("popupForm").style.display = "block";
        }

        function closeFormPopup() {
            document.getElementById("popupForm").style.display = "none";
        }

        let currentPage = 1;
        const rowsPerPage = 6;

        function groupAnhBySanPhamVaMau() {
            const grouped = [];

            dsAnhRaw.forEach(anh => {
                const key = `${anh.ma_san_pham}_${anh.ma_mau}`;
                let group = grouped.find(g => g.key === key);
                if (!group) {
                    group = {
                        key,
                        ma_san_pham: anh.ma_san_pham,
                        ma_mau: anh.ma_mau,
                        ds_anh: []
                    };
                    grouped.push(group);
                }
                group.ds_anh.push(anh.duong_dan);
            });

            return grouped;
        }

        function getFilteredRows() {
            const selectedProduct = document.getElementById("selectFilter").value.toLowerCase();
            return groupAnhBySanPhamVaMau().filter(group => {
                const sp = dsSanPham.find(sp => sp.ma_san_pham === group.ma_san_pham);
                const ten = sp?.ten_san_pham?.toLowerCase() || "";
                return !selectedProduct || ten === selectedProduct;
            });
        }

        function renderTablePage(page = currentPage) {
            const filteredRows = getFilteredRows();
            const totalPages = Math.ceil(filteredRows.length / rowsPerPage);
            if (page > totalPages) page = totalPages || 1;
            currentPage = page;

            const tableBody = document.getElementById("tableBody");
            tableBody.innerHTML = "";

            const start = (currentPage - 1) * rowsPerPage;
            const rowsToRender = filteredRows.slice(start, start + rowsPerPage);

            rowsToRender.forEach((group, index) => {
                const sp = dsSanPham.find(sp => sp.ma_san_pham === group.ma_san_pham);
                const mau = dsMauSac.find(m => m.ma_mau === group.ma_mau);
                const row = document.createElement("tr");

                const htmlAnh = group.ds_anh.map(img => `<img src="<?= $baseUrl ?>${img}" style="height: 70px; margin: 4px; border-radius: 6px; border: 1px solid #ccc;">`).join("");

                row.innerHTML = `
                    <td class="center">${start + index + 1}</td>
                    <td>${sp?.ten_san_pham || ""}</td>
                    <td class="center">${mau?.ten_mau || ""}</td>
                    <td class="center">${htmlAnh}</td>
                    <td class="center">
                        <a href="#" class="btn-icon btn-delete" onclick="confirmXoaAnh(${group.ma_san_pham}, ${group.ma_mau}); return false;">
                            <i class="fas fa-trash-alt"></i>
                        </a>
                    </td>`;

                tableBody.appendChild(row);
            });

            renderPagination(totalPages);
        }

        function renderPagination(totalPages) {
            const pagination = document.querySelector(".pagination");
            pagination.innerHTML = "";

            const createPageItem = (label, page, disabled = false, active = false) => {
                const li = document.createElement("li");
                li.className = `${disabled ? "disabled" : ""} ${active ? "active" : ""}`;
                li.innerHTML = `<a href="#" onclick="changePage(${page}); return false;">${label}</a>`;
                return li;
            };

            pagination.appendChild(createPageItem("Previous", currentPage - 1, currentPage === 1));
            for (let i = 1; i <= totalPages; i++) {
                pagination.appendChild(createPageItem(i, i, false, i === currentPage));
            }
            pagination.appendChild(createPageItem("Next", currentPage + 1, currentPage === totalPages));
        }

        function changePage(page) {
            const filteredRows = getFilteredRows();
            const totalPages = Math.ceil(filteredRows.length / rowsPerPage);
            if (page < 1 || page > totalPages) return;
            currentPage = page;
            renderTablePage();
        }

        function filterByProduct() {
            currentPage = 1;
            renderTablePage();
        }

        function showToast(message, isError = false) {
            const toast = document.getElementById("toast");
            toast.innerText = message;
            toast.className = "toast show" + (isError ? " error" : "");

            setTimeout(() => {
                toast.className = "toast hidden";
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

        function confirmXoaAnh(maSanPham, maMau) {
            Swal.fire({
                title: 'Bạn có chắc chắn?',
                text: "Ảnh này sẽ bị xoá vĩnh viễn!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Xoá',
                cancelButtonText: 'Hủy'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = `/admin/anhbienthe/xoa.php?ma_san_pham=${maSanPham}&ma_mau=${maMau}`;
                }
            });
        }
    </script>
</body>

</html>