<?php
include __DIR__ . '/../includes/check_login.php';
include __DIR__ . '/../includes/connect.php';

$data = callAPI("getallTocao");
$dsToCao = $data['danh_sach_to_cao'] ?? [];
$usersData = callAPI("getallUser");
$users = [];

if (is_array($usersData)) {
    foreach ($usersData as $u) {
        $users[$u['ma_nguoi_dung']] = $u['ten_nguoi_dung'];
    }
}

?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Quản lý tố cáo</title>
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
    </style>
</head>

<body>
    <div class="main-content">
        <h2><i class="fas fa-exclamation-triangle"></i> Danh sách báo cáo</h2>
        <div style="display: flex; margin: 16px 0;">
            <div style="display: flex; align-items: center; gap: 8px;">
                <label for="filterTrangThai" style="font-weight: 600;">Trạng thái:</label>
                <select id="filterTrangThai" onchange="changePage(1)" style="
            padding: 8px 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
            background-color: #fff;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        ">
                    <option value="tatca">Tất cả</option>
                    <option value="cho_xu_ly">Chờ xử lý</option>
                    <option value="da_xu_ly">Đã xử lý</option>
                    <option value="tu_choi">Từ chối</option>
                </select>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th style="width: 50px;">STT</th>
                    <th style="width: 150px;">Mã tố cáo</th>
                    <th style="width: 120px;">Mã người dùng</th>
                    <th style="width: 150px;">Mã đơn hàng</th>
                    <th style="width: 160px;">Lý do</th>
                    <th style="width: 250px;">Nội dung</th>
                    <th style="width: 140px;">Thời gian gửi</th>
                    <th style="width: 110px;">Trạng thái</th>
                    <th style="width: 130px;">Thao tác</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                <?php if (is_array($dsToCao) && !empty($dsToCao)): ?>
                    <?php $i = 1;
                    foreach ($dsToCao as $tc): ?>
                        <tr data-trang-thai="<?= $tc['trang_thai'] ?>">

                            <td class="center"><?= $i++ ?></td>
                            <td class="center"><?= htmlspecialchars($tc['ma_to_cao']) ?></td>
                            <td class="center">
                                <?= isset($users[$tc['ma_nguoi_dung']])
                                    ? htmlspecialchars($users[$tc['ma_nguoi_dung']])
                                    : htmlspecialchars($tc['ma_nguoi_dung']) ?>
                            </td>
                            <td class="center"><?= htmlspecialchars($tc['ma_don_hang']) ?></td>
                            <td><?= htmlspecialchars($tc['ly_do']) ?></td>
                            <td><?= htmlspecialchars($tc['noi_dung']) ?></td>
                            <td class="center"><?= htmlspecialchars($tc['thoi_gian_gui']) ?></td>
                            <td class="center">
                                <?php
                                $hienTrangThai = [
                                    'cho_xu_ly' => 'Chờ xử lý',
                                    'da_xu_ly' => 'Đã xử lý',
                                    'tu_choi' => 'Từ chối',
                                ];
                                echo htmlspecialchars($hienTrangThai[$tc['trang_thai']] ?? $tc['trang_thai']);
                                ?>
                            </td>

                            <td class="center">
                                <?php if ($tc['trang_thai'] === 'cho_xu_ly'): ?>
                                    <a href="#" class="btn-icon btn-edit" title="Cập nhật trạng thái" onclick="hienPopupCapNhat(<?= $tc['id'] ?>)">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                <?php else: ?>
                                    <span style="color: gray;">Không thể cập nhật</span>
                                <?php endif; ?>
                            </td>

                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="9" class="center">Không có tố cáo nào.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <div style="margin-top: 20px; display: flex; justify-content: space-between;" id="paginationWrapper">
            <ul class="pagination" style="display: flex; list-style: none; padding: 0; gap: 4px;"></ul>
        </div>
    </div>
    <div id="popupCapNhat" class="popup-form">
        <div class="form-container">
            <h3>Cập nhật trạng thái tố cáo</h3>
            <form id="formCapNhat">
                <input type="hidden" name="id" id="capNhatId">
                <label>Chọn trạng thái mới:</label>
                <select name="trang_thai" required style="padding: 8px 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
            background-color: #fff;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        ">
                    <option value="">-- Chọn --</option>
                    <option value="da_xu_ly">Đã xử lý</option>
                    <option value="tu_choi">Từ chối</option>
                </select>
                <div style="text-align: right; margin-top: 16px;">
                    <button type="submit">Cập nhật</button>
                    <button type="button" onclick="closePopupCapNhat()">Hủy</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function hienPopupCapNhat(id) {
            document.getElementById("capNhatId").value = id;
            document.getElementById("popupCapNhat").style.display = "block";
        }

        function closePopupCapNhat() {
            document.getElementById("popupCapNhat").style.display = "none";
        }
        document.getElementById("formCapNhat").addEventListener("submit", function(e) {
            e.preventDefault(); // Ngăn form reload trang

            const form = e.target;
            const formData = new FormData(form);

            fetch("baocaovipham/capnhat.php", {
                    method: "POST",
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        showToast("Cập nhật trạng thái thành công");
                    } else {
                        showToast(data.message || "Cập nhật thất bại", true);
                    }
                    closePopupCapNhat();
                    setTimeout(() => window.location.reload(), 1000); // Reload lại trang sau 1s
                })
                .catch(err => {
                    showToast("Có lỗi xảy ra", true);
                    console.error(err);
                });
        });

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
            renderTablePage();
        });

        let currentPage = 1;
        const rowsPerPage = 7;

        function getFilteredRows() {
            const selectedStatus = document.getElementById("filterTrangThai").value;
            const allRows = Array.from(document.querySelectorAll("#tableBody tr"));

            return allRows.filter(row => {
                const rowStatus = row.dataset.trangThai; // Lấy từ data-trang-thai
                return selectedStatus === "tatca" || rowStatus === selectedStatus;
            });
        }


        function renderTablePage() {
            const filteredRows = getFilteredRows();
            const totalPages = Math.ceil(filteredRows.length / rowsPerPage);

            // Ẩn tất cả
            document.querySelectorAll("#tableBody tr").forEach(row => row.style.display = "none");

            // Hiện theo trang
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
            const totalPages = Math.ceil(getFilteredRows().length / rowsPerPage);
            if (page < 1 || page > totalPages) return;
            currentPage = page;
            renderTablePage();
        }
    </script>
</body>

</html>