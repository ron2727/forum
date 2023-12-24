<?php
require_once '../../../helper/functions.php';
require_once '../../../database/connection.php';

$user_id = $_GET['userid'];
$startFrom = $_GET['startFrom'];
 
$sql = "SELECT * FROM users WHERE id = :id";
$statement = $pdo->prepare($sql);
$statement->bindParam(":id", $user_id, PDO::PARAM_INT);
$statement->execute();
$user = $statement->fetch(PDO::FETCH_NAMED);

$sql = "SELECT users.firstname, users.lastname, post.title, post.content, post.created_at, post.id
        FROM users
        INNER JOIN post ON users.id = post.user_id 
        WHERE users.id = :id
        ORDER BY post.created_at DESC
        LIMIT :startFrom, 2";
$statement = $pdo->prepare($sql);
$statement->bindParam(":id", $user_id, PDO::PARAM_INT);
$statement->bindParam(":startFrom", $startFrom, PDO::PARAM_INT);
$statement->execute();
$result = $statement->fetchAll();
?>

<?php foreach ($result as $post) : ?>
    <a href="post.php?id=<?php echo $post['id'] ?>" class=" nav-link">
        <div class="post-item row my-3" style="background:#d9d9d9">
            <div class="col-md-11">
                <div class="header d-flex mb-2">
                    <div class="image-profile border">
                    </div>
                    <div class="user-post-details ms-2">
                        <h5 class="post-user-name fw-semibold"><?php echo $user['firstname'] . ' ' . $user['lastname'] ?></h5>
                        <small class="date-post"><?php echo getAgoDate($post['created_at']) ?></small>
                    </div>
                </div>
                <h4 class="post-title fw-semibold m-0"><?php echo $post['title'] ?></h4>
                <div class="post-content my-1 text-break">
                    <?php echo $post['content'] ?>
                </div>
                <div class="footer my-1">
                    <span class="post-stats">265 views - </span>
                    <span class="post-stats">154 replies - </span>
                    <span class="post-stats">Share</span>
                </div>
            </div>
            <?php
            $sql = "SELECT * FROM likes 
                                    WHERE user_id = :user_id AND post_id = :post_id";
            $statement = $pdo->prepare($sql);
            $statement->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $statement->bindParam(':post_id', $post['id'], PDO::PARAM_INT);
            $statement->execute();

            if ($statement->fetch()) {
                $heart = '<i id="likeIcon" class="bi bi-heart-fill" style="color:red;"></i> ';
            } else {
                $heart = '<i id="likeIcon" class="bi bi-heart"></i> ';
            }

            $sql = "SELECT * FROM likes 
                                    WHERE post_id = :post_id";
            $statement = $pdo->prepare($sql);
            $statement->bindParam(':post_id', $post['id'], PDO::PARAM_INT);
            $statement->execute();

            if ($statement->rowCount()) {
                $number_of_likes = $statement->rowCount();
            } else {
                $number_of_likes = '';
            }
            ?>
            <div class="col-sm-1 d-flex flex-column justify-content-center  align-items-center">
                <button class="btn">
                    <?php echo $heart ?>
                </button>
                <div class=" fw-bold">
                    <?php echo $number_of_likes ?>
                </div>
            </div>
        </div>
    </a>
<?php endforeach; ?>