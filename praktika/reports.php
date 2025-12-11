<?php
$pageTitle = 'Мои отчеты по практике';
require_once "db/db.php";

// Проверка авторизации
if (!isset($_SESSION['user']) || $_SESSION['user']['user_type_id'] != 1) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user']['id_user'];

// Получаем отчеты пользователя
$query = "
    SELECT pr.*, g.group_name, s.specialty_name, st.name_status
    FROM practice_reports pr
    JOIN groups g ON pr.group_id = g.id_group
    JOIN specialties s ON pr.specialty_id = s.id_specialty
    JOIN status st ON pr.status_id = st.id_status
    WHERE pr.user_id = ?
    ORDER BY pr.created_at DESC
";

$stmt = mysqli_prepare($db, $query);
mysqli_stmt_bind_param($stmt, 'i', $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$reports = mysqli_fetch_all($result, MYSQLI_ASSOC);
mysqli_stmt_close($stmt);

ob_start();
?>

<?php if (empty($reports)): ?>
    <div class="no-reports">
        <p>У вас пока нет отчетов по практике.</p>
        <a href="create_report.php" class="create-link">Подать первый отчет</a>
    </div>
<?php else: ?>
    <div class="cards-container">
        <?php foreach ($reports as $report): ?>
            <div class="card">
                <div class="card-header">
                    Отчет #<?= htmlspecialchars($report['id_report']) ?>
                    <span class="status-badge status-<?= $report['status_id'] ?>">
                        <?= htmlspecialchars($report['name_status']) ?>
                    </span>
                </div>
                <div class="card-field">
                    <strong>Организация:</strong> <?= htmlspecialchars($report['organization_name']) ?>
                </div>
                <div class="card-field">
                    <strong>Период:</strong> <?= htmlspecialchars($report['start_date']) ?> - <?= htmlspecialchars($report['end_date']) ?>
                </div>
                <div class="card-field">
                    <strong>Группа:</strong> <?= htmlspecialchars($report['group_name']) ?>
                </div>
                <div class="card-field">
                    <strong>Специальность:</strong> <?= htmlspecialchars($report['specialty_name']) ?>
                </div>
                <?php if ($report['status_id'] == 3 && !empty($report['teacher_comment'])): ?>
                    <div class="card-field">
                        <strong>Комментарий преподавателя:</strong>
                        <div style="background-color: #fff3cd; padding: 1rem; border-radius: 4px; margin-top: 0.5rem;">
                            <?= nl2br(htmlspecialchars($report['teacher_comment'])) ?>
                        </div>
                    </div>
                <?php endif; ?>
                <div class="card-field">
                    <strong>Дата подачи:</strong> <?= htmlspecialchars(date('d.m.Y H:i', strtotime($report['created_at']))) ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    
    <a href="create_report.php" class="create-link">Подать новый отчет</a>
<?php endif; ?>

<?php
$pageContent = ob_get_clean();
require_once "struktura.php";
?>