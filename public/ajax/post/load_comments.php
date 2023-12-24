<?php
require_once '../../../helper/functions.php';
require_once '../../../database/connection.php';

$post_id = $_GET['postid'];
$startFrom = $_GET['startFrom'];

$sql = "SELECT users.firstname, users.lastname, comments.created_at, comments.comments, users.profile_photo  
            FROM users
            INNER JOIN comments ON users.id = comments.user_id 
            WHERE comments.post_id = :id
            ORDER BY comments.id DESC
            LIMIT :startFrom, 2";
$statement = $pdo->prepare($sql);
$statement->bindParam(":id", $post_id, PDO::PARAM_INT);
$statement->bindParam(":startFrom", $startFrom, PDO::PARAM_INT);
$statement->execute();
$result = $statement->fetchAll();
?>

<?php foreach ($result as $comments) : ?>
    <div class="comment-item border-top border-bottom py-3">
        <div class="header d-flex">
            <div class="image-profile-comment border">
              <img class="comment-image-profile" src="uploaded_images/<?php echo $comments['profile_photo']?>" alt="profile">
            </div>
            <div class=" user-comment-details ms-2">
                <h6 class="user-name-comment py-0 m-0"><?php echo $comments['firstname'] . ' ' . $comments['lastname'] ?></h6>
                <small class="date-post-comment">
                    <?php echo getAgoDate($comments['created_at']) ?>
                </small>
            </div>
        </div>
        <div class="comment-text py-2">
            <?php echo $comments['comments'] ?>
        </div>
    </div>
<?php endforeach; ?>