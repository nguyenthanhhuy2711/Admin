<?php
include_once __DIR__ . '/../includes/connect.php';

$tongQuan = callAPI("dashboard/tongquan", "GET") ?? [
    "so_don" => 0,
    "tong_doanh_thu" => 0,
    "so_nguoi_dung" => 0,
    "so_san_pham" => 0
];
// Gọi API doanh thu theo tháng
$doanhThuTheoThang = callAPI("dashboard/doanhthu-theo-thang") ?? [];

// Gọi API trạng thái đơn hàng
$trangThaiDonHang = callAPI("dashboard/trangthai-donhang") ?? [];
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8" />
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f8f8f8;
            margin-left: 260px;
        }

        h2 {
            margin-top: 13px;
            color: #333;
        }

        .cards {
            display: flex;
            gap: 20px;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }

        .card {
            background-color: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            width: 298px;
            /* Rộng hơn mặc định */
            font-size: 18px;
            text-align: center;
        }


        .charts {
            display: flex;
            gap: 20px;
            max-width: 600px;
        }

        .chart-container {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            flex: 1;
        }
    </style>
</head>

<body>

    <h2><i class="fas fa-chart-line"></i> Thống kê tổng quan</h2>
    <div class="cards">
        <a href="index.php?page=donhang" style="text-decoration: none; color: inherit;">
            <div class="card">
                <h3>🛒 Đơn hàng</h3>
                <p><?= $tongQuan['so_don'] ?></p>
            </div>
        </a>

        <a href="index.php?page=donhang" style="text-decoration: none; color: inherit;">
            <div class="card">
                <h3>💵 Doanh thu</h3>
                <p><?= number_format($tongQuan['tong_doanh_thu'], 0, ',', '.') ?> VND</p>
            </div>

        </a>
        <a href="index.php?page=taikhoan" style="text-decoration: none; color: inherit;">
            <div class="card">
                <h3>👤 Người dùng</h3>
                <p><?= $tongQuan['so_nguoi_dung'] ?></p>
            </div>
        </a>

        <a href="index.php?page=sanpham" style="text-decoration: none; color: inherit;">
            <div class="card">
                <h3>📦 Sản phẩm</h3>
                <p><?= $tongQuan['so_san_pham'] ?></p>
            </div>
        </a>

    </div>


    <div class="charts">
        <div class="chart-container">
            <h3>Doanh thu theo tháng</h3>
            <canvas id="revenueChart" width="850" height="300"></canvas>
        </div>
        <div class="chart-container">
            <h3>Tình trạng đơn hàng</h3>
            <canvas id="orderStatusChart" width="300" height="300"></canvas>
        </div>
    </div>
    <script>
        // Dữ liệu từ PHP
        const doanhThuData = <?= json_encode($doanhThuTheoThang) ?>;
        const trangThaiData = <?= json_encode($trangThaiDonHang) ?>;

        // Chuyển dữ liệu doanh thu
        const labels = doanhThuData.map(item => 'Tháng ' + item.thang);
        const values = doanhThuData.map(item => item.doanh_thu);

        const ctx1 = document.getElementById('revenueChart').getContext('2d');
        new Chart(ctx1, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Triệu đồng',
                    data: values,
                    borderColor: 'blue',
                    backgroundColor: 'rgba(0, 0, 255, 0.1)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true
            }
        });

        // Chuyển dữ liệu trạng thái đơn hàng
        const statusLabels = trangThaiData.map(item => item.trang_thai);
        const statusValues = trangThaiData.map(item => item.so_luong);

        const ctx2 = document.getElementById('orderStatusChart').getContext('2d');
        new Chart(ctx2, {
            type: 'doughnut',
            data: {
                labels: statusLabels,
                datasets: [{
                    label: 'Đơn hàng',
                    data: statusValues,
                    backgroundColor: ['green', 'orange', 'red', 'blue', 'gray']
                }]
            },
            options: {
                responsive: true
            }
        });
    </script>
</body>

</html>