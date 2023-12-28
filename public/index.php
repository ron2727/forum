<?php
require_once '../helper/functions.php';
require_once '../database/connection.php';
session_start();

$current_page = $_GET['page'] ?? 1;
$number_posts_per_page = 3;
?>
<?php view('header.php', 'Home') ?>


<?php include_once '../components/navigation.php' ?>
<?php include_once '../components/side_navigation.php' ?>
<?php
if (isset($_SESSION['user_id'])) {
  include_once '../components/create_post_modal.php';
} else {
  include_once '../components/create_post_modal_not_log.php';
}
?>

<style>
  .main-container {
    padding-left: 1rem;
    padding-right: 1rem;
  }

  .post-item {
    padding-top: 0.8rem;
  }

  .image-profile {
    width: 40px;
    height: 40px
  }

  .user-name {
    font-size: 0.8rem;
  }

  .post-image-profile {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }

  .post-title {
    font-size: 1.1rem;
  }

  .post-content {
    font-size: 0.9rem;
  }

  .date-post {
    font-size: 0.6rem;
  }

  .post-stats {
    font-size: 0.8rem;
  }

  .p-num {
    font-size: 0.6rem;
    padding: 5px 10px;
  }

  @media (min-width: 640px) {
    .post-item {
      padding: 1rem;
    }

    .p-num {
      font-size: 0.8rem; 
    }
  }

  @media (min-width: 768px) {}

  @media (min-width: 1024px) {
    .main-container {
      margin-left: 350px;
      margin-top: 80px;
      padding-left: 3rem;
      padding-right: 3rem;
    }

    .post-item {
      padding: 1.5rem;
    }

    .image-profile {
      width: 50px;
      height: 50px
    }

    .user-name {
      font-size: 1.2rem;
    }

    .date-post {
      font-size: 0.8rem;
    }

    .post-title {
      font-size: 1.3rem;
    }

    .post-content {
      font-size: 1rem;
    }

    .post-stats {
      font-size: 0.9rem;
    }

    .p-num {
      font-size: 0.9rem; 
    }
  }
</style>

<div class="main-container">
  <div class="post-list">
    <?php
    $start_pos = ($current_page * $number_posts_per_page) - $number_posts_per_page;
    $sql = "SELECT users.firstname, users.lastname, categories.name, users.profile_photo, 
      post.id, post.created_at, post.title, post.content, post.views  
      FROM post
      INNER JOIN users ON post.user_id = users.id
      INNER JOIN categories ON post.category_id = categories.id ORDER BY post.id DESC
      LIMIT :start_pos, :number_pos";
    $statement = $pdo->prepare($sql);
    $statement->bindParam(':start_pos', $start_pos, PDO::PARAM_INT);
    $statement->bindParam(':number_pos', $number_posts_per_page, PDO::PARAM_INT);
    $statement->execute();
    $result = $statement->fetchAll(PDO::FETCH_NAMED)
    ?>
    <?php foreach ($result as $post) : ?>
      <a href="post.php?id=<?php echo $post['id'] ?>" class=" nav-link">
        <div class="post-item row my-3 bg-white ">
          <div class="col-sm-11">
            <div class="header d-flex mb-2">
              <div class="image-profile border">
                <img class="post-image-profile" src="uploaded_images/<?php echo $post['profile_photo'] ?>" alt="profile">
              </div>
              <div class="user-post-details ms-2">
                <h5 class="user-name fw-semibold"><?php echo $post['firstname'] . ' ' . $post['lastname'] ?></h5>
                <div class="date">
                  <small class="date-post"><?php echo getAgoDate($post['created_at']) ?></small>
                </div>
              </div>
            </div>
            <div class="categories mb-2">
              <span class="badge category-item bg-primary"><?php echo $post['name'] ?></span>
            </div>
            <h4 class="post-title fw-semibold m-0"><?php echo $post['title'] ?></h4>
            <div class="post-content text-break ">
              <?php echo $post['content'] ?>
            </div>
            <div class="footer my-1">
              <span class="post-stats"> <?php echo $post['views'] ?> <i class="bi bi-eye"></i> - </span>
              <?php
              $sql = "SELECT * FROM comments WHERE post_id = :post_id";
              $statement = $pdo->prepare($sql);
              $statement->bindParam(':post_id', $post['id'], PDO::PARAM_INT);
              $statement->execute();
              ?>
              <span class="post-stats"><?php echo $statement->rowCount() ?> <i class="bi bi-chat-left"></i></span>
            </div>
          </div>
          <?php
          $sql = "SELECT * FROM likes 
                    WHERE user_id = :user_id AND post_id = :post_id";
          $statement = $pdo->prepare($sql);
          $statement->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
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
            <button class="btn border-0">
              <?php echo $heart ?>
            </button>
            <div class=" fw-bold">
              <?php echo $number_of_likes ?>
            </div>
          </div>
        </div>
      </a>
    <?php endforeach; ?>
    <?php
    $sql = "SELECT * FROM post";
    $statement = $pdo->query($sql);
    $number_of_pages = round($statement->rowCount() / $number_posts_per_page);
    $prev_page = $current_page - 1;
    $next_page = $current_page + 1;
    if ($prev_page < 1) {
      $prev_page = $number_of_pages;
    }
    if ($next_page > $number_of_pages) {
      $next_page = 1;
    }
    ?>
    <div class=" pagination-container mb-4 d-flex justify-content-end">
      <div class="page-list d-flex align-items-center">
        <a href="index.php?page=<?php echo $prev_page ?>" class="prev-page nav-link mx-2"><i class="bi bi-chevron-left"></i></a>
        <?php for ($i = 1; $i <= $number_of_pages; $i++) : ?>
          <div class="page-numbers d-flex">
            <?php if ($current_page == $i) : ?>
              <a href="index.php?page=<?php echo $i ?>" class="p-num nav-link mx-1 text-white text-center" style="background: #6d28d9"><?php echo $i ?></a>
            <?php else : ?>
              <a href="index.php?page=<?php echo $i ?>" class="p-num nav-link mx-1 text-center border bg-white"><?php echo $i ?></a>
            <?php endif; ?>
          </div>
        <?php endfor; ?>
        <a href="index.php?page=<?php echo $next_page ?>" class="next-page nav-link mx-2"><i class="bi bi-chevron-right"></i></a>
      </div>
    </div>
  </div>
</div>


<?php view('footer.php') ?>