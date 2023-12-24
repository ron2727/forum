<?php
require_once '../../../database/connection.php';

session_start();
$user_id = $_SESSION['user_id'];

$image_tempname = $_FILES['newPhoto']['tmp_name'];
$image_ex = pathinfo($_FILES['newPhoto']['name'], PATHINFO_EXTENSION);
$image_new_name = uniqid('PROFILE-', false) . "." . $image_ex;
$image_upload_location = '../../uploaded_images/' . $image_new_name;

$upload_success = move_uploaded_file($image_tempname, $image_upload_location);

$sql = "SELECT * FROM users WHERE id = :user_id";
$statement = $pdo->prepare($sql);
$statement->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$statement->execute();
$user = $statement->fetch(PDO::FETCH_NAMED);
if($user['profile_photo'] != 'default_profile.png'){
  unlink('../../uploaded_images/'.$user['profile_photo']);
} 

if ($upload_success) {
   $sql = "UPDATE users SET profile_photo = :profile_photo WHERE id = :user_id";

   $statement = $pdo->prepare($sql);
   $statement->bindParam(':profile_photo', $image_new_name);
   $statement->bindParam(':user_id', $user_id, PDO::PARAM_INT);
   $statement->execute();
   
   $_SESSION['profile_photo'] = $image_new_name;

   echo $image_new_name;
}
