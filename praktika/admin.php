<?php
$pageTitle = "Панель преподавателя";
require_once "db/db.php";

// Проверка авторизации и прав преподавателя
if (!isset($_SESSION['user']) || $_SESSION['user']['user_type_id'] != 2) {
    header("Location: index.php");
    exit();
}

$message = "";

// Обработка изменения статуса отчета
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['change_status'])) {
        $report_id = (int)$_POST['report_id'];
        $new_status = (int)$_POST['status_id'];
        $teacher_comment = mysqli_real_escape_string($db, $_POST['teacher_comment'] ?? '');
        
        $update_query = "UPDATE practice_reports SET status_id = '$new_status', teacher_comment = '$teacher_comment' WHERE id_report = '$report_id'";
        
        if (mysqli_query($db, $update_query)) {
            $message = "Статус отчета успешно изменен!";
        } else {
            $message = "Ошибка при изменении статуса: " . mysqli_error($db);
        }
    }
}

// Получаем все отчеты
$reports_query = "
    SELECT pr.*, 
           CONCAT(u.surname, ' ', u.name, ' ', u.otchestvo) as student_name,
           g.group_name,
           s.specialty_name,
           st.name_status
    FROM practice_reports pr
    JOIN user u ON pr.user_id = u.id_user
    JOIN groups g ON pr.group_id = g.id_group
    JOIN specialties s ON pr.specialty_id = s.id_specialty
    JOIN status st ON pr.status_id = st.id_status
    ORDER BY pr.created_at DESC
";
$reports_result = mysqli_query($db, $reports_query);

// Получаем все статусы
$statuses_query = mysqli_query($db, "SELECT * FROM status");
$statuses = [];
while ($row = mysqli_fetch_assoc($statuses_query)) {
    $statuses[$row['id_status']] = $row;
}

ob_start();
?>

<?php if ($message): ?>
    <div class="<?php echo strpos($message, 'успешно') !== false ? 'success' : 'error'; ?>">
        <?php echo $message; ?>
    </div>
<?php endif; ?>

<h2>Все отчеты по практике</h2>

<?php if ($reports_result && mysqli_num_rows($reports_result) > 0): ?>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Студент</th>
                <th>Группа</th>
                <th>Организация</th>
                <th>Период</th>
                <th>Статус</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($report = mysqli_fetch_assoc($reports_result)): ?>
            <tr>
                <td><?= $report['id_report'] ?></td>
                <td><?= htmlspecialchars($report['student_name']) ?></td>
                <td><?= htmlspecialchars($report['group_name']) ?></td>
                <td><?= htmlspecialchars($report['organization_name']) ?></td>
                <td><?= htmlspecialchars($report['start_date']) ?> - <?= htmlspecialchars($report['end_date']) ?></td>
                <td>
                    <span class="status-badge status-<?= $report['status_id'] ?>">
                        <?= htmlspecialchars($report['name_status']) ?>
                    </span>
                </td>
                <td>
                    <button onclick="showReportDetails(<?= $report['id_report'] ?>)">Просмотр</button>
                    <form method="POST" style="display: inline-block; margin-left: 10px;">
                        <input type="hidden" name="report_id" value="<?= $report['id_report'] ?>">
                        <select name="status_id" style="padding: 5px; margin-right: 5px;">
                            <?php foreach ($statuses as $id => $status): ?>
                                <option value="<?= $id ?>" <?= $id == $report['status_id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($status['name_status']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <textarea name="teacher_comment" placeholder="Комментарий (для доработки)" 
                                  style="width: 200px; padding: 5px; margin: 5px 0;" 
                                  rows="2"><?= htmlspecialchars($report['teacher_comment'] ?? '') ?></textarea>
                        <button type="submit" name="change_status">Сохранить</button>
                    </form>
                </td>
            </tr>
            
            <!-- Детали отчета (скрытые) -->
            <tr id="details-<?= $report['id_report'] ?>" style="display: none; background-color: #f9f9f9;">
                <td colspan="7">
                    <div style="padding: 15px;">
                        <h4>Подробная информация об отчете #<?= $report['id_report'] ?></h4>
                        <div class="card-field"><strong>Специальность:</strong> <?= htmlspecialchars($report['specialty_name']) ?></div>
                        <div class="card-field"><strong>Адрес организации:</strong> <?= htmlspecialchars($report['organization_address']) ?></div>
                        <div class="card-field"><strong>Руководитель:</strong> <?= htmlspecialchars($report['supervisor_name']) ?> (<?= htmlspecialchars($report['supervisor_position']) ?>)</div>
                        <div class="card-field"><strong>Описание работ:</strong><br><?= nl2br(htmlspecialchars($report['work_description'])) ?></div>
                        <div class="card-field"><strong>Дата подачи:</strong> <?= htmlspecialchars(date('d.m.Y H:i', strtotime($report['created_at']))) ?></div>
                        <?php if (!empty($report['teacher_comment'])): ?>
                            <div class="card-field"><strong>Ваш комментарий:</strong><br><?= nl2br(htmlspecialchars($report['teacher_comment'])) ?></div>
                        <?php endif; ?>
                    </div>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    
    <script>
    function showReportDetails(reportId) {
        var details = document.getElementById('details-' + reportId);
        if (details.style.display === 'none') {
            details.style.display = 'table-row';
        } else {
            details.style.display = 'none';
        }
    }
    </script>
<?php else: ?>
    <p>Отчетов нет.</p>
<?php endif; ?>

<?php
$pageContent = ob_get_clean();
require_once "struktura.php";
?>