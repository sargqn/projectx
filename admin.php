<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_sale'])) {
    $id = intval($_POST['id']);
    $amount = floatval($_POST['amount']);
    $date = $_POST['date'];
    
    $stmt = $db->prepare("UPDATE sales SET amount = ?, date = ? WHERE id = ?");
    $stmt->execute([$amount, $date, $id]);
}

$sales = $db->query("SELECT s.*, u.fio FROM sales s JOIN users u ON s.user_id = u.id ORDER BY s.date DESC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Админ-панель</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Админ-панель</h1>
    <p>Вы вошли как администратор: <?= $_SESSION['user'] ?> (<a href="logout.php">Выйти</a>)</p>
    <h2>Все продажи</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Продавец</th>
            <th>Сумма</th>
            <th>Дата</th>
            <th>Статус</th>
            <th>Действия</th>
        </tr>
        <?php foreach ($sales as $sale): ?>
        <tr>
            <form action="admin.php" method="post">
                <td><?= $sale['id'] ?></td>
                <td><?= $sale['fio'] ?></td>
                <td><input type="number" name="amount" step="0.01" value="<?= $sale['amount'] ?>" required></td>
                <td><input type="date" name="date" value="<?= $sale['date'] ?>" required></td>
                <td><?= $sale['status'] ?></td>
                <td>
                    <input type="hidden" name="id" value="<?= $sale['id'] ?>">
                    <button type="submit" name="update_sale">Обновить</button>
                </td>
            </form>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
