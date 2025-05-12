<?php
$host = 'localhost';
$db   = 'currency_converter';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';
$port = 3306;

$dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$charset";

try {
    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>