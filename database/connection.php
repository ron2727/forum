<?php
require_once 'config.php';

try {
    $dns = sprintf("mysql:host=%s;dbname=%s;charset=UTF8", DB_HOST, DB_NAME);
    $pdo = new PDO($dns, DB_USER, DB_PASSWORD);
} catch (\Throwable $th) {
    echo 'Error in the database connection';
}