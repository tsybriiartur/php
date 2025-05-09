<?php
$host = 'localhost';
$db = 'clients_db';
$user = 'root';
$pass = 'pass';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    echo "DB connection error: " . $e->getMessage();
    exit;
}
?>
