<?php
require_once '../../../database/connection.php';
session_start();
date_default_timezone_set('Asia/Manila');

$date_time_now = date('m/d/Y h:i:s');
$user_id = $_SESSION['user_id'];
$post_id = $_POST['postid'];
$comments = htmlspecialchars($_POST['comment']);
 
$sql = "INSERT INTO comments(post_id, user_id, comments, created_at)
        VALUES(:post_id, :user_id, :comments, :created_at)";

$statement = $pdo->prepare($sql);
$statement->bindParam(':post_id', $post_id,PDO::PARAM_INT);
$statement->bindParam(':user_id', $user_id,PDO::PARAM_INT);
$statement->bindParam(':comments', $comments,PDO::PARAM_STR);
$statement->bindParam(':created_at', $date_time_now,PDO::PARAM_STR);

?>
<?php if($statement->execute()):?>
<div class="comment-item border-top border-bottom py-3">
    <div class="header d-flex">
        <div class="image-profile-comment border"></div>
        <div class=" user-comment-details ms-2">
            <h6 class="user-name-comment py-0 m-0"><?php echo $_SESSION['user_name']?></h6>
            <small class="date-post-comment">
                few seconds ago
            </small>
        </div>
    </div>
    <div class="comment-text py-2">
       <?php echo $comments?>
    </div>
</div>
 
<?php endif;?>