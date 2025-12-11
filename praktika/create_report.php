<?php
$pageTitle = "Подача отчета по практике";
require_once "db/db.php";

// Проверка авторизации студента
if (!isset($_SESSION['user']) || $_SESSION['user']['user_type_id'] != 1) {
    header("Location: index.php");
    exit();
}

$user = $_SESSION['user'];
$error = "";
$success = "";

// Получаем группы и специальности
$groups = getGroups();
$specialties = getSpecialties();

// Обработка отправки формы
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Получаем и очищаем данные
    $group_id = (int)$_POST['group_id'];
    $specialty_id = (int)$_POST['specialty_id'];
    $start_date = mysqli_real_escape_string($db, $_POST['start_date']);
    $end_date = mysqli_real_escape_string($db, $_POST['end_date']);
    $organization_name = mysqli_real_escape_string($db, $_POST['organization_name']);
    $organization_address = mysqli_real_escape_string($db, $_POST['organization_address']);
    $supervisor_name = mysqli_real_escape_string($db, $_POST['supervisor_name']);
    $supervisor_position = mysqli_real_escape_string($db, $_POST['supervisor_position']);
    $work_description = mysqli_real_escape_string($db, $_POST['work_description']);
    
    // Валидация
    if (empty($group_id) || empty($specialty_id) || empty($start_date) || empty($end_date) ||
        empty($organization_name) || empty($organization_address) || empty($supervisor_name) ||
        empty($supervisor_position) || empty($work_description)) {
        $error = "Все поля обязательны для заполнения!";
    } elseif ($start_date > $end_date) {
        $error = "Дата начала не может быть позже даты окончания!";
    } else {
        // Вставляем новый отчет
        $query = "INSERT INTO practice_reports 
                  (user_id, group_id, specialty_id, start_date, end_date, organization_name, 
                   organization_address, supervisor_name, supervisor_position, work_description, status_id) 
                  VALUES 
                  ('{$user['id_user']}', '$group_id', '$specialty_id', '$start_date', '$end_date', 
                   '$organization_name', '$organization_address', '$supervisor_name', 
                   '$supervisor_position', '$work_description', 1)";
        
        if (mysqli_query($db, $query)) {
            $success = "Отчет успешно отправлен на проверку!";
            // Очищаем POST данные
            $_POST = [];
        } else {
            $error = "Ошибка при отправке отчета: " . mysqli_error($db);
        }
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

<form method="POST" action="">
    <h3>Данные студента</h3>
    <div>
        <label>ФИО:</label>
        <input type="text" value="<?= htmlspecialchars($user['surname'] . ' ' . $user['name'] . ' ' . $user['otchestvo']) ?>" disabled style="background-color: #f5f5f5;">
    </div>
    
    <div>
        <label for="group_id">Группа *</label>
        <select name="group_id" id="group_id" required>
            <option value="">-- Выберите группу --</option>
            <?php foreach ($groups as $group): ?>
                <option value="<?= $group['id_group'] ?>" <?= (isset($_POST['group_id']) && $_POST['group_id'] == $group['id_group']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($group['group_name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    
    <div>
        <label for="specialty_id">Специальность *</label>
        <select name="specialty_id" id="specialty_id" required>
            <option value="">-- Выберите специальность --</option>
            <?php foreach ($specialties as $specialty): ?>
                <option value="<?= $specialty['id_specialty'] ?>" <?= (isset($_POST['specialty_id']) && $_POST['specialty_id'] == $specialty['id_specialty']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($specialty['specialty_name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    
    <h3>Сведения о практике</h3>
    <div>
        <label for="start_date">Дата начала практики *</label>
        <input type="date" name="start_date" id="start_date" value="<?= isset($_POST['start_date']) ? htmlspecialchars($_POST['start_date']) : '' ?>" required>
    </div>
    
    <div>
        <label for="end_date">Дата окончания практики *</label>
        <input type="date" name="end_date" id="end_date" value="<?= isset($_POST['end_date']) ? htmlspecialchars($_POST['end_date']) : '' ?>" required>
    </div>
    
    <div>
        <label for="organization_name">Название организации *</label>
        <input type="text" name="organization_name" id="organization_name" value="<?= isset($_POST['organization_name']) ? htmlspecialchars($_POST['organization_name']) : '' ?>" required>
    </div>
    
    <div>
        <label for="organization_address">Адрес организации *</label>
        <input type="text" name="organization_address" id="organization_address" value="<?= isset($_POST['organization_address']) ? htmlspecialchars($_POST['organization_address']) : '' ?>" required>
    </div>
    
    <div>
        <label for="supervisor_name">ФИО руководителя от организации *</label>
        <input type="text" name="supervisor_name" id="supervisor_name" value="<?= isset($_POST['supervisor_name']) ? htmlspecialchars($_POST['supervisor_name']) : '' ?>" required>
    </div>
    
    <div>
        <label for="supervisor_position">Должность руководителя *</label>
        <input type="text" name="supervisor_position" id="supervisor_position" value="<?= isset($_POST['supervisor_position']) ? htmlspecialchars($_POST['supervisor_position']) : '' ?>" required>
    </div>
    
    <div>
        <label for="work_description">Краткое описание выполненных работ *</label>
        <textarea name="work_description" id="work_description" required><?= isset($_POST['work_description']) ? htmlspecialchars($_POST['work_description']) : '' ?></textarea>
    </div>
    
    <button type="submit">Отправить отчет</button>
</form>

<p><a href="reports.php">← Вернуться к списку отчетов</a></p>

<?php
$pageContent = ob_get_clean();
require_once "struktura.php";
?>