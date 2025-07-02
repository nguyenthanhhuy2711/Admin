    <?php
    include __DIR__ . '/../includes/check_login.php';
    include __DIR__ . '/../includes/connect.php';

    $dsPhieuNhap = callAPI("getAllPhieuNhap") ?? [];
    $dsSanPham = callAPI("getallSanPham") ?? [];
    $dsMauSac = callAPI("getAllMauSac") ?? [];
    ?>

    <!DOCTYPE html>
    <html lang="vi">

    <head>
        <meta charset="UTF-8">
        <title>Quản lý phiếu nhập</title>
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
                vertical-align: middle;
            }

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

            .btn-view {
                background-color: #17a2b8;
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

            #form-error {
                color: red;
                font-weight: bold;
                margin-bottom: 12px;
                text-align: center;
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
        </style>
    </head>

    <body>
        <div class="main-content">
            <h2><i class="fas fa-file-invoice"></i> Danh sách phiếu nhập</h2>
            <div>
                <a href="#" class="add-btn" onclick="openFormPopup(); return false;">Nhập kho</a>
            </div>

            <table>
                <thead>
                    <tr>
                        <th style="width: 80px">STT</th>
                        <th style="width: 150px">Mã phiếu</th>
                        <th style="width: 200px">Người nhập</th>
                        <th style="width: 200px">Ngày nhập</th>
                        <th style="width: 150px">Tổng số lượng</th>
                        <th style="width: 120px">Thao tác</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    <?php if (is_array($dsPhieuNhap) && !empty($dsPhieuNhap)): ?>
                        <?php $i = 1;
                        foreach ($dsPhieuNhap as $phieu): ?>
                            <tr>
                                <td class="center"><?= $i++ ?></td>
                                <td class="center"><?= htmlspecialchars($phieu['ma_phieu_nhap']) ?></td>
                                <td class="center"><?= htmlspecialchars($phieu['nguoi_nhap']) ?></td>
                                <td class="center"><?= date('d/m/Y', strtotime($phieu['ngay_nhap'])) ?></td>
                                <td class="center"><?= htmlspecialchars($phieu['tong_so_luong']) ?></td>
                                <td class="center">
                                    <a href="#" class="btn-icon btn-view" onclick="xemChiTiet('<?= $phieu['ma_phieu_nhap'] ?>')">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="center">Không có phiếu nhập nào.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            <div style="margin-top: 20px; display: flex; justify-content: space-between;" id="paginationWrapper">
                <ul class="pagination" style="display: flex; list-style: none; padding: 0; gap: 4px;"></ul>
            </div>
        </div>

        <div id="popupChiTiet" class="popup-form">
            <div class="form-container">
                <h3>Chi tiết phiếu nhập <span id="ct-ma-phieu"></span></h3>
                <!-- Thông tin chung phiếu nhập -->
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
                            <td><input type="text" id="ct-nguoi-nhap" readonly></td>
                            <td><input type="text" id="ct-ngay-nhap" readonly></td>
                            <td><input type="number" id="ct-tong-so-luong" readonly></td>
                        </tr>
                    </tbody>
                </table>

                <!-- Bảng chi tiết sản phẩm -->
                <table class="form-table" style="width: 100%; margin-top: 16px;">
                    <thead>
                        <tr style="background-color: #007bff; color: white;">
                            <th style="width: 200px; border-top-left-radius: 8px;">Sản phẩm</th>
                            <th style="width: 100px;">Màu sắc</th>
                            <th style="width: 100px;">Kích thước</th>
                            <th style="width: 100px; border-top-right-radius: 8px;">Số lượng</th>
                        </tr>
                    </thead>
                    <tbody id="tableChiTiet">
                        <!-- Dòng chi tiết sẽ được thêm bằng JS -->
                    </tbody>
                </table>

                <div style="text-align: right; margin-top: 12px;">
                    <button onclick="dongChiTiet()" style="padding: 10px 16px; border: none; border-radius: 6px; font-weight: bold; background-color: #dc3545; color: white;">Đóng</button>
                </div>
            </div>
        </div>


        <div id="popupForm" class="popup-form">
            <div class="form-container">
                <h3>Phiếu nhập kho</h3>
                <form method="post" action="phieunhap/nhap_kho.php">
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
                                    <input type="text" name="nguoi_nhap" readonly value="<?= $_SESSION['admin_name'] ?? 'Không xác định' ?>">
                                </td>
                                <td>
                                    <input type="text" readonly name="ngay_nhap" id="ngayNhap">
                                </td>
                                <td>
                                    <input type="number" readonly name="tong_so_luong" id="tongSoLuong" value="0">
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- Bảng chi tiết nhập -->
                    <table style="width: 100%; border-collapse: collapse; margin-bottom: 16px;">
                        <thead>
                            <tr>
                                <th style="width: 30px">STT</th>
                                <th style="width: 200px">Sản phẩm</th>
                                <th style="width: 70px">Màu sắc</th>
                                <th style="width: 70px">Kích thước</th>
                                <th style="width: 30px">Số lượng</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php for ($i = 1; $i <= 5; $i++): ?>
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
                                            <option value="">Chọn</option>
                                        </select>
                                    </td>
                                    <td>
                                        <select name="kich_thuoc[]">
                                            <option value="">Chọn</option>
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
                    <div style="text-align: right; margin-top: 16px;">
                        <button type="submit">Nhập tất cả</button>
                        <button type="button" onclick="closeFormPopup()">Hủy</button>
                    </div>

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
                    selectMau.innerHTML = '<option value="">Chọn</option>';
                    return;
                }

                selectMau.innerHTML = '<option>...</option>';

                fetch(`phieunhap/lay_mau.php?maSanPham=${maSanPham}`)
                    .then(res => res.json())
                    .then(data => {
                        selectMau.innerHTML = '<option value="">Chọn</option>';
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

            function xemChiTiet(maPhieuNhap) {
                fetch(`phieunhap/lay_chi_tiet_phieu.php?ma_phieu_nhap=${maPhieuNhap}`)
                    .then(res => res.json())
                    .then(data => {
                        if (!data || !data.phieu_nhap || !Array.isArray(data.chi_tiet)) {
                            alert("Dữ liệu không hợp lệ");
                            return;
                        }
                        document.getElementById("ct-ma-phieu").textContent = `${maPhieuNhap}`;

                        // Hiển thị thông tin chung
                        document.getElementById("ct-nguoi-nhap").value = data.phieu_nhap.nguoi_nhap || "";
                        document.getElementById("ct-ngay-nhap").value = data.phieu_nhap.ngay_nhap || "";
                        document.getElementById("ct-tong-so-luong").value = data.phieu_nhap.tong_so_luong || 0;

                        // Hiển thị chi tiết
                        const tbody = document.getElementById("tableChiTiet");
                        tbody.innerHTML = "";

                        data.chi_tiet.forEach(row => {
                            const tr = document.createElement("tr");
                            tr.innerHTML = `
                    <td>${row.ten_san_pham}</td>
                    <td>${row.ten_mau}</td>
                    <td>${row.kich_thuoc}</td>
                    <td>${row.so_luong}</td>
                `;
                            tbody.appendChild(tr);
                        });

                        document.getElementById("popupChiTiet").style.display = "block";
                    })
                    .catch(err => {
                        alert("Lỗi khi tải chi tiết phiếu nhập");
                        console.error(err);
                    });
            }



            function dongChiTiet() {
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
            window.addEventListener('DOMContentLoaded', () => {
                const success = sessionStorage.getItem('toastSuccess');
                const error = sessionStorage.getItem('toastError');

                if (success) {
                    showToast(success, false);
                    sessionStorage.removeItem('toastSuccess');
                }

                if (error) {
                    showToast(error, true);
                    sessionStorage.removeItem('toastError');
                }
            });
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

            function openFormPopup() {
                document.getElementById("popupForm").style.display = "block";
                document.getElementById("form-error").innerText = "";
            }

            function closeFormPopup() {
                document.getElementById("popupForm").style.display = "none";
            }
            document.getElementById("ngayNhap").value = new Date().toLocaleDateString("vi-VN");

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