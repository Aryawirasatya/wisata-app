<?php
session_start();
require_once 'function.php';

// Memeriksa apakah user sudah login, jika sudah, redirect ke dashboard atau halaman lainnya
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

// Memeriksa jika form login disubmit
if (isset($_POST['submit_login'])) {
    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);

    // Memeriksa apakah username ada di database
    $user = query("SELECT * FROM users WHERE username = '$username'");

    // Jika user ditemukan dan password cocok
    if ($user && password_verify($password, $user[0]['password'])) {
        // Menyimpan user_id di session
        $_SESSION['user_id'] = $user[0]['id'];
        $_SESSION['username'] = $user[0]['username'];
        $_SESSION['role'] = $user[0]['role'];
        
        // Redirect ke halaman dashboard atau halaman utama
        header("Location: index.php");
        exit;
    } else {
        $error_message = "Username atau password salah.";
    }
}
?>

<!-- Form Login -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f7f9fc;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .card {
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .form-control:focus {
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
            border-color: #007bff;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <div class="card p-4">
                    <div class="text-center mb-4">
                        <h2 class="fw-bold">Login</h2>
                        <p class="text-muted">Masuk untuk melanjutkan</p>
                    </div>
                    <form action="" method="POST">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" placeholder="Masukkan username" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan password" required>
                        </div>
                        <button type="submit" name="submit_login" class="btn btn-primary w-100">Login</button>
                        <?php if (isset($error_message)) : ?>
                            <div class="alert alert-danger mt-3"><?= $error_message ?></div>
                        <?php endif; ?>
                    </form>
                    <div class="text-center mt-3">
                        <p class="mb-0">Belum punya akun? <a href="register.php" class="text-primary">Daftar sekarang</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
