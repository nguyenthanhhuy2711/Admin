<?php
include __DIR__ . '/../includes/check_login.php';
include __DIR__ . '/../includes/connect.php';
$users = callAPI("getallUser");
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Danh sách tài khoản</title>
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

        th.center,
        td.center {
            text-align: center;
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

        .btn-icon:hover {
            opacity: 0.8;
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
            margin: 80px auto;
            border: 2px solid #007bff;
        }

        .form-container h3 {
            margin-top: 0;
            margin-bottom: 20px;
            color: #333;
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
        <h2><i class="fas fa-user"></i> Danh sách tài khoản</h2>
        <div style="text-align: right">
            <a href="#" class="add-btn" onclick="openFormPopup(); return false;">Thêm tài khoản</a>
        </div>


        <div id="popupForm" class="popup-form">
            <div class="form-container">
                <h3>Thêm tài khoản</h3>
                <form id="userForm" method="post" action="them.php">
                    <input type="hidden" name="ma_nguoi_dung" id="ma_nguoi_dung">

                    <label>Họ tên:</label>
                    <input type="text" name="ten_nguoi_dung" required
                        oninvalid="this.setCustomValidity('Vui lòng nhập họ tên')"
                        oninput="this.setCustomValidity('')">

                    <label>Email:</label>
                    <input type="email" name="email" required
                        oninvalid="this.setCustomValidity('Vui lòng nhập email hợp lệ')"
                        oninput="this.setCustomValidity('')">

                    <label>Mật khẩu:</label>
                    <input type="password" name="mat_khau" required
                        oninvalid="this.setCustomValidity('Vui lòng nhập mật khẩu')"
                        oninput="this.setCustomValidity('')">

                    <label>Số điện thoại:</label>
                    <input type="text" name="sdt" required
                        oninvalid="this.setCustomValidity('Vui lòng nhập số điện thoại')"
                        oninput="this.setCustomValidity('')">

                    <label>Địa chỉ:</label>
                    <input type="text" name="dia_chi_mac_dinh"
                        oninvalid="this.setCustomValidity('Vui lòng nhập địa chỉ')"
                        oninput="this.setCustomValidity('')">

                    <label>Vai trò:</label>
                    <select name="vai_tro" required
                        oninvalid="this.setCustomValidity('Vui lòng chọn vai trò')"
                        oninput="this.setCustomValidity('')">
                        <option value="">-- Chọn vai trò --</option>
                        <option value="user">user</option>
                        <option value="admin">admin</option>
                    </select>

                    <button type="submit">Thêm</button>
                    <button type="button" onclick="closeFormPopup()">Hủy</button>
                </form>

            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th class="center" style="width: 50px;">STT</th>
                    <th style="width: 250px;">Tên người dùng</th>
                    <th style="width: 200px;">Email</th>
                    <th style="width: 100px;">SĐT</th>
                    <th style="width: 250px;">Địa chỉ</th>
                    <th style="width: 30px;">Vai trò</th>
                    <th style="width: 200px;">Ngày tạo</th>
                    <th class="center" style="width: 128px;">Thao tác</th>
                </tr>

            </thead>
            <tbody id="tableBody">
                <?php if (is_array($users) && !empty($users)): ?>
                    <?php $i = 1;
                    foreach ($users as $user): ?>
                        <tr>
                            <td class="center"><?= $i++ ?></td>
                            <td><?= htmlspecialchars($user['ten_nguoi_dung']) ?></td>
                            <td><?= htmlspecialchars($user['email']) ?></td>
                            <td><?= htmlspecialchars($user['sdt']) ?></td>
                            <td><?= htmlspecialchars($user['dia_chi_mac_dinh']) ?></td>
                            <td><?= htmlspecialchars($user['vai_tro']) ?></td>
                            <td><?= htmlspecialchars($user['ngay_tao']) ?></td>
                            <td class="actions">
                                <a href="#" class="btn-icon btn-edit" title="Sửa" onclick="openEditPopup(<?= $user['ma_nguoi_dung'] ?>); return false;"><i class="fas fa-edit"></i></a>
                                <a href="#" class="btn-icon btn-delete" title="Xoá" onclick="xoaUser(<?= $user['ma_nguoi_dung'] ?>); return false;">
                                    <i class="fas fa-trash-alt"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8">Không có người dùng nào để hiển thị.</td>
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
            document.getElementById('userForm').reset();
            document.getElementById('ma_nguoi_dung').value = '';
        }

        function closeFormPopup() {
            document.getElementById('popupForm').style.display = 'none';
        }

        function openEditPopup(id) {
            fetch('lay.php?id=' + id)
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        const u = data.data;
                        document.querySelector('[name="ma_nguoi_dung"]').value = u.ma_nguoi_dung;
                        document.querySelector('[name="ten_nguoi_dung"]').value = u.ten_nguoi_dung;
                        document.querySelector('[name="email"]').value = u.email;
                        document.querySelector('[name="mat_khau"]').value = '';
                        document.querySelector('[name="sdt"]').value = u.sdt;
                        document.querySelector('[name="dia_chi_mac_dinh"]').value = u.dia_chi_mac_dinh;
                        document.querySelector('[name="vai_tro"]').value = u.vai_tro;
                        document.getElementById('popupForm').style.display = 'block';
                    } else {
                        alert('Không tìm thấy người dùng');
                    }
                });
        }

        function xoaUser(id) {
            if (!confirm("Bạn có chắc chắn muốn xoá người dùng này?")) return;

            const formData = new FormData();
            formData.append("ma_nguoi_dung", id);

            fetch("taikhoan/xoa.php", {
                    method: "POST",
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    const isSuccess = data.success === true || data.success === "true";
                    showToast(data.message, !isSuccess);

                    if (isSuccess) {
                        setTimeout(() => {
                            window.location.reload(); // dùng reload cho chắc chắn
                        }, 1000);
                    }
                })
                .catch(error => {
                    showToast("Xoá thất bại. Vui lòng thử lại.", true);
                    console.error("Lỗi khi gọi API xoá:", error);
                });
        }

        document.getElementById('userForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const form = this;
            const formData = new FormData(form);

            fetch('taikhoan/them.php', {
                    method: 'POST',
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    showToast(data.message, !data.success);
                    if (data.success) {
                        form.reset();
                        closeFormPopup();
                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                    }
                })
                .catch(error => {
                    console.error('Lỗi:', error);
                    showToast('Lỗi khi gửi dữ liệu', true);
                });
        });
        let currentPage = 1;
        const rowsPerPage = 7;
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
    </script>
</body>

</html>