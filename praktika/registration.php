<?php
$pageTitle = 'Регистрация студента';
require_once "db/db.php";

$errors = [];
$success = "";
$surname = $name = $otchestvo = $group_id = $student_card = $email = $username = $password = '';

$groups = getGroups();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $surname = trim($_POST['surname'] ?? '');
    $name = trim($_POST['name'] ?? '');
    $otchestvo = trim($_POST['otchestvo'] ?? '');
    $group_id = (int)($_POST['group_id'] ?? 0);
    $student_card = trim($_POST['student_card'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    
    if (empty($surname)) $errors[] = "Фамилия обязательна для заполнения";
    if (empty($name)) $errors[] = "Имя обязательно для заполнения";
    if (empty($otchestvo)) $errors[] = "Отчество обязательно для заполнения";
    if (empty($group_id)) $errors[] = "Группа обязательна для выбора";
    if (empty($email)) $errors[] = "Email обязателен для заполнения";
    if (empty($username)) $errors[] = "Логин обязателен для заполнения";
    if (empty($password)) $errors[] = "Пароль обязателен для заполнения";
    
    if (!empty($username)) {
        $check_username = mysqli_query($db, "SELECT id_user FROM user WHERE username = '$username'");
        if (mysqli_num_rows($check_username) > 0) {
            $errors[] = "Пользователь с таким логином уже существует";
        }
    }
    
    if (!empty($email)) {
        $check_email = mysqli_query($db, "SELECT id_user FROM user WHERE email = '$email'");
        if (mysqli_num_rows($check_email) > 0) {
            $errors[] = "Пользователь с таким email уже существует";
        }
    }
    
    if (empty($errors)) {
        $hashed_password = md5($password);
        $sql = "INSERT INTO user (user_type_id, surname, name, otchestvo, group_id, student_card, email, username, password) 
                VALUES (1, '$surname', '$name', '$otchestvo', '$group_id', '$student_card', '$email', '$username', '$hashed_password')";
        
        if (mysqli_query($db, $sql)) {
            $success = "Регистрация прошла успешно! Теперь вы можете войти в систему.";
            $surname = $name = $otchestvo = $student_card = $email = $username = $password = '';
            $group_id = 0;
        } else {
            $errors[] = "Ошибка при регистрации: " . mysqli_error($db);
        }
    }
}

ob_start();
?>

<?php if (!empty($errors)): ?>
    <div class="error">
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?php echo htmlspecialchars($error); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<?php if (!empty($success)): ?>
    <div class="success">
        <?php echo htmlspecialchars($success); ?>
    </div>
<?php endif; ?>

<form method="POST" action="">
    <div>
        <label for="surname">Фамилия *</label>
        <input type="text" name="surname" id="surname" value="<?php echo htmlspecialchars($surname); ?>" required>
    </div>
    
    <div>
        <label for="name">Имя *</label>
        <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($name); ?>" required>
    </div>
    
    <div>
        <label for="otchestvo">Отчество *</label>
        <input type="text" name="otchestvo" id="otchestvo" value="<?php echo htmlspecialchars($otchestvo); ?>" required>
    </div>
    
    <div>
        <label for="group_id">Группа *</label>
        <select name="group_id" id="group_id" required>
            <option value="">-- Выберите группу --</option>
            <?php foreach ($groups as $group): ?>
                <option value="<?php echo $group['id_group']; ?>" <?php echo $group_id == $group['id_group'] ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($group['group_name']); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    
    <div>
        <label for="student_card">Номер студенческого билета</label>
        <input type="text" name="student_card" id="student_card" value="<?php echo htmlspecialchars($student_card); ?>">
    </div>
    
    <div>
        <label for="email">Email *</label>
        <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($email); ?>" required>
    </div>
    
    <div>
        <label for="username">Логин *</label>
        <input type="text" name="username" id="username" value="<?php echo htmlspecialchars($username); ?>" required>
    </div>
    
    <div>
        <label for="password">Пароль *</label>
        <input type="password" name="password" id="password" value="<?php echo htmlspecialchars($password); ?>" required>
    </div>
    
    <button type="submit">Зарегистрироваться</button>
</form>

<p>Уже есть аккаунт? <a href="index.php">Войдите здесь</a></p>

<?php
$pageContent = ob_get_clean();
require_once "struktura.php";
?>