 <style>
   .logo-text {
     font-size: 0.8rem;
   }

   .nav-container {
     padding: 0 0.2rem;
   }

   .signup-login {
     display: none;
   }

   #search {
     font-size: 0.6rem;
   }

   .nav-image-profile-con {
     width: 30px;
     height: 30px;
   }

   .nav-image-profile {
     width: 100%;
     height: 100%;
     object-fit: cover;
   }

   @media (min-width: 640px) {
     .logo-text {
       font-size: 1.3rem;
     }

     .nav-container {
       padding-left: 0.5rem;
       padding-right: 0.5rem;
     }

     .signup-login {
       display: flex;
     }

     .signup-login a,
     #search {
       font-size: 0.8rem;
     }
   }

   @media (min-width: 768px) {}

   @media (min-width: 1024px) {
     .nav-container {
       padding-left: 1rem;
       padding-right: 1rem;
     }

     .nav-image-profile-con {
       width: 40px;
       height: 40px;
     }

     .signup-login a,
     #search {
       font-size: 1rem;
     }
   }
 </style>
 <nav class=" fixed-top bg-white border">
   <div class=" nav-container d-flex justify-content-between align-items-center py-2">
     <div class="menus-container">
       <a href="../../myproject/forum/index.php" class="logo-text nav-link fw-bold">K<span style="color: #6d28d9;">FORUM</span></a>
     </div>
     <div class="search-login-profile d-flex align-items-center">
       <form action="../../myproject/forum/search.php" method="get" class="p-0 m-0">
         <div class="input-con border py-1 px-2">
           <input type="text" name="search" id="search" placeholder="Enter Keywords..." class=" border-0" style="outline:none">
           <button type="submit" class=" border-0 bg-white"><i class="bi bi-search"></i></button>
         </div>
       </form>
       <?php if (isset($_SESSION['user_id'])) : ?>
         <div class="login-profile ms-1">
           <div class="dropdown open">
             <div id="profMenu" data-bs-toggle="dropdown" class="nav-image-profile-con">
               <img class="nav-image-profile" src="uploaded_images/<?php echo $_SESSION['profile_photo'] ?>" alt="profile">
             </div>
             <div class="dropdown-menu rounded-0" aria-labelledby="profMenu">
               <span class=" dropdown-header text-center fw-semibold"><?php echo $_SESSION['user_name'] ?></span>
               <p class=" dropdown-divider"></p>
               <a class="dropdown-item" href="../forum/profile.php?id=<?php echo $_SESSION['user_id'] ?>">My Profile</a>
               <a class="dropdown-item" href="../forum/settings.php">User Settings</a>
               <a class="dropdown-item" href="../forum/logout.php">Logout</a>
             </div>
           </div>
         </div>
       <?php else : ?>
         <div class="signup-login ms-4">
           <a href="../forum/signup.php" class=" nav-link border py-2 px-3 mx-1 bg-light text-muted fw-semibold">Signup</a>
           <a href="../forum/login.php" class=" nav-link border py-2 px-3 mx-1 text-white fw-semibold" style="background: #6d28d9;">Login</a>
         </div>
       <?php endif; ?>
     </div>
   </div>
 </nav>