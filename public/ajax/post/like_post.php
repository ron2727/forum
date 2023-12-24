<?php
session_start();
require_once '../../../database/connection.php';


$sql = "SELECT * FROM likes 
       WHERE user_id = :user_id AND post_id = :post_id";

$statement = $pdo->prepare($sql);
$statement->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
$statement->bindParam(':post_id', $_POST['postid'], PDO::PARAM_INT);
$statement->execute();

if ($statement->fetch()) {
    $sql = "DELETE FROM likes 
       WHERE user_id = :user_id AND post_id = :post_id";
    $statement = $pdo->prepare($sql);
    $statement->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
    $statement->bindParam(':post_id', $_POST['postid'], PDO::PARAM_INT);
    $statement->execute();
    echo '<i id="likeIcon" class="bi bi-heart"></i> ';
}else{
    $sql = "INSERT INTO likes(user_id, post_id)
            VALUES(:user_id, :post_id)";
    $statement = $pdo->prepare($sql);
    $statement->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
    $statement->bindParam(':post_id', $_POST['postid'], PDO::PARAM_INT);
    $statement->execute();
    echo '<i id="likeIcon" class="bi bi-heart-fill" style="color:red;"></i> ';
}

$sql = "SELECT * FROM likes 
                       WHERE post_id = :post_id";
$statement = $pdo->prepare($sql);
$statement->bindParam(':post_id', $_POST['postid'], PDO::PARAM_INT);
$statement->execute();

if ($statement->rowCount()) {
    echo $statement->rowCount();
}