<?php
session_start();
include "koneksi.php";

/*
=====================================
LOGIN AMAN SESSION PHP
ASIA COMPUTER
=====================================
*/

/*
Database users contoh:

username : admin
password : admin123

Password harus di-hash:
password_hash('admin123', PASSWORD_DEFAULT);
*/

if (isset($_POST['login'])) {

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        $error = "Username dan Password wajib diisi!";
    } else {

        $query = mysqli_prepare($conn, "SELECT id, username, password, role FROM users WHERE username = ?");
        mysqli_stmt_bind_param($query, "s", $username);
        mysqli_stmt_execute($query);

        $result = mysqli_stmt_get_result($query);
        $user = mysqli_fetch_assoc($result);

        if ($user) {

            if (password_verify($password, $user['password'])) {

                session_regenerate_id(true);

                $_SESSION['login'] = true;
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];

                header("Location: admin.php");
                exit;

            } else {
                $error = "Password salah!";
            }

        } else {
            $error = "Username tidak ditemukan!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login Admin - Asia Computer</title>

<style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:Arial, sans-serif;
}

body{
    background:#050505;
    display:flex;
    justify-content:center;
    align-items:center;
    height:100vh;
    color:#fff;
}

.login-box{
    width:400px;
    background:#111;
    padding:40px;
    border-radius:20px;
    border:1px solid #5A1E1E;
    box-shadow:0 10px 30px rgba(0,0,0,0.3);
}

h2{
    text-align:center;
    margin-bottom:30px;
    color:#C00000;
}

input{
    width:100%;
    padding:15px;
    margin-bottom:15px;
    border:none;
    border-radius:10px;
    font-size:15px;
}

button{
    width:100%;
    padding:15px;
    background:#C00000;
    color:#fff;
    border:none;
    border-radius:10px;
    font-size:16px;
    font-weight:bold;
    cursor:pointer;
}

button:hover{
    opacity:0.9;
}

.error{
    background:#2b0000;
    color:#ffb3b3;
    padding:12px;
    border-radius:10px;
    margin-bottom:15px;
    text-align:center;
}

.footer{
    text-align:center;
    margin-top:20px;
    font-size:14px;
    color:#aaa;
}
</style>
</head>
<body>

<div class="login-box">

    <h2>Admin Login</h2>

    <?php if (!empty($error)) : ?>
        <div class="error">
            <?php echo $error; ?>
        </div>
    <?php endif; ?>

    <form method="POST">

        <input
            type="text"
            name="username"
            placeholder="Username Admin"
            required
        >

        <input
            type="password"
            name="password"
            placeholder="Password"
            required
        >

        <button type="submit" name="login">
            Login Sekarang
        </button>

    </form>

    <div class="footer">
        Asia Computer • Secure Admin Panel
    </div>

</div>

</body>
</html>
