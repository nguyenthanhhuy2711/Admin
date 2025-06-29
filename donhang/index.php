<?php
include __DIR__ . '/../includes/check_login.php';
include __DIR__ . '/../includes/connect.php';

$donHangs = callAPI("adminGetAllDonHang") ?? [];
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Danh sách đơn hàng</title>
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
        }

        td.center {
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

        .btn-detail {
            background-color: #17a2b8;
        }

        .btn-status {
            background-color: #ffc107;
            color: black;
        }

        /* Popup */
        .popup-form {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.4);
            z-index: 1000;
            align-items: center;
            justify-content: center;
            display: flex;
        }

        .popup-form .form-container {
            background: white;
            padding: 30px 25px;
            border-radius: 12px;
            width: 400px;
            max-width: 95%;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.25);
            border: 1px solid #007bff;
            animation: fadeIn 0.3s ease-in-out;
        }

        .popup-form h3 {
            margin-top: 0;
            margin-bottom: 20px;
            font-size: 20px;
            color: #007bff;
            text-align: center;
        }

        .popup-form label {
            display: block;
            font-weight: 600;
            margin-bottom: 6px;
            color: #333;
        }

        .popup-form select,
        .popup-form input {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
            margin-bottom: 16px;
            font-size: 15px;
        }

        .popup-form button {
            padding: 10px 16px;
            border: none;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
            font-size: 15px;
            margin-right: 8px;
        }

        .popup-form button[type="submit"] {
            background-color: #28a745;
            color: white;
        }

        .popup-form button[type="button"] {
            background-color: #dc3545;
            color: white;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
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
        <h2><i class="fas fa-receipt"></i> Danh sách đơn hàng</h2>
        <div style="margin-bottom: 20px;">
            <label for="filterTrangThai">Lọc theo trạng thái:</label>
            <select id="filterTrangThai" style="padding: 8px; border-radius: 6px; margin-left: 10px;">
                <option value="tatca">Tất cả</option>
                <option value="Chờ xác nhận">Chờ xác nhận</option>
                <option value="Chờ lấy hàng">Chờ lấy hàng</option>
                <option value="Chờ giao hàng">Chờ giao hàng</option>
                <option value="Đã giao">Đã giao</option>
                <option value="Đã hủy">Đã hủy</option>
            </select>
        </div>

        <table>
            <thead>
                <tr>
                    <th class="center" style="width: 50px;">STT</th>
                    <th style="width: 80px;">Mã ĐH</th>
                    <th style="width: 120px;">Người dùng</th>
                    <th style="width: 120px;">Người nhận</th>
                    <th style="width: 120px;">SĐT</th>
                    <th style="width: 250px;">Địa chỉ</th>
                    <th style="width: 120px;">Voucher đơn</th>
                    <th style="width: 120px;">Voucher ship</th>
                    <th style="width: 150px;">Phương thức giao</th>
                    <th style="width: 120px;">Phí vận chuyển</th>
                    <th style="width: 120px;">Tổng tiền</th>
                    <th style="width: 120px;">Trạng thái</th>
                    <th style="width: 150px;">Ngày tạo</th>
                    <th class="center" style="width: 120px;">Thao tác</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                <?php if (is_array($donHangs) && !empty($donHangs)): ?>
                    <?php $i = 1;
                    foreach ($donHangs as $dh): ?>
                        <tr>
                            <td class="center"><?= $i++ ?></td>
                            <td><?= htmlspecialchars($dh['ma_don_hang']) ?></td>
                            <td><?= htmlspecialchars($dh['ten_nguoi_dung'] ?? '---') ?></td>
                            <td><?= htmlspecialchars($dh['ten_nguoi_nhan']) ?></td>
                            <td><?= htmlspecialchars($dh['so_dien_thoai']) ?></td>
                            <td><?= htmlspecialchars($dh['dia_chi_giao_hang']) ?></td>
                            <td><?= htmlspecialchars($dh['voucher_order'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($dh['voucher_ship'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($dh['ten_phuong_thuc'] ?? '-') ?></td>
                            <td><?= number_format($dh['chi_phi_van_chuyen'] ?? 0, 0, ',', '.') ?> VND</td>
                            <td><?= number_format($dh['tong_tien'], 0, ',', '.') ?> VND</td>
                            <td><?= htmlspecialchars($dh['trang_thai']) ?></td>
                            <td><?= date('d/m/Y H:i:s', strtotime($dh['ngay_tao'])) ?></td>
                            <td class="center">
                                <a href="#" class="btn-icon btn-detail" title="Chi tiết"
                                    onclick="hienChiTietDonHang(<?= $dh['ma_don_hang'] ?>); return false;">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="#" class="btn-icon btn-status" title="Cập nhật trạng thái"
                                    onclick="capNhatTrangThaiTuDong(<?= $dh['ma_don_hang'] ?>, '<?= $dh['trang_thai'] ?>'); return false;">
                                    <i class="fas fa-sync-alt"></i>
                                </a>

                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="14">Không có đơn hàng nào để hiển thị.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        </table>
        <div style="margin-top: 20px; display: flex; justify-content: space-between;" id="paginationWrapper">
            <ul class="pagination" style="display: flex; list-style: none; padding: 0; gap: 4px;"></ul>
        </div>

    </div>

    <!-- Popup cập nhật trạng thái -->
    <div id="popupCapNhat" class="popup-form" style="display: none;">
        <div class="form-container">
            <h3>Cập nhật trạng thái đơn hàng</h3>
            <form id="capnhatForm">
                <input type="hidden" name="ma_don_hang" id="capnhat_ma_don_hang">
                <label>Trạng thái mới:</label>
                <select name="trang_thai_moi" required>
                    <option value="Chờ xác nhận">Chờ xác nhận</option>
                    <option value="Chờ lấy hàng">Chờ lấy hàng</option>
                    <option value="Chờ giao hàng">Chờ giao hàng</option>
                    <option value="Đã giao">Đã giao</option>
                    <option value="Đã hủy">Đã hủy</option>
                </select>
                <button type="submit">Cập nhật</button>
                <button type="button" onclick="closePopup()">Hủy</button>
            </form>
        </div>
    </div>

    <div id="popupChiTiet" class="popup-form" style="display: none;">
        <div class="form-container" style="width: 600px;">
            <h3>Chi tiết đơn hàng</h3>
            <div id="chiTietContent">Đang tải...</div>
            <button type="button" onclick="closePopupChiTiet()">Đóng</button>
        </div>
    </div>



    <script>
        function capNhatTrangThaiTuDong(maDonHang, trangThaiHienTai) {
            const nextTrangThaiMap = {
                "Chờ xác nhận": "Chờ lấy hàng",
                "Chờ lấy hàng": "Chờ giao hàng",
                "Chờ giao hàng": "Đã giao"
            };

            if (trangThaiHienTai === "Đã giao" || trangThaiHienTai === "Đã hủy") {
                showToast("Không thể cập nhật đơn này nữa!", true);
                return;
            }

            const trangThaiMoi = nextTrangThaiMap[trangThaiHienTai];
            if (!trangThaiMoi) {
                showToast("Không xác định được trạng thái kế tiếp", true);
                return;
            }

            const formData = new FormData();
            formData.append("ma_don_hang", maDonHang);
            formData.append("trang_thai_moi", trangThaiMoi);

            fetch("donhang/capnhat.php", {
                    method: "POST",
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    showToast(data.message, !data.success);

                    if (data.success) {
                        setTimeout(() => {
                            location.reload(); // ✅ Reload sau 1.5 giây
                        }, 1500);
                    }
                })
                .catch(err => {
                    console.error("Lỗi cập nhật:", err);
                    showToast("Có lỗi xảy ra khi cập nhật", true);
                });
        }


        function closePopup() {
            document.getElementById('popupCapNhat').style.display = 'none';
        }

        document.getElementById('capnhatForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);

            fetch('donhang/capnhat.php', {
                    method: 'POST',
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    alert(data.message);
                    if (data.success) {
                        window.location.reload();
                    }
                })
                .catch(err => {
                    alert('Lỗi khi gửi yêu cầu');
                    console.error(err);
                });
        });
        document.getElementById("filterTrangThai").addEventListener("change", function() {
            const selectedStatus = this.value;
            const rows = document.querySelectorAll("tbody tr");

            rows.forEach(row => {
                const statusCell = row.children[11]; // Cột trạng thái
                if (!statusCell) return;

                const trangThai = statusCell.textContent.trim();

                if (selectedStatus === "tatca" || trangThai === selectedStatus) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            });
        });
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

        function hienChiTietDonHang(maDonHang) {
            document.getElementById("chiTietContent").innerHTML = "Đang tải...";
            document.getElementById("popupChiTiet").style.display = "flex";

            fetch(`donhang/chi_tiet_don_hang.php?ma_don_hang=${maDonHang}`)
                .then(res => res.text())
                .then(html => {
                    document.getElementById("chiTietContent").innerHTML = html;
                })
                .catch(err => {
                    document.getElementById("chiTietContent").innerHTML = "Không tải được chi tiết đơn hàng.";
                    console.error(err);
                });
        }

        function closePopupChiTiet() {
            document.getElementById("popupChiTiet").style.display = "none";
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
    </script>
</body>

</html>