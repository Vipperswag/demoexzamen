<?php
session_start();

// Подключение к базе данных
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'praktika';

$db = mysqli_connect($host, $username, $password, $database);

if (!$db) {
    die("Ошибка подключения: " . mysqli_connect_error());
}

// Установка кодировки
mysqli_set_charset($db, "utf8");

// Функция для поиска пользователя
function find($login, $password) {
    global $db;
    
    $login = mysqli_real_escape_string($db, $login);
    $password = md5($password);
    
    $query = "SELECT u.*, ut.name_user as user_type_name 
              FROM user u 
              JOIN user_type ut ON u.user_type_id = ut.id_user_type 
              WHERE u.username = '$login' AND u.password = '$password'";
    
    $result = mysqli_query($db, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        return mysqli_fetch_assoc($result);
    }
    
    return false;
}

// Функция для получения списка групп
function getGroups() {
    global $db;
    $query = "SELECT * FROM groups ORDER BY group_name";
    $result = mysqli_query($db, $query);
    $groups = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $groups[] = $row;
    }
    return $groups;
}

// Функция для получения списка специальностей
function getSpecialties() {
    global $db;
    $query = "SELECT * FROM specialties ORDER BY specialty_name";
    $result = mysqli_query($db, $query);
    $specialties = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $specialties[] = $row;
    }
    return $specialties;
}
?>