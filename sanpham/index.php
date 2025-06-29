<?php
include __DIR__ . '/../includes/check_login.php';

include __DIR__ . '/../includes/connect.php';
$products = callAPI("getallSanPham");
$dsDanhMuc = callAPI("getAllMaDanhMuc")["danh_sach_danh_muc"] ?? [];

?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Danh sách sản phẩm</title>
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

        .input-select {
            width: 100%;
            padding: 10px;
            margin-bottom: 16px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 16px;
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
        <h2><i class="fas fa-box"></i> Danh sách sản phẩm</h2>
        <div style="text-align: right">
            <a href="#" class="add-btn" onclick="openFormPopup(); return false;">Thêm sản phẩm</a>
        </div>
        <div style="text-align: right; margin-bottom: 10px;">
            <button type="button" class="btn-export" onclick="downloadExcel()">📤 Xuất Excel</button>
        </div>

        <div id="popupForm" class="popup-form">
            <div class="form-container">
                <h3 id="formTitle">Thêm sản phẩm mới</h3>
                <form id="productForm" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="ma_san_pham" id="ma_san_pham">

                    <label>Tên sản phẩm:</label>
                    <input
                        type="text"
                        name="ten_san_pham"
                        id="ten_san_pham"
                        required
                        oninvalid="this.setCustomValidity('Vui lòng nhập tên sản phẩm')"
                        oninput="this.setCustomValidity('')">

                    <label>Mô tả:</label>
                    <input type="text" name="mo_ta" id="mo_ta" style="height: 50px;">

                    <label>Giá:</label>
                    <input
                        type="text"
                        id="gia_hien_thi"
                        placeholder="Nhập giá..."
                        required
                        oninvalid="this.setCustomValidity('Vui lòng nhập giá')"
                        oninput="this.setCustomValidity('')">

                    <input type="hidden" name="gia" id="gia">


                    <label>Danh mục:</label>
                    <select
                        name="ma_danh_muc"
                        id="ma_danh_muc"
                        required
                        class="input-select"
                        oninvalid="this.setCustomValidity('Vui lòng chọn danh mục')"
                        oninput="this.setCustomValidity('')">
                        <option value="">-- Chọn danh mục --</option>
                        <?php foreach ($dsDanhMuc as $dm): ?>
                            <option value="<?= $dm['ma_danh_muc'] ?>">
                                <?= htmlspecialchars($dm['ten_danh_muc']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <label>Chọn ảnh:</label>
                    <input type="file" name="file" id="file">

                    <img id="previewImage" src="" alt="Ảnh hiện tại" style="max-height: 100px; margin-bottom: 16px; display: none; border: 1px solid #ccc; border-radius: 6px;">

                    <button type="submit" id="formSubmitBtn">Thêm</button>
                    <button type="button" onclick="closeFormPopup()">Hủy</button>
                </form>

            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th class="center" style="width: 50px;">STT</th>
                    <th style="width: 300px;">Tên sản phẩm</th>
                    <th style="width: 150px;">Giá</th>
                    <th style="width: 500px;">Mô tả</th>
                    <th style="width: 130px;">Ảnh</th>
                    <th style="width: 120px;">Thao tác</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                <?php if (is_array($products) && !empty($products)): ?>
                    <?php $i = 1;
                    foreach ($products as $sp): ?>
                        <?php
                        $ten = $sp['ten_san_pham'] ?? '[Không có tên]';
                        $gia = $sp['gia'] ?? 0;
                        $anh = $sp['anh_san_pham'] ?? '';
                        $mota = $sp['mo_ta'] ?? '';
                        $ma = $sp['ma_san_pham'] ?? 0;
                        ?>
                        <tr>
                            <td class="center"><?= $i++ ?></td>
                            <td><?= htmlspecialchars($ten) ?></td>
                            <td><?= number_format($gia) ?> VND</td>
                            <td><?= htmlspecialchars($mota) ?></td>
                            <td>
                                <?php if ($anh): ?>
                                    <img src="https://cuddly-exotic-snake.ngrok-free.app<?= htmlspecialchars($anh) ?>" alt="Ảnh sản phẩm">
                                <?php else: ?>
                                    <em>Không có ảnh</em>
                                <?php endif; ?>
                            </td>
                            <td class="actions">
                                <a href="#" class="btn-icon btn-edit" title="Sửa" onclick="openEditPopup(<?= $ma ?>); return false;">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="/admin/sanpham/xoa.php?id=<?= $ma ?>" class="btn-icon btn-delete" title="Xoá" onclick="return confirm('Xoá sản phẩm này?')">
                                    <i class="fas fa-trash-alt"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6">Không có sản phẩm nào để hiển thị.</td>
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
            document.getElementById('productForm').reset();
            document.getElementById('formTitle').innerText = 'Thêm sản phẩm mới';
            document.getElementById('formSubmitBtn').innerText = 'Thêm';
            document.getElementById('productForm').action = 'sanpham/them.php';

            // Ẩn ảnh nếu đang hiển thị
            document.getElementById('previewImage').style.display = 'none';
        }

        function closeFormPopup() {
            document.getElementById('popupForm').style.display = 'none';
        }

        function openEditPopup(maSanPham) {
            fetch('sanpham/lay.php?maSanPham=' + maSanPham)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const sp = data.data;
                        document.getElementById('ma_san_pham').value = sp.ma_san_pham;
                        document.getElementById('ten_san_pham').value = sp.ten_san_pham;
                        document.getElementById('mo_ta').value = sp.mo_ta;
                        document.getElementById('gia').value = sp.gia;
                        document.getElementById('gia_hien_thi').value = formatNumberWithCommas(sp.gia) + ' VND';
                        document.getElementById('ma_danh_muc').value = sp.ma_danh_muc;
                        document.getElementById('formTitle').innerText = 'Chỉnh sửa sản phẩm';
                        document.getElementById('formSubmitBtn').innerText = 'Cập nhật';
                        document.getElementById('productForm').action = 'sanpham/sua.php';
                        document.getElementById('popupForm').style.display = 'block';

                        // Hiển thị ảnh hiện tại
                        if (sp.anh_san_pham) {
                            document.getElementById('previewImage').style.display = 'block';
                            document.getElementById('previewImage').src = 'https://cuddly-exotic-snake.ngrok-free.app' + sp.anh_san_pham;
                        } else {
                            document.getElementById('previewImage').style.display = 'none';
                        }
                    } else {
                        alert('Không tìm thấy sản phẩm');
                    }
                });
        }
        let currentPage = 1;
        const rowsPerPage = 5;
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

        // Tải lần đầu
        renderTablePage();

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
        });
        const giaHienThiInput = document.getElementById("gia_hien_thi");
        const giaHiddenInput = document.getElementById("gia");

        giaHienThiInput.addEventListener("input", function() {
            // Bỏ VND nếu có
            const rawValue = this.value.replace(/[^\d]/g, ""); // loại tất cả ký tự không phải số

            if (rawValue === "") {
                giaHiddenInput.value = "";
                this.value = "";
                return;
            }

            const formatted = Number(rawValue).toLocaleString("en-US");
            giaHiddenInput.value = rawValue;
            this.value = `${formatted} VND`;
        });

        function formatNumberWithCommas(number) {
            return Number(number).toLocaleString("en-US");
        }

        function downloadExcel() {
            fetch('sanpham/xuat_excel.php')
                .then(response => response.blob())
                .then(blob => {
                    const url = window.URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = 'danhsach_sanpham.xlsx'; // đổi tên file .xlsx
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