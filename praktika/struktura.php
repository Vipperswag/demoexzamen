<?php
require_once "db/db.php"; 
$navLinks = [];
$showAuthLinks = true;

// Check if user is logged in via session
if (isset($_SESSION['user'])) {
    $showAuthLinks = false;
    $user = $_SESSION['user'];
    $userTypeId = $user['user_type_id'] ?? null;
    
    if ($userTypeId == 2) { // Преподаватель
        $navLinks = [
            ['href' => 'admin.php', 'text' => 'Панель преподавателя'],
        ];
    } else { // Студент
        $navLinks = [
            ['href' => 'reports.php', 'text' => 'Мои отчеты'],
            ['href' => 'create_report.php', 'text' => 'Подать отчет'],
        ];
    }
    $navLinks[] = ['href' => 'logout.php', 'text' => 'Выход'];
} else {
    $navLinks = [
        ['href' => 'index.php', 'text' => 'Авторизация'],
        ['href' => 'registration.php', 'text' => 'Регистрация'],
    ];
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Практика Онлайн<?php echo isset($pageTitle) ? " - $pageTitle" : ''; ?></title>
    <link rel='icon' href='images/logo.png'>
    <link rel='stylesheet' href='css/style.css'>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        }
        
        body {
            background-color: #f0f9ff;
            color: #334155;
            line-height: 1.6;
        }
        
        header {
            background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%);
            color: white;
            padding: 1.2rem 2rem;
            display: flex;
            align-items: center;
            gap: 1.2rem;
            box-shadow: 0 2px 10px rgba(2, 132, 199, 0.15);
        }
        
        header img {
            height: 50px;
        }
        
        header h1 {
            font-size: 1.8rem;
            font-weight: 600;
            letter-spacing: -0.3px;
        }
        
        nav {
            background-color: #0c4a6e;
            padding: 0.9rem 2rem;
            display: flex;
            gap: 0.8rem;
            flex-wrap: wrap;
        }
        
        nav a {
            color: #e0f2fe;
            text-decoration: none;
            padding: 0.6rem 1.2rem;
            border-radius: 6px;
            font-size: 0.95rem;
            font-weight: 500;
            transition: all 0.2s ease;
            border: 1px solid transparent;
        }
        
        nav a:hover {
            background-color: rgba(56, 189, 248, 0.2);
            border-color: #38bdf8;
            transform: translateY(-1px);
        }
        
        main {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1.5rem;
        }
        
        .content {
            background: white;
            padding: 2.2rem;
            border-radius: 10px;
            box-shadow: 0 3px 12px rgba(2, 132, 199, 0.08);
            min-height: 500px;
            border: 1px solid #e0f2fe;
        }
        
        h1 {
            color: #0c4a6e;
            margin-bottom: 1.8rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #0ea5e9;
            font-weight: 600;
            font-size: 1.8rem;
        }
        
        form {
            max-width: 600px;
            margin: 0 auto;
        }
        
        form div {
            margin-bottom: 1.5rem;
        }
        
        label {
            display: block;
            margin-bottom: 0.5rem;
            color: #0c4a6e;
            font-weight: 500;
            font-size: 0.95rem;
        }
        
        input[type="text"],
        input[type="password"],
        input[type="email"],
        input[type="tel"],
        input[type="date"],
        select,
        textarea {
            width: 100%;
            padding: 0.9rem;
            border: 1px solid #bae6fd;
            border-radius: 6px;
            font-size: 1rem;
            background-color: #f8fafc;
            transition: all 0.2s;
        }
        
        input:focus,
        select:focus,
        textarea:focus {
            outline: none;
            border-color: #0ea5e9;
            box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.1);
            background-color: white;
        }
        
        textarea {
            min-height: 150px;
            resize: vertical;
        }
        
        button {
            background: linear-gradient(135deg, #0ea5e9 0%, #38bdf8 100%);
            color: white;
            padding: 1rem 2.2rem;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 500;
            transition: all 0.2s ease;
            box-shadow: 0 2px 6px rgba(14, 165, 233, 0.25);
        }
        
        button:hover {
            background: linear-gradient(135deg, #0284c7 0%, #0ea5e9 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(14, 165, 233, 0.3);
        }
        
        .error {
            background-color: #fef2f2;
            color: #dc2626;
            padding: 1rem;
            border-radius: 6px;
            margin-bottom: 1.2rem;
            border-left: 4px solid #ef4444;
            font-size: 0.95rem;
        }
        
        .success {
            background-color: #f0fdf4;
            color: #16a34a;
            padding: 1rem;
            border-radius: 6px;
            margin-bottom: 1.2rem;
            border-left: 4px solid #22c55e;
            font-size: 0.95rem;
        }
        
        .cards-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
            gap: 1.5rem;
            margin-top: 2rem;
        }
        
        .card {
            background: white;
            border: 1px solid #e0f2fe;
            border-radius: 8px;
            padding: 1.8rem;
            box-shadow: 0 2px 8px rgba(2, 132, 199, 0.06);
            transition: all 0.25s ease;
            position: relative;
            overflow: hidden;
        }
        
        .card:before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, #0ea5e9, #7dd3fc);
        }
        
        .card:hover {
            transform: translateY(-4px);
            box-shadow: 0 6px 16px rgba(2, 132, 199, 0.1);
        }
        
        .card-header {
            font-weight: 600;
            font-size: 1.2rem;
            color: #0c4a6e;
            margin-bottom: 1.2rem;
            padding-bottom: 0.8rem;
            border-bottom: 1px solid #f0f9ff;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .card-field {
            margin-bottom: 0.9rem;
            line-height: 1.5;
            font-size: 0.95rem;
        }
        
        .card-field strong {
            color: #475569;
            min-width: 130px;
            display: inline-block;
            font-weight: 500;
        }
        
        .create-link {
            display: inline-block;
            margin-top: 1.8rem;
            padding: 1rem 2rem;
            background: linear-gradient(135deg, #10b981 0%, #34d399 100%);
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 500;
            transition: all 0.2s ease;
            box-shadow: 0 2px 6px rgba(16, 185, 129, 0.25);
        }
        
        .create-link:hover {
            background: linear-gradient(135deg, #0da271 0%, #10b981 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(16, 185, 129, 0.3);
        }
        
        .no-reports {
            text-align: center;
            padding: 3.5rem;
            color: #64748b;
            font-style: italic;
            background-color: #f8fafc;
            border-radius: 8px;
            border: 1px dashed #cbd5e1;
            margin: 2rem 0;
        }
        
        .status-badge {
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            display: inline-block;
        }
        
        .status-1 { background-color: #fff7ed; color: #ea580c; } /* На проверке */
        .status-2 { background-color: #f0fdf4; color: #16a34a; } /* Принято */
        .status-3 { background-color: #fef2f2; color: #dc2626; } /* На доработку */
        
        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin-top: 1.5rem;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(2, 132, 199, 0.08);
            border: 1px solid #e0f2fe;
        }
        
        th, td {
            padding: 1.1rem;
            text-align: left;
            border-bottom: 1px solid #e0f2fe;
        }
        
        th {
            background-color: #f0f9ff;
            font-weight: 600;
            color: #0c4a6e;
            font-size: 0.95rem;
        }
        
        tr:hover {
            background-color: #f8fafc;
        }
        
        tr:last-child td {
            border-bottom: none;
        }
        
        footer {
            margin-top: 3rem;
            padding-top: 1.5rem;
            border-top: 1px solid #e0f2fe;
            text-align: center;
            color: #64748b;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <header>
        <img src='images/logo.png' alt='Логотип'>
        <h1>Практика Онлайн</h1>
    </header>

    <nav>
        <?php foreach ($navLinks as $link): ?>
            <a href="<?php echo htmlspecialchars($link['href']); ?>"><?php echo htmlspecialchars($link['text']); ?></a>
        <?php endforeach; ?>
    </nav>

    <main>
        <h1><?php echo $pageTitle;?></h1>
        <div class="content">
            <?php 
            if (isset($pageContent) && !empty($pageContent)) {
                echo $pageContent;
            }
            ?>
        </div>
        <footer>
            <h3>© 2025 Техникум. Система "Практика Онлайн"</h3>
        </footer>
    </main>
</body>
</html>