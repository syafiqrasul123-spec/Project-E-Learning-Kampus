<?php
session_start();
include 'config.php';

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = mysqli_query($conn, "SELECT * FROM users WHERE email='$email' AND password='$password'");
    
    if (mysqli_num_rows($query) > 0) {
        $data = mysqli_fetch_assoc($query);
        $_SESSION['id_user'] = $data['id'];
        $_SESSION['nama'] = $data['nama'];
        $_SESSION['role'] = $data['role'];
        header("Location: index.php");
    } else {
        $error = "Email atau Password salah!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login EduLink</title>
    <link rel="stylesheet" href="style.css">
</head>
<body style="display:flex; justify-content:center; align-items:center; height:100vh;">
    <div class="container" style="width: 300px; text-align:center;">
        <h2 style="color:#154FB2;">EduLink Login</h2>
        <?php if(isset($error)) echo "<p style='color:red'>$error</p>"; ?>
        <form method="POST">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="login" style="width:100%">Masuk</button>
        </form>
    </div>
</body>
</html>