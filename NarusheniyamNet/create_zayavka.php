<?php
$pageTitle = "Сообщить о нарушении";
require_once "db/db.php";

if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

$user = $_SESSION['user'];
$error = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $car_number = mysqli_real_escape_string($db, $_POST['car_number']);
    $violation_description = mysqli_real_escape_string($db, $_POST['violation_description']);
    $data = mysqli_real_escape_string($db, $_POST['data']);
    $time = mysqli_real_escape_string($db, $_POST['time']);
    
    if (!empty($car_number) && !empty($violation_description) && !empty($data) && !empty($time)) {
        $query = "INSERT INTO `service` (`car_number`, `violation_description`, `user_id`, `data`, `time`, `status_id`) 
                  VALUES ('$car_number', '$violation_description', '{$user['id_user']}', '$data', '$time', '1')";
        
        if (mysqli_query($db, $query)) {
            $success = "Заявление о нарушении успешно отправлено!";
            $_POST = array();
        } else {
            $error = "Ошибка при отправке заявления: " . mysqli_error($db);
        }
    } else {
        $error = "Все поля обязательны для заполнения!";
    }
}

ob_start();
?>

<?php if ($error): ?>
    <div class="error"><?php echo $error; ?></div>
<?php endif; ?>

<?php if ($success): ?>
    <div class="success"><?php echo $success; ?></div>
<?php endif; ?>

<h2>Сообщить о нарушении ПДД</h2>
<form method="POST" action="">
    <div>
        <label for="car_number">Номер автомобиля *</label>
        <input type="text" id="car_number" name="car_number" required 
               placeholder="Например: А123БВ777" 
               value="<?php echo isset($_POST['car_number']) ? htmlspecialchars($_POST['car_number']) : ''; ?>">
    </div>
    
    <div>
        <label for="violation_description">Описание нарушения *</label>
        <textarea id="violation_description" name="violation_description" required 
                  placeholder="Подробно опишите нарушение ПДД..." 
                  rows="4"><?php echo isset($_POST['violation_description']) ? htmlspecialchars($_POST['violation_description']) : ''; ?></textarea>
    </div>
    
    <div>
        <label for="data">Дата нарушения *</label>
        <input type="date" id="data" name="data" required 
               value="<?php echo isset($_POST['data']) ? htmlspecialchars($_POST['data']) : ''; ?>">
    </div>
    
    <div>
        <label for="time">Время нарушения *</label>
        <input type="time" id="time" name="time" required 
               value="<?php echo isset($_POST['time']) ? htmlspecialchars($_POST['time']) : ''; ?>">
    </div>
    
    <button type="submit">Отправить заявление</button>
</form>
<p><a href="zayavka.php">Вернуться к списку заявлений</a></p>

<?php
$pageContent = ob_get_clean();
require_once "struktura.php";
?>