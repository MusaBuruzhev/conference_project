<?php
session_start();
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header('Location: my_requests.php');
    exit;
}
require_once('db.php');

$roomNames = [
    'auditorium' => 'Аудитория',
    'coworking' => 'Коворкинг',
    'cinema' => 'Кинозал'
];

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

require_once 'header.php';
?>

<h1 class="page-title">Админ-панель</h1>
<div class="page-actions">
    <a class="button-link" href="my_requests.php">Мои заявки</a>
    <a class="button-link secondary" href="logout.php">Выйти</a>
</div>

<section class="card">
    <h2>Все заявки</h2>
    <?php if ($result->num_rows > 0): ?>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Пользователь</th>
                        <th>Помещение</th>
                        <th>Дата</th>
                        <th>Статус</th>
                        <th>Действие</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= htmlspecialchars($row['login']) ?></td>
                        <td><?= htmlspecialchars($roomNames[$row['room']] ?? $row['room']) ?></td>
                        <td><?= htmlspecialchars($row['date']) ?></td>
                        <td><span class="status-pill"><?= htmlspecialchars($row['status']) ?></span></td>
                        <td>
                            <form class="admin-form" method="POST">
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
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p>Заявок пока нет</p>
    <?php endif; ?>
</section>

<?php require_once 'footer.php'; ?>