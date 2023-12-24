<?php
require_once '../../../database/connection.php';

session_start();
date_default_timezone_set('Asia/Manila');

$date_time_now = date('m/d/Y h:i:s');

$image_tempname = $_FILES['image']['tmp_name'];
$image_ex = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
$image_new_name = uniqid('POST-', false) . "." . $image_ex;
$image_upload_location = '../../uploaded_images/' . $image_new_name;

// move_uploaded_file($image_tempname, $image_upload_location);



$sanitized_inputs = [];

foreach ($_POST as $field => $value) {
    $sanitized_inputs[$field] = htmlspecialchars($value);
}

$sql = "INSERT INTO post(user_id, category_id, title, content, image_name, created_at)
        VALUES(:user_id, :category_id, :title, :content, :image_name, :created_at)";

$statement = $pdo->prepare($sql);

$statement->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
$statement->bindParam(':category_id', $sanitized_inputs['categories'], PDO::PARAM_INT);
$statement->bindParam(':title', $sanitized_inputs['title']);
$statement->bindParam(':content', $sanitized_inputs['content']);
$statement->bindParam(':image_name', $image_new_name);
$statement->bindParam(':created_at', $date_time_now);

$statement->execute();

$post_id = $pdo->lastInsertId();

$sql = "SELECT users.firstname, users.lastname, categories.name, users.profile_photo,
                   post.id, post.created_at, post.title, post.content  
            FROM post
            INNER JOIN users ON post.user_id = users.id
            INNER JOIN categories ON post.category_id = categories.id
            WHERE post.id = :id";

$statement = $pdo->prepare($sql);

$statement->bindParam(':id', $post_id, PDO::PARAM_INT);

$statement->execute();

$post =  $statement->fetch(PDO::FETCH_NAMED);

?>

<a href="post.php?id=<?php echo $post['id'] ?>" class=" nav-link">
    <div class="post-item row my-3" style="background:#d9d9d9">
        <div class="col-sm-11">
            <div class="header d-flex mb-2">
                <div class="image-profile border">
                    <img class="post-image-profile" src="uploaded_images/<?php echo $post['profile_photo'] ?>" alt="profile">
                </div>
                <div class="user-post-details ms-2">
                    <h5 class="user-name fw-semibold"><?php echo $post['firstname'] . ' ' . $post['lastname'] ?></h5>
                    <div class="date">
                        <small class="date-post">few seconds ago</small>
                    </div>
                </div>
            </div>
            <div class="categories mb-2">
              <span class="badge category-item bg-primary"><?php echo $post['name'] ?></span>
            </div>
            <h4 class="post-title fw-semibold m-0"><?php echo $post['title'] ?></h4>
            <div class="post-content">
                <?php echo $post['content'] ?>
            </div>
            <div class="footer my-1">
                <span class="post-stats">265 views - </span>
                <span class="post-stats">154 replies - </span>
                <span class="post-stats">Share</span>
            </div>
        </div>
        <div class="col-sm-1 d-flex justify-content-center  align-items-center">
            <button class=" btn"><i class="bi bi-heart"></i></button>
        </div>
    </div>
</a>