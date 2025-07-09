<?php
include __DIR__ . '/../includes/check_login.php';
include __DIR__ . '/../includes/connect.php';
$dsDanhMuc = callAPI("getAllMaDanhMuc")["danh_sach_danh_muc"] ?? [];
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Quản lý danh mục</title>
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
    </style>
</head>

<body>
    <div class="main-content">
        <h2><i class="fas fa-list"></i> Danh mục sản phẩm</h2>
        <div style="text-align: right">
            <a href="#" class="add-btn" onclick="openFormPopup(); return false;">Thêm danh mục</a>
        </div>

        <div id="popupForm" class="popup-form">
            <div class="form-container">
                <h3 id="formTitle">Thêm danh mục mới</h3>
                <form id="categoryForm" method="post">
                    <input type="hidden" name="ma_danh_muc" id="ma_danh_muc">

                    <label>Tên danh mục:</label>
                    <input type="text"
                        name="ten_danh_muc"
                        id="ten_danh_muc"
                        required
                        oninvalid="this.setCustomValidity('Vui lòng nhập tên danh mục')"
                        oninput="this.setCustomValidity('')">

                    <div style="text-align: right; margin-top: 16px;">
                        <button type="button" onclick="closeFormPopup()">Hủy</button>
                        <button type="submit" id="formSubmitBtn">Thêm</button>
                    </div>

                </form>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th style="width: 100px" ;>STT</th>
                    <th style="width: 400px" ;>Tên danh mục</th>
                    <th style="width: 500px" ;>Ngày tạo</th>
                    <th style="width: 250px" ;>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($dsDanhMuc)): ?>
                    <?php $i = 1;
                    foreach ($dsDanhMuc as $dm): ?>
                        <tr>
                            <td class="center"><?= $i++ ?></td>
                            <td class="center"><?= htmlspecialchars($dm['ten_danh_muc']) ?></td>
                            <td class="center"><?= htmlspecialchars($dm['ngay_tao'] ?? '-') ?></td>
                            <td class="center">
                                <!-- Nút xoá -->
                                <a href="#"
                                    class="btn-icon btn-delete"
                                    title="Xoá"
                                    onclick="confirmXoaDanhMuc(<?= $dm['ma_danh_muc'] ?>); return false;">
                                    <i class="fas fa-trash-alt"></i>
                                </a>

                            </td>


                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4">Không có danh mục nào.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script>
        function openFormPopup() {
            document.getElementById('popupForm').style.display = 'block';
            document.getElementById('formTitle').innerText = 'Thêm danh mục mới';
            document.getElementById('formSubmitBtn').innerText = 'Thêm';
            document.getElementById('categoryForm').action = 'danhmuc/them.php';
            document.getElementById('categoryForm').reset();
        }

        function openEditPopup(ma, ten) {
            document.getElementById('popupForm').style.display = 'block';
            document.getElementById('formTitle').innerText = 'Chỉnh sửa danh mục';
            document.getElementById('formSubmitBtn').innerText = 'Cập nhật';
            document.getElementById('categoryForm').action = 'danhmuc/sua.php';
            document.getElementById('ma_danh_muc').value = ma;
            document.getElementById('ten_danh_muc').value = ten;
        }

        function closeFormPopup() {
            document.getElementById('popupForm').style.display = 'none';
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
        });

        function confirmXoaDanhMuc(id) {
            Swal.fire({
                title: 'Bạn có chắc chắn?',
                text: 'Danh mục này sẽ bị xoá!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Xoá',
                cancelButtonText: 'Hủy'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'danhmuc/xoa.php?id=' + id;
                }
            });
        }
    </script>
</body>

</html>