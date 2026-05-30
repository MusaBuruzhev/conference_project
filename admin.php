<?php
session_start();
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header('Location: my_requests.php');
    exit;
}
require_once('db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['request_id']) && isset($_POST['status'])) {
    $request_id = $_POST['request_id'];
    $status = $_POST['status'];
    
    $stmt = $conn->prepare("UPDATE requests SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $request_id);
    $stmt->execute();
    
    header('Location: admin.php');
    exit;
}

$sql = "SELECT requests.*, users.login 
        FROM requests 
        JOIN users ON requests.user_id = users.id 
        ORDER BY requests.id DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Админ-панель</title>
    <meta charset="UTF-8">
</head>
<body>
    <h1>Админ-панель</h1>
    <a href="my_requests.php">Мои заявки</a> | <a href="logout.php">Выйти</a>

    <h2>Все заявки</h2>
    <?php if ($result->num_rows > 0): ?>
        <table border="1">
            <tr>
                <th>ID</th>
                <th>Пользователь</th>
                <th>Помещение</th>
                <th>Дата</th>
                <th>Статус</th>
                <th>Действие</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['login']) ?></td>
                <td><?= htmlspecialchars($row['room']) ?></td>
                <td><?= $row['date'] ?></td>
                <td><?= htmlspecialchars($row['status']) ?></td>
                <td>
                    <form method="POST">
                        <input type="hidden" name="request_id" value="<?= $row['id'] ?>">
                        <select name="status">
                            <option value="Новая" <?= $row['status'] == 'Новая' ? 'selected' : '' ?>>Новая</option>
                            <option value="Мероприятие назначено" <?= $row['status'] == 'Мероприятие назначено' ? 'selected' : '' ?>>Мероприятие назначено</option>
                            <option value="Мероприятие завершено" <?= $row['status'] == 'Мероприятие завершено' ? 'selected' : '' ?>>Мероприятие завершено</option>
                        </select>
                        <button type="submit">Обновить</button>
                    </form>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>Заявок пока нет</p>
    <?php endif; ?>
</body>
</html>