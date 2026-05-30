<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
    <title>Конференции.РФ</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container">
        <header class="site-header">
            <a class="brand" href="index.php">Конференции.РФ</a>
            <nav class="site-nav">
                <a href="index.php">Главная</a>
                <?php if (!empty($_SESSION['user'])): ?>
                    <?php if (!empty($_SESSION['is_admin']) && $_SESSION['is_admin'] === true): ?>
                        <a href="admin.php">Админ</a>
                    <?php else: ?>
                        <a href="my_requests.php">Мои заявки</a>
                        <a href="add_request.php">Заявка</a>
                    <?php endif; ?>
                    <a href="logout.php">Выйти</a>
                <?php else: ?>
                    <a href="login.php">Войти</a>
                    <a href="register.php">Регистрация</a>
                <?php endif; ?>
            </nav>
        </header>
        <main class="page-main">