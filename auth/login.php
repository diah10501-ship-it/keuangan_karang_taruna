<?php
session_start();

include '../config/koneksi.php';

if (isset($_SESSION['login'])) {
    header("Location: ../dashboard.php");
    exit;
}

$error = "";

if (isset($_POST['login'])) {

    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = md5($_POST['password']);

    $query = mysqli_query(
        $conn,
        "SELECT * FROM users
         WHERE username='$username'
         AND password='$password'"
    );

    if (mysqli_num_rows($query) > 0) {

        $data = mysqli_fetch_assoc($query);

        $_SESSION['login'] = true;
        $_SESSION['username'] = $data['username'];
        $_SESSION['nama'] = $data['nama'];

        header("Location: ../dashboard.php");
        exit;

    } else {
        $error = "Username atau password salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Login - SIKAT</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="../assets/css/style.css">

    <style>
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #198754, #0d6efd);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-card {
            width: 420px;
            background: white;
            padding: 40px;
            border-radius: 25px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
        }

        .logo-login {
            width: 75px;
            height: 75px;
            background: #198754;
            color: white;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: auto;
            font-size: 32px;
        }
    </style>
</head>

<body>

<div class="login-card">

    <div class="logo-login">
        💰
    </div>

    <div class="text-center mt-3 mb-4">

        <h3 class="fw-bold">SIKAT</h3>

        <p class="text-muted">
            Sistem Informasi Keuangan<br>
            Karang Taruna
        </p>

    </div>

    <?php if ($error != "") : ?>

        <div class="alert alert-danger">
            <?= $error; ?>
        </div>

    <?php endif; ?>

    <form method="POST">

        <div class="mb-3">

            <label class="form-label">
                Username
            </label>

            <input
                type="text"
                name="username"
                class="form-control"
                placeholder="Masukkan username"
                required
            >

        </div>

        <div class="mb-4">

            <label class="form-label">
                Password
            </label>

            <input
                type="password"
                name="password"
                class="form-control"
                placeholder="Masukkan password"
                required
            >

        </div>

        <button
            type="submit"
            name="login"
            class="btn btn-success w-100 py-2"
        >
            Masuk
        </button>

    </form>

    <div class="text-center mt-4">

        <small class="text-muted">
            SIKAT © 2026
        </small>

    </div>

</div>

</body>
</html>