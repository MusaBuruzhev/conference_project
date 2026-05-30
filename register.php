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
?>

<!DOCTYPE html>
<html>
<head>
    <title>Регистрация</title>
    <meta charset="UTF-8">
</head>
<body>
    <?php if ($error): ?>
        <p style="color:red"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <?php if ($success): ?>
        <p style="color:green"><?= htmlspecialchars($success) ?></p>
    <?php endif; ?>
    
    <form method="POST">
        <input type="text" name="fio" placeholder="ФИО" required><br>
        <input type="text" name="phone" placeholder="Телефон" required><br>
        <input type="email" name="email" placeholder="Email" required><br>
        <input type="text" name="login" placeholder="Логин (латиница+цифры, мин 6)" required><br>
        <input type="password" name="password" placeholder="Пароль (мин 8)" required><br>
        <input type="password" name="password_confirm" placeholder="Подтвердите пароль" required><br>
        <button type="submit">Зарегистрироваться</button>
    </form>
    <a href="login.php">Уже есть аккаунт? Войти</a>
</body>
</html>