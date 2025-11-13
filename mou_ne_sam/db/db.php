<?php
// Чтобы на разных страницах оставалсь информация о данных пользователя будем использовать сессии (Другой вариант cookie)
session_start();

$db = mysqli_connect("localhost", "root", "", "mou_ne_sam");
if (!$db) {
    die ("Ошибка подключения к базе данных");
}

function find($login, $password) {
    global $db;
    $result = mysqli_query($db, "SELECT * FROM user WHERE username = '$login' AND password = MD5('$password');");
    return mysqli_num_rows($result);
}