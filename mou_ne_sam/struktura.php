<?php
require_once "db/db.php"; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Мой не сам  <?php echo $pageTitle; ?></title>
    <link rel='icon' href='images/1.jpeg'>
    <link rel='stylesheet' href='style/style.css'>
</head>
<body>
    <header>
        <img src='images/1.jpeg' alt='логотип'>
        <h1>мой не сам</h1>
    </header>

    <nav>
        <a href="index.php">Авторизация</a>
        <a href="registration.php">Регистрация</a>
        <a href="create_zayavka.php">Создать заявку</a>
        <a href="zayavka.php">Список заявок</a>
        <a href="admin.php">Панель администратора</a>
    </nav>

    <main>
        <?php echo $pageHeading;?>
        <div class="content">
            <?php echo $pageContent ?? '';?>
        </div>
        <footer>
            <h3>2025</h3>
        </footer>
    </main>

    <script src="script/script.js"></script>
</body>
</html>