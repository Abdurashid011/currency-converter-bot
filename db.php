<?php

$dsn = 'mysql:host=localhost;dbname=converter_db;charset=utf8';
$username = 'abdurashid';
$password = 'Abdu_1504';

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    die("Ma'lumotlar bazasi bilan bog'lanishda xato: " . $e->getMessage());
}
