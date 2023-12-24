<?php
require_once '../helper/functions.php';
require_once '../database/connection.php';
session_start();
if (!isset($_GET['id'])) {
   header("Location: index.php");
   exit;
}
$post_id = htmlspecialchars($_GET['id']);

$sql = "SELECT users.firstname, users.lastname, categories.name, post.user_id, users.profile_photo, 
      post.id, post.created_at, post.title, post.content, post.views  
      FROM post
      INNER JOIN users ON post.user_id = users.id
      INNER JOIN categories ON post.category_id = categories.id 
      WHERE post.id = :id
      ORDER BY post.id DESC";
$statement = $pdo->prepare($sql);

$statement->bindParam(':id', $post_id, PDO::PARAM_INT);
$statement->execute();
$post = $statement->fetch(PDO::FETCH_NAMED);

$currentViews = $post['views'] + 1;

$sql = "UPDATE post SET views = :views WHERE id = :id";
$statement = $pdo->prepare($sql);
$statement->bindParam(":views", $currentViews, PDO::PARAM_INT);
$statement->bindParam(":id", $post_id, PDO::PARAM_INT);
$statement->execute();
?>
<?php view('header.php', 'Home') ?>

<?php include_once '../components/navigation.php' ?>
<?php
if (!isset($_SESSION['user_id'])) { 
  include_once '../components/create_post_modal_not_log.php';
}
?>
<style>
   .post-container {
      width: 97%;
   }

   .post-stats {
      padding-top: 0.4rem;
      padding-bottom: 0.4rem;
   }

   .image-profile-con{
      width: 40px;
      height: 40px
   }

   .post-image-profile, .comment-image-profile{
    width: 100%;
    height: 100%;
    object-fit: cover;
  }
  
   .image-profile-comment {
      width: 30px;
      height: 30px
   }
 
   .user-name {
      font-size: 0.8rem;
   }

   .user-name-comment {
      font-size: 0.7rem;
   }

   .date-post {
      font-size: 0.6rem;
   }

   .date-post-comment {
      font-size: 0.5rem;
   }

   .comment-label {
      font-size: 1rem;
   }

   .comment-not-log-text {
      font-size: 0.9rem;
   }

   .comment-text {
      font-size: 0.8rem;
   }


   @media (min-width: 640px) {}

   @media (min-width: 768px) {
      .image-profile-comment {
         width: 35px;
         height: 35px
      }

      .user-name-comment {
         font-size: 0.8rem;
      }

      .date-post-comment {
         font-size: 0.6rem;
      }
   }

   @media (min-width: 1024px) {
      .post-container {
         width: 50%;
      }

      .image-profile-con{
         width: 60px;
         height: 60px
      }

      .title-post {
         font-size: 2rem;
      }

      .user-name {
         font-size: 1.2rem;
      }

      .date-post {
         font-size: 0.7rem;
      }

      .comment-label {
         font-size: 1.2rem;
      }

      .comment-text {
         font-size: 0.9rem;
      }

      .comment-not-log-text {
         font-size: 1rem;
      }
   }
</style>
 
<div class=" main-container mb-5 d-flex justify-content-center" style="margin-top:80px;">
   <div class="post-container">
      <h4 class="title-post text-gray"><?php echo $post['title'] ?></h4>
      <div class="user-post-details d-flex my-3">
         <div class="image-profile-con border">
           <img class="post-image-profile" src="uploaded_images/<?php echo $post['profile_photo']?>" alt="profile">
         </div>
         <div class="user-post-details ms-2">
            <a href="profile.php?id=<?php echo $post['user_id']?>" class="user-name nav-link fw-semibold"><?php echo $post['firstname'] . ' ' . $post['lastname'] ?></a>
            <small class="date-post"><?php echo getAgoDate($post['created_at']) ?></small>
         </div>
      </div>
      <div class="categories mb-2">
         <span class="badge category-item bg-primary"><?php echo $post['name'] ?></span>
      </div>
      <div class="post-stats d-flex align-items-center border-top border-bottom">
         <button id="<?php echo isset($_SESSION['user_id']) ? 'btnLike':'postModal'?>" class="btn border-0 mx-2" data-bs-toggle="<?php echo isset($_SESSION['user_id']) ? '#':'modal'?>" data-bs-target="#postModal">
            <?php
            $sql = "SELECT * FROM likes 
                    WHERE user_id = :user_id AND post_id = :post_id";
            $statement = $pdo->prepare($sql);
            $statement->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
            $statement->bindParam(':post_id', $post_id, PDO::PARAM_INT);
            $statement->execute();

            if ($statement->fetch()) {
               echo '<i id="likeIcon" class="bi bi-heart-fill" style="color:red;"></i> ';
            } else {
               echo '<i id="likeIcon" class="bi bi-heart"></i> ';
            }

            $sql = "SELECT * FROM likes 
                       WHERE post_id = :post_id";
            $statement = $pdo->prepare($sql);
            $statement->bindParam(':post_id', $post_id, PDO::PARAM_INT);
            $statement->execute();

            if ($statement->rowCount()) {
               echo $statement->rowCount();
            }
            ?>
         </button>
         <span class=" mx-2">
            <i class="bi bi-chat"></i>
            <?php
            $sql = "SELECT * FROM comments 
            WHERE post_id = :post_id";
            $statement = $pdo->prepare($sql);
            $statement->bindParam(':post_id', $post_id, PDO::PARAM_INT);
            $statement->execute();

            if ($statement->rowCount()) {
               echo $statement->rowCount();
            }
            ?>
         </span>
      </div>
      <div class="post-content p-2 border-bottom text-break"><?php echo $post['content'] ?></div>
      <div class="post-comments-container">
         <h3 class="comment-label fw-semibold py-2">Comments</h3>
         <div class="comment-form my-3">
            <?php if (isset($_SESSION['user_id'])) : ?>
               <form id="commentForm">
                  <input type="hidden" name="postid" value="<?php echo $post_id ?>">
                  <div class="input-con my-1">
                     <textarea id="comment" name="comment" class="form-control rounded-0"></textarea>
                  </div>
                  <div class=" my-2 text-end">
                     <button disabled type="submit" id="btnComment" class=" btn text-white rounded-0" style="background: #6d28d9;">Comment</button>
                  </div>
               </form>
            <?php else : ?>
               <h6 class="comment-not-log-text text-center py-3">You have to login first to comment <a href="login.php" class=" text-decoration-none" style="color: #6d28d9;">Login</a></h6>
            <?php endif; ?>
         </div>
         <?php
         $sql = "SELECT users.firstname, users.lastname, comments.created_at, comments.comments, users.profile_photo  
            FROM users
            INNER JOIN comments ON users.id = comments.user_id 
            WHERE comments.post_id = :id
            ORDER BY comments.id DESC
            LIMIT 3";
         $statement = $pdo->prepare($sql);
         $statement->bindParam(":id", $post_id, PDO::PARAM_INT);
         $statement->execute();
         $result = $statement->fetchAll();
         ?>
         <div class="comments-list" post-id="<?php echo $post_id ?>">
            <?php if ($result) : ?>
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
            <?php else : ?>
               <div class="no-comment-con my-3 border-top border-bottom py-3">
                  <h6 class="comment-label text-center py-5">No comments yet</h6>
               </div>
            <?php endif; ?>
         </div>
         <!-- <div class="load-comments-spinner d-none justify-content-center align-items-center py-4">
            <span class="spinner-border spinner-border-sm" style="color:#6d28d9"></span>
         </div> -->
      </div>
   </div>
</div>

<script>
   $(document).ready(function() {

      $('#comment').on('input', function() {
         let comment = $(this).val()
         if ($.trim(comment) != '') {
            $('#btnComment').removeAttr('disabled');
         } else {
            $('#btnComment').attr('disabled', '');
         }
      });

      $('#commentForm').submit(function(e) {
         e.preventDefault();
         let comments = $(this).serialize();
         let isPostHasComments = $('.comments-list').children('.no-comment-con').length == 0;
         $.ajax({
            type: "POST",
            url: "ajax/post/post_comment.php",
            data: comments,
            dataType: 'text',
            success: function(response) {
               if (response != '') {
                  if (isPostHasComments) {
                     $('.comments-list').prepend(response);
                  } else {
                     $('.comments-list').html(response);
                  }
                  $('#comment').val('');
                  $('#btnComment').attr('disabled', '');
               }
            }
         });
      });

      var startFrom = 1;

      $(document).scroll(function(e) {
         let scrollToBottom = document.body.scrollTop + document.body.clientHeight;
         if (document.body.scrollHeight == scrollToBottom) {
            let postid = $('.comments-list').attr('post-id');
            startFrom += 2;
            $.ajax({
               type: "GET",
               url: "ajax/post/load_comments.php",
               data: {
                  postid,
                  startFrom
               },
               dataType: "text",
               success: function(response) {
                  if ($.trim(response) != '') {
                     $('.comments-list').append(response);
                  }
               }
            });
         }

      });



      $('#btnLike').click(function() {
         let postid = $('.comments-list').attr('post-id');
         $.ajax({
            type: "POST",
            url: "ajax/post/like_post.php",
            data: {
               postid
            },
            dataType:'html',
            success: function(response) {
               $('#btnLike').html(response)
            }
         });
      })

   });
</script>

<?php view('footer.php') ?>