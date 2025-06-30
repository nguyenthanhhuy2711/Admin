<?php
include __DIR__ . '/../includes/check_login.php';
include __DIR__ . '/../includes/connect.php';

$data = callAPI("getAllBienTheSanPham") ?? [];
$dsSanPham = callAPI("getallSanPham") ?? [];
$dsMauSac = callAPI("getAllMauSac") ?? [];
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

        .form-container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            width: 600px;
            margin: 10px auto;
            border: 2px solid #007bff;
        }

        .form-container h3 {
            margin-top: 0;
            margin-bottom: 20px;
            color: #333;
        }

        .form-container input,
        select {
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
            font-weight: bold;
            cursor: pointer;
        }

        .form-container button[type="submit"] {
            background-color: #28a745;
            color: white;
        }

        .form-container button[type="button"] {
            background-color: #dc3545;
            color: white;
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

        #form-error {
            color: red;
            font-weight: bold;
            margin-bottom: 12px;
            text-align: center;
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

        .toast.error {
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
            background: rgba(0, 0, 0, 0.2);
            z-index: 1000;

            /* thêm 2 dòng canh giữa nếu cần */
            align-items: center;
            justify-content: center;
        }


        .form-container {
            background: #f4f4f4;
            /* Nền trắng tinh */
            padding: 24px;
            border-radius: 10px;
            width: 700px;
            max-width: 90%;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            border: 1px solid #ccc;
        }

        #form-them-nhieu-bien-the table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
            border: 1px solid #ccc;
            font-size: 14px;
        }

        #form-them-nhieu-bien-the th,
        #form-them-nhieu-bien-the td {
            border: 1px solid #ccc;
            padding: 6px;
            text-align: center;
            vertical-align: middle;
            height: auto;
        }

        #form-them-nhieu-bien-the select,
        #form-them-nhieu-bien-the input[type="number"] {
            width: 100%;
            padding: 6px 8px;
            font-size: 14px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
            background-color: white;
            appearance: auto;
            /* hiện lại giao diện chuẩn của select */
        }

        #form-them-nhieu-bien-the thead th {
            background-color: #007bff;
            color: white;
            font-weight: bold;
        }

        .form-table input {
            width: 100%;
            padding: 8px;
            border-radius: 6px;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }

        .form-table th,
        .form-table td {
            padding: 8px;
            border: 1px solid #ccc;
            text-align: center;
        }

        .form-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
        }
    </style>
</head>

<body>
    <div class="main-content">
        <h2><i class="fas fa-warehouse"></i> Quản lý biến thể sản phẩm</h2>
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
                <a href="#" class="add-btn" onclick="openFormPopup(); return false;">Nhập kho</a>
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
            <tbody id="tableBody">
                <?php if (is_array($data)): ?>
                    <?php $i = 1;
                    foreach ($data as $item): ?>
                        <tr>
                            <td><?= $i++ ?></td>
                            <td><?= htmlspecialchars($item['ten_san_pham']) ?></td>
                            <td><?= htmlspecialchars($item['kich_thuoc']) ?></td>
                            <td><?= htmlspecialchars($item['ten_mau']) ?></td>
                            <td><?= htmlspecialchars($item['so_luong_ton']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">Không có dữ liệu</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <div style="margin-top: 20px; display: flex; justify-content: space-between;" id="paginationWrapper">
            <ul class="pagination" style="display: flex; list-style: none; padding: 0; gap: 4px;"></ul>
        </div>
    </div>

    <div id="popupForm" class="popup-form">
        <div class="form-container">
            <h3>Phiếu nhập kho</h3>
            <form id="form-them-nhieu-bien-the">
                <table class="form-table">
                    <thead>
                        <tr style="background-color: #007bff; color: white;">
                            <th style="border-top-left-radius: 8px;">Người nhập</th>
                            <th>Ngày nhập</th>
                            <th style="border-top-right-radius: 8px;">Tổng số lượng</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <input type="text" readonly
                                    value="<?= $_SESSION['admin_name'] ?? 'Không xác định' ?>">
                            </td>
                            <td>
                                <input type="text" readonly name="ngay_nhap" id="ngayNhap">
                            </td>
                            <td>
                                <input type="number" readonly id="tongSoLuong" value="0">
                            </td>
                        </tr>
                    </tbody>
                </table>


                <table style="width: 100%; border-collapse: collapse; margin-bottom: 16px;">
                    <thead>
                        <tr>
                            <th style="width: 50px">STT</th>
                            <th style="width: 100px">Sản phẩm</th>
                            <th style="width: 100px">Màu sắc</th>
                            <th style="width: 100px">Kích thước</th>
                            <th style="width: 50px">Số lượng</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php for ($i = 1; $i <= 7; $i++): ?>
                            <tr>
                                <td><?= $i ?></td>
                                <td>
                                    <select name="ma_san_pham[]" id="sp-<?= $i ?>" onchange="layMauTheoSanPhamTheoDong(<?= $i ?>)">
                                        <option value="">--Chọn--</option>
                                        <?php foreach ($dsSanPham as $sp): ?>
                                            <option value="<?= $sp['ma_san_pham'] ?>"><?= $sp['ten_san_pham'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                                <td>
                                    <select name="ma_mau[]" id="mau-<?= $i ?>" class="select-mau">
                                        <option value="">--Chọn--</option>
                                        <!-- sẽ load màu theo sản phẩm tương ứng -->
                                    </select>
                                </td>
                                <td>
                                    <select name="kich_thuoc[]">
                                        <option value="">--Chọn--</option>
                                        <?php for ($size = 35; $size <= 46; $size++): ?>
                                            <option value="<?= $size ?>"><?= $size ?></option>
                                        <?php endfor; ?>
                                    </select>
                                </td>
                                <td>
                                    <input type="number" name="so_luong_ton[]" min="1">
                                </td>
                            </tr>
                        <?php endfor; ?>
                    </tbody>
                </table>
                <button type="submit">Nhập tất cả</button>
                <button type="button" onclick="closeFormPopup()">Hủy</button>
            </form>
        </div>
    </div>


    <div id="toast" class="toast hidden"></div>

    <script>
        function layMauTheoSanPhamTheoDong(index) {
            const selectSanPham = document.getElementById(`sp-${index}`);
            const selectMau = document.getElementById(`mau-${index}`);

            const maSanPham = selectSanPham.value;
            if (!maSanPham) {
                selectMau.innerHTML = '<option value="">--Chọn--</option>';
                return;
            }

            selectMau.innerHTML = '<option>Đang tải...</option>';

            fetch(`bienthesp/lay_mau.php?maSanPham=${maSanPham}`)
                .then(res => res.json())
                .then(data => {
                    selectMau.innerHTML = '<option value="">--Chọn--</option>';
                    data.forEach(mau => {
                        const option = document.createElement("option");
                        option.value = mau.ma_mau;
                        option.textContent = mau.ten_mau;
                        selectMau.appendChild(option);
                    });
                })
                .catch(() => {
                    selectMau.innerHTML = '<option value="">Không tải được màu</option>';
                });
        }

        function openFormPopup() {
            document.getElementById("popupForm").style.display = "block";
            document.getElementById("form-error").innerText = "";
        }

        function closeFormPopup() {
            document.getElementById("popupForm").style.display = "none";
        }

        function showToast(message, isError = false) {
            const toast = document.getElementById("toast");
            toast.innerText = message;
            toast.className = "toast show" + (isError ? " error" : "");

            setTimeout(() => {
                toast.className = "toast hidden";
            }, 3000);
        }

        document.getElementById("form-them-nhieu-bien-the").addEventListener("submit", function(e) {
            e.preventDefault();
            const formData = new FormData(e.target);

            fetch("bienthesp/them.php", {
                    method: "POST",
                    body: formData
                })
                .then(res => res.json())
                .then(res => {
                    if (res.success) {
                        showToast(res.message || "Thêm thành công");
                        closeFormPopup();
                        setTimeout(() => location.reload(), 1500);
                    } else {
                        showToast(res.message || "Đã xảy ra lỗi.", true);
                    }
                })
                .catch(() => {
                    showToast("Không thể kết nối đến máy chủ.", true);
                });
        });

        let currentPage = 1;
        const rowsPerPage = 10;
        const allRows = Array.from(document.querySelectorAll("#tableBody tr"));
        let filteredRows = [...allRows];

        function renderTablePage() {
            filteredRows.forEach((row, index) => {
                row.style.display = (index >= (currentPage - 1) * rowsPerPage && index < currentPage * rowsPerPage) ?
                    "" : "none";
            });
            renderPagination(Math.ceil(filteredRows.length / rowsPerPage));
        }

        function renderPagination(totalPages) {
            const pagination = document.querySelector(".pagination");
            pagination.innerHTML = "";

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
            const totalPages = Math.ceil(filteredRows.length / rowsPerPage);
            if (page < 1 || page > totalPages) return;
            currentPage = page;
            renderTablePage();
        }

        function filterByProduct() {
            const select = document.getElementById("selectFilter");
            const selectedName = select.value.toLowerCase();

            filteredRows = allRows.filter(row => {
                const tenSanPham = row.cells[1].innerText.toLowerCase();
                const match = selectedName === "" || tenSanPham === selectedName;
                row.style.display = match ? "" : "none";
                return match;
            });

            currentPage = 1; // reset về trang đầu
            renderTablePage();
        }

        // Gọi lần đầu
        renderTablePage();
        document.getElementById("ngayNhap").value = new Date().toLocaleString("vi-VN");

        function tinhTongSoLuong() {
            let tong = 0;
            const inputs = document.querySelectorAll('input[name="so_luong_ton[]"]');
            inputs.forEach(input => {
                const val = parseInt(input.value);
                if (!isNaN(val)) tong += val;
            });
            document.getElementById("tongSoLuong").value = tong;
        }

        // Gắn sự kiện input
        document.querySelectorAll('input[name="so_luong_ton[]"]').forEach(input => {
            input.addEventListener("input", tinhTongSoLuong);
        });
    </script>
</body>

</html>