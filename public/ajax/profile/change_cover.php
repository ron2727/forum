<?php
require_once '../../../database/connection.php';
session_start();
$cover_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

$sql = "UPDATE users SET cover_id = :id WHERE id = :user_id";
$statement = $pdo->prepare($sql);
$statement->bindParam(':id', $cover_id, PDO::PARAM_INT);
$statement->bindParam(':user_id', $user_id, PDO::PARAM_INT);
if ($statement->execute()) {
    $sql = "SELECT * FROM covers WHERE id = :id";
    $statement = $pdo->prepare($sql);
    $statement->bindParam(':id', $cover_id, PDO::PARAM_INT);
    if ($statement->execute()) {
      $cover = $statement->fetch(PDO::FETCH_NAMED);
      echo $cover['linear_gradient'];
    }
}
?>