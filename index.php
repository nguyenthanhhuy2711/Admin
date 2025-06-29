<?php include 'includes/connect.php'; ?>


<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Trang quản trị</title>
    <link rel="stylesheet" href="assets/style.css">
</head>

<body>
    <div class="container">
        <div class="sidebar">
            <div class="logo">DOUBLE H ADMIN</div>
            <ul>
                <hr style="border: none; height: 1px; background-color: #ccc;">
                <li><a href=""><i class="fas fa-chart-line"></i> Dashboard</a></li>
                <li><a href="index.php?page=sanpham"><i class="fas fa-box"></i> Sản phẩm</a></li>
                <li><a href="index.php?page=donhang"><i class="fas fa-receipt"></i> Đơn hàng</a></li>
                <li><a href="index.php?page=taikhoan"><i class="fas fa-user"></i> Tài khoản</a></li>
                <li><a href="index.php?page=danhmuc"><i class="fas fa-list"></i> Danh mục</a></li>
                <li><a href="index.php?page=bienthesp"><i class="fas fa-warehouse"></i> Kho</a></li>
                <li><a href="index.php?page=mausac"><i class="fas fa-palette"></i> Màu sắc</a></li>
                <li><a href="index.php?page=anhbienthe"><i class="fas fa-image"></i> Ảnh biến thể</a></li>
                <li><a href="index.php?page=voucher"><i class="fas fa-ticket-alt"></i> Voucher</a></li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Đăng xuất</a></li>
            </ul>
        </div>
        <div class="main-content">
            <?php
            $page = $_GET['page'] ?? 'sanpham';
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