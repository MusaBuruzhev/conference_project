<?php
session_start();
require_once('db.php');

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = $_POST['login'];
    $password = $_POST['password'];

    if (empty($login) || empty($password)) {
        $error = "Заполните все поля";
    } else {
        $stmt = $conn->prepare("SELECT * FROM users WHERE login = ?");
        $stmt->bind_param("s", $login);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION['user'] = $user['login'];
                $_SESSION['user_id'] = $user['id'];
                
                if ($login === 'Admin26' && $password === 'Demo20') {
                    $_SESSION['is_admin'] = true;
                } else {
                    $_SESSION['is_admin'] = false;
                }
                
                header('Location: my_requests.php');
                exit;
            } else {
                $error = "Неверный логин или пароль";
            }
        } else {
            $error = "Неверный логин или пароль";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Вход</title>
    <meta charset="UTF-8">
</head>
<body>
    <?php if ($error): ?>
        <p style="color:red"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    
    <form method="POST">
        <input type="text" name="login" placeholder="Логин" required><br>
        <input type="password" name="password" placeholder="Пароль" required><br>
        <button type="submit">Войти</button>
    </form>
    <a href="register.php">Нет аккаунта? Зарегистрируйтесь</a>
</body>
</html>