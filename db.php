<?php
$host = 'localhost'; // Хост БД
$dbname = 'pyterochka'; // Имя БД
$username = 'root'; // Имя пользователя БД
$password = ''; // Пароль БД (пустой по умолчанию в XAMPP)

try {
    $db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Ошибка подключения к БД: " . $e->getMessage());
}
