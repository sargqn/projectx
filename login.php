<?php
session_start();
require_once 'db.php';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['user']);
    $password = trim($_POST['password']);
    if ($username === 'admin' && $password === 'Password*') {
        $_SESSION['user'] = 'admin';
        $_SESSION['role'] = 'admin';
        header('Location: admin.php');
        exit;
    }

    $stmt = $db->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();
    
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['user_id'] = $user['id'];
        header('Location: sales.php');
        exit;
    } else {
        $error = 'Неверный логин или пароль';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <form action="login.php" method="post">
        <h2>Вход в систему</h2>
        <?php if ($error): ?>
            <p class="error"><?= $error ?></p>
        <?php endif; ?>
        <input type="text" name="user" placeholder="Логин" required>
        <input type="password" name="password" placeholder="Пароль" minlength="6" required>
        <button type="submit">Войти</button>
        <p>Ещё нет аккаунта? <a href="register.php">Зарегистрироваться</a></p>
    </form>
</body>
</html>
