<?php
require_once '../helper/functions.php';
require_once '../database/connection.php';
session_start();
$q = $_GET['search'];

$sql = "SELECT users.firstname, users.lastname, categories.name, users.profile_photo, 
      post.id, post.created_at, post.title, post.content  
      FROM post
      INNER JOIN users ON post.user_id = users.id
      INNER JOIN categories ON post.category_id = categories.id
      WHERE users.firstname LIKE :q OR users.lastname LIKE :q 
            OR categories.name LIKE :q OR post.title LIKE :q
      ORDER BY post.id DESC";
$statement = $pdo->prepare($sql);
$statement->bindParam(':q', $q);
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_NAMED);
?>
<?php view('header.php', 'Home') ?>


<?php include_once '../components/navigation.php' ?>

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

  .filter-text {
    font-size: 0.7rem;
  }

  @media (min-width: 640px) {
    .post-item {
      padding: 1rem;
    }

    .filter-text {
      font-size: 0.9rem;
    }
  }

  @media (min-width: 768px) {}

  @media (min-width: 1024px) {
    .main-container { 
      margin-top: 80px;
      padding-left: 3rem;
      padding-right: 3rem;
    }

    .post-item {
      padding: 1.5rem;
    }

    .image-profile {
      width: 60px;
      height: 60px
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

    .filter-text {
      font-size: 1rem;
    }
  }
</style>

<div class="main-container">
  <div class=" filter-container text-end mt-3">
    <div class="dropdown open">
      <a class="filter-text btn btn-white border rounded-0" type="button" id="filter" data-bs-toggle="dropdown">
        Filter <i class="bi bi-funnel"></i>
      </a>
      <div class="dropdown-menu" aria-labelledby="filter">
        <a class="dropdown-item" href="#">Action</a>
        <a class="dropdown-item disabled" href="#">Disabled action</a>
      </div>
    </div>
  </div>
  <div class="post-list">
    <?php if ($statement->rowCount()):?>
    <?php foreach ($result as $post) : ?>
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
                  <small class="date-post"><?php echo getAgoDate($post['created_at']) ?></small>
                </div>
              </div>
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
    <?php else:?>
      <div class="no-result-con">
         <h5 class=" text-center py-5">No results found</h5>
      </div>
    <?php endif; ?>
  </div>
</div>


<?php view('footer.php') ?>