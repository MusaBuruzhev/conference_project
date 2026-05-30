<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}
require_once('db.php');

$error = '';
$success = '';
$roomNames = [
    'auditorium' => 'Аудитория',
    'coworking' => 'Коворкинг',
    'cinema' => 'Кинозал'
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $room = $_POST['room'] ?? '';
    $date = trim($_POST['date']);

    if (empty($room) || empty($date) || !isset($roomNames[$room])) {
        $error = "Заполните все поля";
    } else {
        $dateObject = DateTime::createFromFormat('d.m.Y', $date);
        if (!$dateObject) {
            $error = "Введите дату в формате ДД.MM.ГГГГ";
        } else {
            $dateValue = $dateObject->format('Y-m-d');
            $user_id = $_SESSION['user_id'];
            $stmt = $conn->prepare("INSERT INTO requests (user_id, room, date) VALUES (?, ?, ?)");
            $stmt->bind_param("iss", $user_id, $room, $dateValue);
            
            if ($stmt->execute()) {
                $success = "Заявка успешно создана";
            } else {
                $error = "Ошибка при создании заявки";
            }
        }
    }
}

require_once 'header.php';
?>

<section class="page-center">
    <div class="center-card">
        <h1 class="page-title">Новая заявка на помещение</h1>

        <?php if ($error): ?>
            <div class="page-alert page-alert--error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="page-alert page-alert--success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <form class="form-card" method="POST">
            <label class="form-group">
                <span>Выберите помещение</span>
                <select name="room" required>
                    <option value="">-- Выберите помещение --</option>
                    <option value="auditorium">Аудитория</option>
                    <option value="coworking">Коворкинг</option>
                    <option value="cinema">Кинозал</option>
                </select>
            </label>
            <label class="form-group">
                <span>Дата начала</span>
                <input type="text" name="date" placeholder="ДД.MM.ГГГГ" required>
            </label>
            <button type="submit">Отправить заявку</button>
        </form>

        <div class="page-actions">
            <a class="button-link secondary" href="my_requests.php">Назад к заявкам</a>
        </div>
    </div>
</section>

<?php require_once 'footer.php'; ?>