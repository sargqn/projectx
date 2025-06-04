<?php
require_once 'db.php';
$errors = [];
$success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['user']);
    $password = trim($_POST['password']);
    $fio = trim($_POST['fio']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    if (empty($username)) $errors[] = 'Логин обязателен';
    if (strlen($password) < 6) $errors[] = 'Пароль должен быть не менее 6 символов';
    if (!preg_match('/[A-Z]/', $password)) $errors[] = 'Пароль должен содержать хотя бы одну заглавную букву';
    if (!preg_match('/[!@#$%^&*(),.?":{}|<>]/', $password)) $errors[] = 'Пароль должен содержать хотя бы один спецсимвол';
    if (empty($fio)) $errors[] = 'ФИО обязательно';
    if (!preg_match('/^[а-яА-ЯёЁ\s]+$/u', $fio)) $errors[] = 'ФИО должно содержать только кириллицу и пробелы';
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Введите корректный email';
    if (empty($phone) || !preg_match('/^\+7\(\d{3}\)-\d{3}-\d{2}-\d{2}$/', $phone)) {
        $errors[] = 'Телефон должен быть в формате +7(XXX)-XXX-XX-XX';
    }
    $stmt = $db->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->execute([$username]);
    if ($stmt->fetch()) $errors[] = 'Этот логин уже занят';
    if (empty($errors)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $db->prepare("INSERT INTO users (username, password, fio, email, phone, role) VALUES (?, ?, ?, ?, ?, 'user')");
        $stmt->execute([$username, $hashedPassword, $fio, $email, $phone]);
        $success = 'Регистрация успешна! Теперь вы можете <a href="login.php">войти</a>.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <form action="register.php" method="post">
        <h2>Регистрация</h2>
        
        <?php if (!empty($errors)): ?>
            <?php foreach ($errors as $error): ?>
                <p class="error"><?= $error ?></p>
            <?php endforeach; ?>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <p class="success"><?= $success ?></p>
        <?php endif; ?>
        
        <input type="text" name="user" placeholder="Логин" required>
        <input type="password" name="password" placeholder="Пароль (мин. 6 символов, заглавная буква и спецсимвол)" required>
        <input type="text" name="fio" placeholder="ФИО (только кириллица)" pattern="[А-Яа-яЁё\s]+" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="tel" name="phone" placeholder="Телефон +7(XXX)-XXX-XX-XX" pattern="\+7\(\d{3}\)-\d{3}-\d{2}-\d{2}" required>
        <select name="position" required>
            <option value="" disabled selected>Выберите должность</option>
            <option value="Пользователь">Пользователь</option>
            <option value="Администратор">Администратор</option>
        </select>
        
        <button type="submit">Зарегистрироваться</button>
        <p>Уже есть аккаунт? <a href="login.php">Войти</a></p>
    </form>
</body>
</html>
