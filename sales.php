<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}
// Добавление новой продажи
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_sale'])) {
    $amount = floatval($_POST['amount']);
    $date = $_POST['date'];
    $status = $_POST['status'];
    $other_status = $_POST['other_status'] ?? null;
    
    if (!empty($other_status)) {
        $status = $other_status;
    }
    
    $stmt = $db->prepare("INSERT INTO sales (user_id, amount, date, status) VALUES (?, ?, ?, ?)");
    $stmt->execute([$_SESSION['user_id'], $amount, $date, $status]);
}
// Получение продаж текущего пользователя
$stmt = $db->prepare("SELECT * FROM sales WHERE user_id = ? ORDER BY date DESC");
$stmt->execute([$_SESSION['user_id']]);
$sales = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Продажи \ Пятерочка</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Мои продажи</h1>
    <p>Вы вошли как: <?= $_SESSION['user'] ?> (<a href="logout.php">Выйти</a>)</p>
    
    <h2>Добавить новую продажу</h2>
    <form action="sales.php" method="post">
        <input type="number" name="amount" step="0.01" placeholder="Сумма" required>
        <input type="date" name="date" required value="<?= date('Y-m-d') ?>">
        
        <select name="status" required>
            <option value="Завершена">Завершена</option>
            <option value="Возврат">Возврат</option>
            <option value="Отменена">Отменена</option>
            <option value="other">Иной статус</option>
        </select>
        
        <input type="text" name="other_status" placeholder="Укажите статус" style="display: none;">
        
        <button type="submit" name="add_sale">Добавить продажу</button>
    </form>
    
    <h2>История продаж</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Сумма</th>
            <th>Дата</th>
            <th>Статус</th>
        </tr>
        <?php foreach ($sales as $sale): ?>
        <tr>
            <td><?= $sale['id'] ?></td>
            <td><?= number_format($sale['amount'], 2) ?> ₽</td>
            <td><?= $sale['date'] ?></td>
            <td><?= $sale['status'] ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
    
    <script>
        // Показываем поле для другого статуса при выборе "Иной статус"
        document.querySelector('select[name="status"]').addEventListener('change', function() {
            const otherField = document.querySelector('input[name="other_status"]');
            otherField.style.display = this.value === 'other' ? 'block' : 'none';
            if (this.value !== 'other') otherField.value = '';
        });
    </script>
</body>
</html>