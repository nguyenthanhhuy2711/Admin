<?php
// Bắt đầu session và kiểm tra đăng nhập
include __DIR__ . '/includes/check_login.php';
include __DIR__ . '/includes/connect.php';
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Trang quản trị</title>
    <link rel="stylesheet" href="assets/style.css?v=<?= time() ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>
    <div class="container">
        <div class="sidebar">
            <div class="logo">DOUBLE H ADMIN</div>
            <?php if (isset($_SESSION['admin_name'])): ?>
                <p style="font-size: 18px; color: #333; margin-left: 35px">
                    Xin chào, <?= htmlspecialchars($_SESSION['admin_name']) ?>
                </p>
            <?php endif; ?>
            <ul>
                <hr style="border: none; height: 1px; background-color: #ccc;">

                <!-- Dashboard & System -->
                <li><a href="index.php?page=dashboard"><i class="fas fa-chart-line"></i> Dashboard</a></li>
                <li><a href="index.php?page=taikhoan"><i class="fas fa-user"></i> Tài khoản</a></li>

                <!-- Sản phẩm -->
                <li><a href="index.php?page=sanpham"><i class="fas fa-box"></i> Sản phẩm</a></li>
                <li><a href="index.php?page=danhmuc"><i class="fas fa-list"></i> Danh mục</a></li>
                <li><a href="index.php?page=anhbienthe"><i class="fas fa-image"></i> Biến thể sản phẩm</a></li>
                <li><a href="index.php?page=mausac"><i class="fas fa-palette"></i> Màu sắc</a></li>
                <li><a href="index.php?page=bienthesp"><i class="fas fa-warehouse"></i> Kho</a></li>
                <li><a href="index.php?page=phieunhap"><i class="fas fa-file-invoice"></i> Nhập kho</a></li>

                <!-- Giao dịch -->
                <li><a href="index.php?page=donhang"><i class="fas fa-receipt"></i> Đơn hàng</a></li>
                <li><a href="index.php?page=voucher"><i class="fas fa-ticket-alt"></i> Voucher</a></li>

                <!-- Phản hồi -->
                <li><a href="index.php?page=danhgia"><i class="fas fa-star-half-alt"></i> Đánh giá</a></li>
                <li><a href="index.php?page=baocaovipham"><i class="fas fa-exclamation-triangle"></i> Báo cáo vi phạm</a></li>

                <!-- Logout -->
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Đăng xuất</a></li>
            </ul>

        </div>

        <div class="main-content">
            <?php
            $page = $_GET['page'] ?? 'dashboard';
            $path = __DIR__ . "/$page/index.php";
            if (file_exists($path)) {
                include $path;
            } else {
                echo "<p>Không tìm thấy trang</p>";
            }
            ?>
        </div>
    </div>
</body>

</html>