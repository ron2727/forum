 <?php
require_once '../helper/functions.php';
require_once '../helper/validation.php';
require_once '../database/connection.php';

session_start();

$request_method = strtoupper($_SERVER['REQUEST_METHOD']);

if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

if ($request_method == 'GET') {
    $_SESSION['token'] = bin2hex(random_bytes(35));
}
if ($request_method == 'POST') {
    $token = htmlspecialchars($_POST['token']);
    if ($token == $_SESSION['token']) {
       $email = htmlspecialchars($_POST['email']);
       $password = htmlspecialchars($_POST['password']);
       
       $sql = "SELECT * FROM users WHERE email = :email";

       $statement = $pdo->prepare($sql);
       $statement->bindParam('email', $email, PDO::PARAM_STR);
       $statement->execute();

       $result = $statement->fetch();

       if ($result) {
                if ($result['password'] == md5($password)) {
                    $_SESSION['user_id'] = $result['id'];
                    $_SESSION['user_name'] = $result['firstname']. " " .$result['lastname']; 
                    $_SESSION['profile_photo'] = $result['profile_photo'];
                    
                    header("Location: index.php");
                    exit;
                } else {
                    $errors['password'] = "Password is Incorrect";
                }
         
       } else {
               $errors['email'] = "Email does not email";
            }

    }else {
        header($_SERVER['SERVER_PROTOCOL'] . ' 405 Method Not Allowed');
        exit;
    }
}
?>

<?php view('header.php', 'Login') ?>

<style>
    body {
        /* position: relative; */
        background-image: linear-gradient(140deg, #a21caf 0%, #6d28d9 50%, #1d4ed8 75%);
    } 
    .login-container {
        width: 98%;
    }
    .error{
      border: 1px solid #b91c1c !important;
    }
    @media (min-width: 640px) {
        .login-container {
            width: 75%;
        }
    }
    @media (min-width: 768px) {
        .login-container {
            width: 50%;
        }
    }
    @media (min-width: 1024px) {
        .login-container {
            width: 40%;
        }
    }
</style>
 
<div class="main-container mb-5 d-flex justify-content-center" style="margin-top:80px;">
    <div class="login-container">
        <div class=" form-container border px-3 bg-white">
            <div class="text-center py-3">
                <span class=" fw-bold fs-3">Login to <a href="../../myproject/forum/index.php" class="logo text-decoration-none text-dark">K<span style="color: #6d28d9;">FORUM</span></a></span>
            </div>
            <form action="login.php" method="post">
                <input type="hidden" name="token" value="<?php echo $_SESSION['token']?>">
                <div class="input-con my-2">
                    <input type="text" name="email" class="form-control rounded-0 <?php echo hasError($errors['email'] ?? '')?>" placeholder="Enter Email">
                    <small class=" text-danger"><?php echo $errors['email'] ?? ''?></small>
                </div>
                <div class="input-con my-2">
                    <input type="password" name="password" class="form-control rounded-0 <?php echo hasError($errors['password'] ?? '')?>" placeholder="Enter Password">
                    <small class=" text-danger"><?php echo $errors['password'] ?? ''?></small>
                </div>
                <div class="submit-button-con text-end">
                    <button type="submit" class=" btn rounded-0 fw-semibold text-white" style="background: #6d28d9;">Login</button>
                </div>
                <div class="footer py-2 text-center">
                    <span>Dont have an account? <a href="signup.php" class=" text-decoration-none" style="color: #6d28d9;">Signup</a></span>
                </div>
            </form>
        </div>
    </div>
</div>
 
<?php view('footer.php') ?>