<?php
$pageTitle = 'Регистрация';
require_once "struktura.php";

// Обработка формы регистрации
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Получаем данные из формы
    $login = trim($_POST['login'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $surname = trim($_POST['surname'] ?? '');
    $name = trim($_POST['name'] ?? '');
    $otchestvo = trim($_POST['otchestvo'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    
    $errors = [];
    
    // Проверка на пустые поля
    if (empty($login)) $errors[] = "Логин обязателен для заполнения";
    if (empty($password)) $errors[] = "Пароль обязателен для заполнения";
    if (empty($surname)) $errors[] = "Фамилия обязательна для заполнения";
    if (empty($name)) $errors[] = "Имя обязательно для заполнения";
    if (empty($otchestvo)) $errors[] = "Отчество обязательно для заполнения";
    if (empty($phone)) $errors[] = "Телефон обязателен для заполнения";
    if (empty($email)) $errors[] = "Email обязателен для заполнения";
    
    // Проверка уникальности логина
    if (!empty($login)) {
        $check_login = mysqli_query($db, "SELECT id_user FROM user WHERE username = '$login'");
        if (mysqli_num_rows($check_login) > 0) {
            $errors[] = "Пользователь с таким логином уже существует";
        }
    }
    
    // Если ошибок нет - регистрируем пользователя
    if (empty($errors)) {
        // user_type_id = 1 - обычный пользователь
        $sql = "INSERT INTO user (user_type_id, surname, name, otchestvo, phone, email, username, password) 
                VALUES ('1', '$surname', '$name', '$otchestvo', '$phone', '$email', '$login', MD5('$password'))";
        
        if (mysqli_query($db, $sql)) {
            $success = "Регистрация прошла успешно! Теперь вы можете войти в систему.";
            // Очищаем поля формы после успешной регистрации
            $login = $password = $surname = $name = $otchestvo = $phone = $email = '';
        } else {
            $errors[] = "Ошибка при регистрации: " . mysqli_error($db);
        }
    }
}

// Если пользователь уже авторизован, перенаправляем его
if (isset($_SESSION['user'])) {
    header('Location: index.php');
    exit();
}
?>

<main>    
    
    <?php if (!empty($errors)): ?>
        <div class="error-message">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
    
    <?php if (isset($success)): ?>
        <div class="success-message">
            <?php echo htmlspecialchars($success); ?>
        </div>
    <?php endif; ?>
    
    <form method="POST" action="">
        <div class="form-group">
            <label>Логин *</label>
            <input type="text" name="login" value="<?php echo htmlspecialchars($login ?? ''); ?>" required>
        </div>
        
        <div class="form-group">
            <label>Пароль *</label>
            <input type="password" name="password" value="<?php echo htmlspecialchars($password ?? ''); ?>" required>
        </div>
        
        <div class="form-group">
            <label>Фамилия *</label>
            <input type="text" name="surname" value="<?php echo htmlspecialchars($surname ?? ''); ?>" required>
        </div>
        
        <div class="form-group">
            <label>Имя *</label>
            <input type="text" name="name" value="<?php echo htmlspecialchars($name ?? ''); ?>" required>
        </div>
        
        <div class="form-group">
            <label>Отчество *</label>
            <input type="text" name="otchestvo" value="<?php echo htmlspecialchars($otchestvo ?? ''); ?>" required>
        </div>
        
        <div class="form-group">
            <label>Телефон *</label>
            <input type="tel" name="phone" value="<?php echo htmlspecialchars($phone ?? ''); ?>" required>
        </div>
        
        <div class="form-group">
            <label>Email *</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($email ?? ''); ?>" required>
        </div>
        
        <button type="submit" class="btn-register">Зарегистрироваться</button>
    </form>
    
    <div class="login-link">
        <p>Уже есть аккаунт? <a href="index.php">Войдите здесь</a></p>
    </div>
</main>

<style>
.form-group {
    margin-bottom: 15px;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
}

.form-group input {
    width: 100%;
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 4px;
    box-sizing: border-box;
}

.btn-register {
    background-color: #4CAF50;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
}

.btn-register:hover {
    background-color: #45a049;
}

.error-message {
    background-color: #ffebee;
    color: #c62828;
    padding: 15px;
    border-radius: 4px;
    margin-bottom: 20px;
    border-left: 4px solid #c62828;
}

.success-message {
    background-color: #e8f5e8;
    color: #2e7d32;
    padding: 15px;
    border-radius: 4px;
    margin-bottom: 20px;
    border-left: 4px solid #2e7d32;
}

.login-link {
    margin-top: 20px;
    text-align: center;
}
</style>