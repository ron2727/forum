 <?php
    require_once '../helper/functions.php';
    require_once '../database/connection.php';

    session_start();

    $user_id = htmlspecialchars($_GET['id']);

    // get user info
    $sql = "SELECT users.id, users.firstname, users.lastname, users.profile_photo, covers.linear_gradient
            FROM users
            INNER JOIN covers ON users.cover_id = covers.id
            WHERE users.id = :id";
    $statement = $pdo->prepare($sql);
    $statement->bindParam(":id", $user_id, PDO::PARAM_INT);
    $statement->execute();
    $user = $statement->fetch(PDO::FETCH_NAMED);

    //get user posts
    $sql = "SELECT * FROM post WHERE user_id = :user_id ORDER BY created_at DESC LIMIT 3";
    $statement = $pdo->prepare($sql);
    $statement->bindParam(":user_id", $user['id'], PDO::PARAM_INT);
    $statement->execute();
    $result = $statement->fetchAll();

    ?>

 <?php view('header.php', 'Home') ?>

 <?php include_once '../components/navigation.php' ?>
 <?php
    if (isset($_SESSION['user_id'])) {
        include_once '../components/upload_profile_modal.php';
        include_once '../components/change_cover_modal.php';
    }
    ?>
 <style>
     .user-image {
         width: 100%;
         height: 100%;
         border-radius: 50%;
         object-fit: cover;
     }

     .user-image-con,
     .user-name {
         transform: translateY(-50%);
     }

     .user-name {
         transform: translateY(-150%);
     }

     .cover-photo {
         height: 10rem;
     }

     .btn-change-cover {
         display: none;
         cursor: pointer;
         background-color: rgba(255, 255, 255, 0.4);
         opacity: 0;
         transition: opacity, 0.5s;
         /* backdrop-filter: blur(100%); */
     }

     .cover-photo:hover .btn-change-cover {
         display: flex;
     }

     .btn-change-cover:hover {
         opacity: 1;
     }

     /* Mobile Responsive */
     .profile-container {
         width: 98%;
     }

     .post-item {
         padding-top: 0.8rem;
     }

     .user-image-con {
         width: 60px;
         height: 60px
     }

     .image-profile {
         width: 40px;
         height: 40px
     }

     .btn-upload-prof {
         bottom: 0px;
         right: 0;
         padding: 0.3rem;
     }

     .btn-upload-prof i {
         font-size: 0.5rem;
     }

     .user-name,
     .post-user-name {
         font-size: 0.8rem;
     }

     .date-post {
         font-size: 0.6rem;
     }

     .post-title {
         font-size: 1.1rem;
     }

     .post-content {
         font-size: 0.9rem;
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

     @media (min-width: 768px) {
         .user-name {
             font-size: 1rem;
         }
     }

     @media (min-width: 1024px) {
         .profile-container {
             width: 50%;
         }

         .post-item {
             padding: 1.5rem;
         }

         .user-name {
             font-size: 1.2rem;
         }

         .post-user-name {
             font-size: 1.1rem;
         }

         .user-image-con {
             width: 80px;
             height: 80px
         }

         .image-profile {
             width: 60px;
             height: 60px
         }

         .btn-upload-prof {
             bottom: 0;
             right: 0;
             padding: 0.5rem;
         }

         .btn-upload-prof i {
             font-size: 0.7rem;
         }

         .filter-text {
             font-size: 1rem;
         }
     }
 </style>

 <div class=" main-container mb-5 d-flex justify-content-center" style="margin-top:80px;">
     <div class="profile-container">
         <div class="user-profile d-flex flex-column align-items-center">
             <div class="cover-photo border w-100" style="background-image: linear-gradient(<?php echo $user['linear_gradient'] ?>);">
                 <?php if (isset($_SESSION['user_id'])) : ?>
                     <?php if ($_SESSION['user_id'] == $user_id) : ?>
                         <div class="btn-change-cover flex-column justify-content-center h-100" data-bs-toggle="modal" data-bs-target="#coverBackgroundModal">
                             <p class=" text-center m-0 p-0 fw-semibold">Change Background</p>
                             <p class="text-center m-0 p-0 fw-semibold" style="font-size: 1.3rem"><i class="bi bi-paint-bucket"></i></p>
                         </div>
                     <?php endif; ?>
                 <?php endif; ?>
             </div>
             <div class="user-image-con position-relative border bg-secondary rounded-circle">
                 <img src="uploaded_images/<?php echo $user['profile_photo'] ?>" alt="profile" class="user-image">
                 <?php if (isset($_SESSION['user_id'])) : ?>
                     <?php if ($_SESSION['user_id'] == $user_id) : ?>
                         <button class="btn-upload-prof btn position-absolute border bg-white rounded-circle" data-bs-toggle="modal" data-bs-target="#postProfileModal">
                             <i class="bi bi-camera"></i>
                         </button>
                     <?php endif; ?>
                 <?php endif; ?>
             </div>
             <h4 class="user-name fw-bold"><?php echo $user['firstname'] . ' ' . $user['lastname'] ?></h4>
         </div>
         <div class="user-post-list px-3" user-id="<?php echo $_GET['id'] ?>">
             <?php foreach ($result as $post) : ?>
                 <a href="post.php?id=<?php echo $post['id'] ?>" class=" nav-link">
                     <div class="post-item row my-3" style="background:#d9d9d9">
                         <div class="col-sm-11">
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

                            if ($statement->rowCount() == 1) {
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
             <!-- <div class=" pagination-container d-flex justify-content-end">
      <div class="page-list d-flex align-items-center">
        <a href="#" class="prev-page nav-link mx-2"><i class="bi bi-chevron-left"></i></a>
        <div class="page-numbers d-flex">
          <a href="#" class=" nav-link mx-1 px-2 py-1 text-white text-center" style="width: 35px;background: #6d28d9">1</a>
          <a href="#" class=" nav-link mx-1 px-2 py-1 text-center border" style="width: 35px">2</a>
          <a href="#" class=" nav-link mx-1 px-2 py-1 text-center border" style="width: 35px">3</a>
        </div>
        <a href="#" class="next-page nav-link mx-2"><i class="bi bi-chevron-right"></i></a>
      </div>
    </div> -->
         </div>
     </div>
 </div>

 <script>
     $(document).ready(function() {
         var startFrom = 1;

         $(document).scroll(function(e) {
             let scrollToBottom = document.body.scrollTop + document.body.clientHeight;
             if (document.body.scrollHeight == scrollToBottom) {
                 let userid = $('.user-post-list').attr('user-id');
                 startFrom += 2;
                 $.ajax({
                     type: "GET",
                     url: "ajax/profile/load_post.php",
                     data: {
                         userid,
                         startFrom
                     },
                     dataType: "text",
                     success: function(response) {
                         if ($.trim(response) != '') {
                             $('.user-post-list').append(response);
                         }
                     }
                 });
             }
         });


     });
 </script>
 <?php view('footer.php') ?>