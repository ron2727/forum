<?php
require_once '../../../database/connection.php';

session_start();
$user_id = $_SESSION['user_id'];

$sql = "SELECT * FROM users WHERE id = :user_id";
$statement = $pdo->prepare($sql);
$statement->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$statement->execute();
$user = $statement->fetch(PDO::FETCH_NAMED); 
if ($user['profile_photo'] != 'default_profile.png') {
    unlink('../../uploaded_images/' . $user['profile_photo']);
} 

$sql = "UPDATE users SET profile_photo = 'default_profile.png' WHERE id = :user_id";

$statement = $pdo->prepare($sql); 
$statement->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$statement->execute();

$_SESSION['profile_photo'] = 'default_profile.png';

echo 'default_profile.png';
