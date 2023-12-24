<?php
require_once '../../../database/connection.php';
extract($_POST);

$sql = "SELECT * FROM users WHERE id = :id";
$statement = $pdo->prepare($sql);
$statement->bindParam(":id", $userid, PDO::PARAM_INT);
$statement->execute();
$user = $statement->fetch(PDO::FETCH_NAMED);

$errors = [];
$email_valid = filter_var($email, FILTER_VALIDATE_EMAIL);

if (trim($firstname) == '') {
    $errors['firstname'] = 'Please input your firstname';
}
if (trim($lastname) == '') {
    $errors['lastname'] = 'Please input your lastname';
}
if (!$email_valid) {
    $errors['email'] = 'Email is invalid';
}
if (trim($email) == '') {
    $errors['email'] = 'Please input your email';
}
if ($password != '' && $newpass == '') {
    $errors['newpass'] = 'Please enter your new password';
}
if ($password == '' && $newpass != '') {
    $errors['password'] = 'Please enter your current password';
}
if ($password != '' && $newpass != '') {
    if ($user['password'] != md5($password)) {
        $errors['password'] = 'Incorrect Password';
    }
}
if (!count($errors)) {

    if ($password != '' && $newpass != '') {
        $newpass = md5($newpass);
        $sql = "UPDATE users SET firstname = :firstname, lastname = :lastname, email = :email, password = :password
        WHERE id = :id";
        $statement = $pdo->prepare($sql);
        $statement->bindParam(":id", $userid, PDO::PARAM_INT);
        $statement->bindParam(":firstname", $firstname);
        $statement->bindParam(":lastname", $lastname);
        $statement->bindParam(":email", $email);
        $statement->bindParam(":password", $newpass);
    } else {
        $sql = "UPDATE users SET firstname = :firstname, lastname = :lastname, email = :email
                WHERE id = :id";
        $statement = $pdo->prepare($sql);
        $statement->bindParam(":id", $userid, PDO::PARAM_INT);
        $statement->bindParam(":firstname", $firstname);
        $statement->bindParam(":lastname", $lastname);
        $statement->bindParam(":email", $email); 
    }
    $statement->execute();

    echo 'success';
}else{
    echo json_encode($errors);
}

