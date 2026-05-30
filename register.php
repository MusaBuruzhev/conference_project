<?php
require_once('db.php');
session_start();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fio = trim($_POST['fio']);
    $phone = trim($_POST['phone']);
    $email = trim($_POST['email']);
    $login = trim($_POST['login']);
    $password = $_POST['password'];
    $password_confirm = $_POST['password_confirm'];

    if (empty($fio) || empty($phone) || empty($email) || empty($login) || empty($password)) {
        $error = "Заполните все поля";
    }
    elseif (strlen($login) < 6) {
        $error = "Логин должен быть минимум 6 символов";
    }
    elseif (!preg_match('/^[a-zA-Z0-9]+$/', $login)) {
        $error = "Логин может содержать только латинские буквы и цифры";
    }
    elseif (strlen($password) < 8) {
        $error = "Пароль должен быть минимум 8 символов";
    }
    elseif ($password !== $password_confirm) {
        $error = "Пароли не совпадают";
    }
    else {
        $stmt = $conn->prepare("SELECT id FROM users WHERE login = ?");
        $stmt->bind_param("s", $login);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $error = "Пользователь с таким логином уже существует";
        } else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (fio, phone, email, login, password) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $fio, $phone, $email, $login, $hashed);
            
            if ($stmt->execute()) {
                $success = "Регистрация успешна! Теперь вы можете войти.";
            } else {
                $error = "Ошибка при регистрации: " . $conn->error;
            }
        }
    }
}

require_once 'header.php';
?>

<h1 class="page-title">Регистрация</h1>

<?php if ($error): ?>
    <div class="page-alert page-alert--error"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<?php if ($success): ?>
    <div class="page-alert page-alert--success"><?= htmlspecialchars($success) ?></div>
<?php endif; ?>

<form class="form-card" method="POST">
    <label class="form-group">
        <span>ФИО</span>
        <input type="text" name="fio" placeholder="Введите ФИО" required>
    </label>
    <label class="form-group">
        <span>Телефон</span>
        <input type="text" name="phone" placeholder="Введите телефон" required>
    </label>
    <label class="form-group">
        <span>Email</span>
        <input type="email" name="email" placeholder="Введите email" required>
    </label>
    <label class="form-group">
        <span>Логин</span>
        <input type="text" name="login" placeholder="Латиница и цифры, минимум 6" required>
    </label>
    <label class="form-group">
        <span>Пароль</span>
        <input type="password" name="password" placeholder="Минимум 8 символов" required>
    </label>
    <label class="form-group">
        <span>Подтверждение пароля</span>
        <input type="password" name="password_confirm" placeholder="Повторите пароль" required>
    </label>
    <button type="submit">Зарегистрироваться</button>
</form>

<div class="page-actions">
    <a class="button-link secondary" href="login.php">Уже есть аккаунт? Войти</a>
</div>

<?php require_once 'footer.php'; ?>