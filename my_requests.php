<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}
require_once('db.php');

$user_id = $_SESSION['user_id'];
$roomNames = [
    'auditorium' => 'Аудитория',
    'coworking' => 'Коворкинг',
    'cinema' => 'Кинозал'
];

$stmt = $conn->prepare("SELECT * FROM requests WHERE user_id = ? ORDER BY id DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

require_once 'header.php';
?>

<h1 class="page-title">Привет, <?= htmlspecialchars($_SESSION['user']) ?>!</h1>
<div class="page-actions">
    <a class="button-link" href="add_request.php">+ Новая заявка</a>
    <a class="button-link secondary" href="logout.php">Выйти</a>
</div>


<section class="card">
    <h2>Мои заявки</h2>
    <?php if ($result->num_rows > 0): ?>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Помещение</th>
                        <th>Дата</th>
                        <th>Статус</th>
                        <th>Отзыв</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= htmlspecialchars($roomNames[$row['room']] ?? $row['room']) ?></td>
                        <td><?= htmlspecialchars($row['date']) ?></td>
                        <td><span class="status-pill"><?= htmlspecialchars($row['status']) ?></span></td>
                        <td>
                            <?php if ($row['status'] == 'Мероприятие завершено'): ?>
                                <form class="review-form" method="POST" action="add_review.php">
                                    <input type="hidden" name="request_id" value="<?= $row['id'] ?>">
                                    <textarea name="review" rows="2" placeholder="Ваш отзыв" required></textarea>
                                    <button type="submit">Отправить</button>
                                </form>
                            <?php else: ?>
                                —
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p>У вас пока нет заявок. <a href="add_request.php">Создать заявку</a></p>
    <?php endif; ?>
</section>

<?php require_once 'footer.php'; ?>