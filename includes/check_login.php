<?php
session_start();
if (!isset($_SESSION['admin_logged'])) {
    header("Location: /admin/login.php"); // ✅ Đúng: luôn chạy về đúng file

    exit;
}
