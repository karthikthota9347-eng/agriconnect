<?php
// Railway environment variables (auto-set by Railway MySQL plugin)
$host = getenv('MYSQLHOST')     ?: getenv('DB_HOST') ?: 'localhost';
$user = getenv('MYSQLUSER')     ?: getenv('DB_USER') ?: 'root';
$pass = getenv('MYSQLPASSWORD') ?: getenv('DB_PASS') ?: '';
$db   = getenv('MYSQLDATABASE') ?: getenv('DB_NAME') ?: 'agriconnect_db';
$port = getenv('MYSQLPORT')     ?: 3306;

$conn = mysqli_connect($host, $user, $pass, $db, (int)$port);
mysqli_set_charset($conn, "utf8mb4");
if(!$conn) die("DB Error: " . mysqli_connect_error());
?>
