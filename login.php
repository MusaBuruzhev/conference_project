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
                    header('Location: admin.php');
                } else {
                    $_SESSION['is_admin'] = false;
                    header('Location: my_requests.php');
                }
                exit;
            } else {
                $error = "Неверный логин или пароль";
            }
        } else {
            $error = "Неверный логин или пароль";
        }
    }
}

require_once 'header.php';
?>

<section class="page-center">
    <div class="center-card">
        <h1 class="page-title">Вход</h1>

        <?php if ($error): ?>
            <div class="page-alert page-alert--error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form class="form-card" method="POST">
            <label class="form-group">
                <span>Логин</span>
                <input type="text" name="login" placeholder="Введите логин" required>
            </label>
            <label class="form-group">
                <span>Пароль</span>
                <input type="password" name="password" placeholder="Введите пароль" required>
            </label>
            <button type="submit">Войти</button>
        </form>

        <div class="page-actions">
            <a class="button-link secondary" href="register.php">Еще не зарегистрированы? Регистрация</a>
        </div>
    </div>
</section>

<?php require_once 'footer.php'; ?>