<?php
session_start();

$error = '';

if (!function_exists('callAPI')) {
    function callAPI($endpoint, $method = 'GET', $data = [], $contentType = 'json')
    {
        $baseUrl = "https://cuddly-exotic-snake.ngrok-free.app/";
        $url = $baseUrl . $endpoint;

        $headers = '';
        $content = '';

        if ($contentType === 'json') {
            $headers = "Content-Type: application/json";
            $content = json_encode($data);
        } elseif ($contentType === 'form') {
            $headers = "Content-Type: application/x-www-form-urlencoded";
            $content = http_build_query($data);
        }

        $options = [
            "http" => [
                "method"  => $method,
                "header"  => $headers,
                "timeout" => 10
            ]
        ];

        if ($method === 'POST' || $method === 'PUT') {
            $options["http"]["content"] = $content;
        }

        $context = stream_context_create($options);
        $response = @file_get_contents($url, false, $context);

        return $response ? json_decode($response, true) : [];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $data = callAPI('login', 'POST', [
        'email' => $email,
        'mat_khau' => $password
    ], 'form');

    if (isset($data['user'])) {
        $user = $data['user'];
        $roleData = callAPI('kiemTraVaiTroAdmin?ma_nguoi_dung=' . $user['ma_nguoi_dung']);

        if ($roleData && strcasecmp($roleData['vai_tro'] ?? '', 'admin') === 0) {
            $_SESSION['admin_logged'] = true;
            $_SESSION['admin_id'] = $user['ma_nguoi_dung'];
            $_SESSION['admin_email'] = $user['email'];
            $_SESSION['admin_name'] = $user['ten_nguoi_dung'] ?? ''; // SỬA LẠI Ở ĐÂY

            header("Location: index.php");
            exit;
        } else {
            $error = 'Bạn không có quyền truy cập (không phải Admin)';
        }
    } else {
        $error = $data['detail'] ?? 'Đăng nhập thất bại';
    }
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Đăng nhập</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-container {
            display: flex;
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
            overflow: hidden;
            max-width: 1000px;
            width: 100%;
            height: 600px;
        }

        .form-side {
            flex: 1;
            padding: 40px 30px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .form-side h2 {
            color: blue;
            margin-bottom: 20px;
            font-size: 28px;
        }

        .form-group {
            margin-bottom: 15px;
            text-align: left;
        }

        .form-group label {
            font-size: 14px;
            color: #333;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            border-radius: 6px;
            border: 1px solid #ccc;
            margin-top: 5px;
        }

        .btn-group {
            display: flex;
            gap: 10px;
            margin-top: 10px;
        }

        .btn-group button {
            flex: 1;
            padding: 10px;
            font-weight: bold;
            border-radius: 6px;
            border: none;
            cursor: pointer;
        }

        .btn-login {
            background: blue;
            color: white;
        }

        .image-side {
            flex: 1.2;
            position: relative;
            overflow: hidden;
        }

        .image-side img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            position: absolute;
            top: 0;
            left: 0;
            opacity: 0;
            transition: opacity 1s ease-in-out;
        }

        .image-side img.active {
            opacity: 1;
        }

        .error-message {
            color: red;
            margin-bottom: 10px;
            font-size: 14px;
        }

        @media (max-width: 768px) {
            .login-container {
                flex-direction: column;
                height: auto;
            }

            .image-side {
                height: 300px;
            }

            .form-side {
                padding: 20px;
            }
        }
    </style>
</head>

<body>

    <div class="login-container">
        <div class="form-side">
            <h2>Đăng nhập</h2>
            <?php if ($error): ?>
                <p class="error-message"><?= htmlspecialchars($error) ?></p>
            <?php endif; ?>
            <form method="post" action="" autocomplete="off">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        placeholder="Email"
                        required
                        autocomplete="off"
                        value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                        oninvalid="this.setCustomValidity('Vui lòng nhập email')"
                        oninput="this.setCustomValidity('')" />
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Password"
                        required
                        autocomplete="off"
                        oninvalid="this.setCustomValidity('Vui lòng nhập mật khẩu')"
                        oninput="this.setCustomValidity('')" />
                </div>
                <div class="btn-group">
                    <button class="btn-login" type="submit">Sign In</button>
                </div>
            </form>
        </div>

        <div class="image-side">
            <img src="https://i.pinimg.com/736x/f8/a4/18/f8a418fc1dff82471813e228caa93fbb.jpg" class="active" alt="slide 1">
            <img src="https://i.pinimg.com/736x/9a/66/98/9a66985370a996f060585fa195157cfa.jpg" alt="slide 2">
            <img src="https://i.pinimg.com/736x/f1/be/ff/f1beff5762f04e880c58356ee31fc185.jpg" alt="slide 3">
        </div>
    </div>

    <script>
        const images = document.querySelectorAll('.image-side img');
        let current = 0;

        setInterval(() => {
            images[current].classList.remove('active');
            current = (current + 1) % images.length;
            images[current].classList.add('active');
        }, 3000);
    </script>

</body>

</html>