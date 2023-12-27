<?php
require_once '../../../database/connection.php';
$sanitized_inputs = [];

foreach ($_POST as $field => $value) {
    $sanitized_inputs[$field] = htmlspecialchars($value);
}

if (!empty($_FILES['image']['name'])) {
    $image_tempname = $_FILES['image']['tmp_name'];
    $image_ex = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
    $image_new_name = uniqid('POST-', false) . "." . $image_ex;
    $image_upload_location = '../../uploaded_images/' . $image_new_name;

    move_uploaded_file($image_tempname, $image_upload_location);
    if (!empty($sanitized_inputs['current_image_name'])) {
        unlink('../../uploaded_images/' . $sanitized_inputs['current_image_name']);
    }
    $sql = "UPDATE post 
            SET title = :title, category_id = :category_id, content = :content, image_name = :image_name
            WHERE id = :id";
    $statement = $pdo->prepare($sql);
    $statement->bindParam(":image_name", $image_new_name);
}else {
    $sql = "UPDATE post 
            SET title = :title, category_id = :category_id, content = :content
            WHERE id = :id";
    $statement = $pdo->prepare($sql);
}

$statement->bindParam(":id", $sanitized_inputs['postid'], PDO::PARAM_INT);
$statement->bindParam(":title", $sanitized_inputs['title']);
$statement->bindParam(":category_id", $sanitized_inputs['categories']);
$statement->bindParam(":content", $sanitized_inputs['content']);

$statement->execute();

$statement = $pdo->query("SELECT * FROM categories WHERE id = {$sanitized_inputs['categories']}");
$category = $statement->fetch(PDO::FETCH_NAMED);
$post_details = [
    'title' => $sanitized_inputs['title'],
    'category' => $category['name'],
    'content' => $sanitized_inputs['content']
];

if (!empty($_FILES['image']['name'])) { 
    $post_details['image_name'] = $image_new_name; 
} 
echo json_encode($post_details);
?>