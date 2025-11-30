<?php
$pageTitle = 'Мои заявления';
require_once "db/db.php";

if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user']['id_user'];

$query = "
    SELECT s.id_service, s.car_number, s.violation_description, s.data, s.time, stat.name_status
    FROM service s
    JOIN status stat ON s.status_id = stat.id_status
    WHERE s.user_id = ?
    ORDER BY s.data DESC, s.time DESC
";

$stmt = mysqli_prepare($db, $query);
mysqli_stmt_bind_param($stmt, 'i', $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$zayavki = mysqli_fetch_all($result, MYSQLI_ASSOC);
mysqli_stmt_close($stmt);

ob_start();
?>

<h1>Мои заявления о нарушениях</h1>

<?php if (empty($zayavki)): ?>
    <div class="no-zayavki">
        <p>У вас пока нет заявлений о нарушениях.</p>
        <a href="create_zayavka.php" class="create-link">Сообщить о нарушении</a>
    </div>
<?php else: ?>
    <div class="cards-container">
        <?php foreach ($zayavki as $z): ?>
            <div class="card">
                <div class="card-header">
                    Заявление #<?= htmlspecialchars($z['id_service']) ?>
                </div>
                <div class="card-field">
                    <strong>Номер авто:</strong> <?= htmlspecialchars($z['car_number']) ?>
                </div>
                <div class="card-field">
                    <strong>Нарушение:</strong> <?= htmlspecialchars($z['violation_description']) ?>
                </div>
                <div class="card-field">
                    <strong>Дата:</strong> <?= htmlspecialchars($z['data']) ?>
                </div>
                <div class="card-field">
                    <strong>Время:</strong> <?= htmlspecialchars($z['time']) ?>
                </div>
                <div class="card-field">
                    <strong>Статус:</strong> <?= htmlspecialchars($z['name_status']) ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    
    <a href="create_zayavka.php" class="create-link">Сообщить о новом нарушении</a>
<?php endif; ?>

<?php
$pageContent = ob_get_clean();
require_once "struktura.php";
?>