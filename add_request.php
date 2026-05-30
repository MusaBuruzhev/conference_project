<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}
require_once('db.php');

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $room = $_POST['room'];
    $date = $_POST['date'];

    if (empty($room) || empty($date)) {
        $error = "Заполните все поля";
    } else {
        $user_id = $_SESSION['user_id'];
        $stmt = $conn->prepare("INSERT INTO requests (user_id, room, date) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $user_id, $room, $date);
        
        if ($stmt->execute()) {
            $success = "Заявка успешно создана";
        } else {
            $error = "Ошибка при создании заявки";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Новая заявка</title>
    <meta charset="UTF-8">
</head>
<body>
    <h1>Новая заявка на помещение</h1>
    <a href="my_requests.php">Назад к заявкам</a>

    <?php if ($error): ?>
        <p style="color:red"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <?php if ($success): ?>
        <p style="color:green"><?= htmlspecialchars($success) ?></p>
    <?php endif; ?>

    <form method="POST">
        <select name="room" required>
            <option value="">-- Выберите помещение --</option>
            <option value="auditorium">Аудитория</option>
            <option value="coworking">Коворкинг</option>
            <option value="cinema">Кинозал</option>
        </select><br>
        <input type="date" name="date" required><br>
        <button type="submit">Отправить заявку</button>
    </form>
</body>
</html>