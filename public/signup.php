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
    [$inputs, $errors] = validation($_POST);
    if ($inputs['token'] == $_SESSION['token']) {
        if (!$errors) {
            date_default_timezone_set('Asia/Manila');
            $date_time = date('m/d/Y H:i');
            $password = md5($inputs['password']);
            $sql = 'INSERT INTO users(firstname, lastname, email, password, created_at)
                    VALUE(:firstname, :lastname, :email, :password, :created_at)';
            $statement = $pdo->prepare($sql);

            $success = $statement->execute([
                                     'firstname' => $inputs['firstname'],
                                     'lastname' => $inputs['lastname'],
                                     'email' => $inputs['email'],
                                     'password' => $password, 
                                     'created_at' => $date_time,
                                  ]);
            if (!$success) {
                header($_SERVER['SERVER_PROTOCOL'] . ' Something went wrong...');
                exit;
            }else {
                $_SESSION['user_id'] = $pdo->lastInsertId();
                $_SESSION['user_name'] = $inputs['firstname'] . " " . $inputs['lastname'];
                $_SESSION['profile_photo'] = 'default_profile.png';

                header("Location: index.php");
                exit;
            }
        } 
    }else {
        header($_SERVER['SERVER_PROTOCOL'] . ' 405 Method Not Allowed');
        exit;
    }
}
?>

<?php view('header.php', 'Signup') ?>

<style>
    body { 
        background-image: linear-gradient(140deg, #a21caf 0%, #6d28d9 50%, #1d4ed8 75%) !important;
    } 
    .signup-container {
        width: 98%;
    }
    .error{
      border: 1px solid #b91c1c !important;
    }
    @media (min-width: 640px) {
        .signup-container {
            width: 75%;
        }
    }
    @media (min-width: 768px) {
        .signup-container {
            width: 50%;
        }
    }
    @media (min-width: 1024px) {
        .signup-container {
            width: 40%;
        }
    }
</style>

<div class="main-container mb-5 d-flex justify-content-center" style="margin-top:80px;">
    <div class="signup-container">
        <div class=" form-container border px-3 bg-white">
            <div class="text-center py-3">
                <span class=" fw-bold fs-3">Signup to <a href="../../myproject/forum/index.php" class="logo text-decoration-none text-dark">K<span style="color: #6d28d9;">FORUM</span></a></span>
            </div>
            <form action="signup.php" method="post">
                <input type="hidden" name="token" value="<?php echo $_SESSION['token']?>">
                <div class="input-con my-2">
                    <input type="text" name="firstname" class="form-control rounded-0 <?php echo hasError($errors['firstname'] ?? '')?>" placeholder="Enter Firstname...">
                    <small class=" text-danger"><?php echo $errors['firstname'] ?? ''?></small>
                </div>
                <div class="input-con my-2">
                    <input type="text" name="lastname"  class="form-control rounded-0 <?php echo hasError($errors['lastname'] ?? '')?>" placeholder="Enter Lastname...">
                    <small class=" text-danger"><?php echo $errors['lastname'] ?? ''?></small>
                </div>
                <div class="input-con my-2">
                    <input type="text" name="email"  class="form-control rounded-0 <?php echo hasError($errors['email'] ?? '')?>" placeholder="Enter Email...">
                    <small class=" text-danger"><?php echo $errors['email'] ?? ''?></small>
                </div>
                <div class="input-con my-2">
                    <input type="password" name="password"  class="form-control rounded-0 <?php echo hasError($errors['password'] ?? '')?>" placeholder="Enter Password">
                    <small class=" text-danger"><?php echo $errors['password'] ?? ''?></small>
                </div>
                <div class="input-con my-2">
                    <input type="password" name="repassword" class="form-control rounded-0 <?php echo hasError($errors['repassword'] ?? '')?>" placeholder="Re-Enter Password...">
                    <small class=" text-danger"><?php echo $errors['repassword'] ?? ''?></small>
                </div>
                <div class="submit-button-con text-end">
                    <button type="submit" class=" btn rounded-0 fw-semibold text-white" style="background: #6d28d9;">Signup</button>
                </div>
                <div class="footer py-2 text-center">
                    <span>Have an account? <a href="login.php" class=" text-decoration-none" style="color: #6d28d9;">Login</a></span>
                </div>
            </form>
        </div>
    </div>
</div>
 
<?php view('footer.php') ?>