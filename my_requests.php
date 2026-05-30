<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}
require_once('db.php');

$user_id = $_SESSION['user_id'];

// Получаем заявки пользователя
$stmt = $conn->prepare("SELECT * FROM requests WHERE user_id = ? ORDER BY id DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Личный кабинет</title>
    <meta charset="UTF-8">
</head>
<body>
    <h1>Привет, <?= htmlspecialchars($_SESSION['user']) ?>!</h1>
    <a href="add_request.php">+ Новая заявка</a> | <a href="logout.php">Выйти</a>

    <h2>Мои заявки</h2>
    <?php if ($result->num_rows > 0): ?>
        <table border="1">
            <tr>
                <th>ID</th>
                <th>Помещение</th>
                <th>Дата</th>
                <th>Статус</th>
                <th>Отзыв</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['room']) ?></td>
                <td><?= $row['date'] ?></td>
                <td><?= htmlspecialchars($row['status']) ?></td>
                <td>
                    <?php if ($row['status'] == 'Мероприятие завершено'): ?>
                        <form method="POST" action="add_review.php">
                            <input type="hidden" name="request_id" value="<?= $row['id'] ?>">
                            <textarea name="review" rows="2" cols="30" placeholder="Ваш отзыв" required></textarea>
                            <button type="submit">Оставить отзыв</button>
                        </form>
                    <?php else: ?>
                        —
                    <?php endif; ?>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>У вас пока нет заявок. <a href="add_request.php">Создать заявку</a></p>
    <?php endif; ?>
</body>
</html>