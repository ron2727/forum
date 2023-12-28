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
 
    </div>
</div>
 
<?php view('footer.php') ?>