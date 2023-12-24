<?php
require_once '../database/connection.php';

if (isset($_POST['comment'])) {
   $user_id = htmlspecialchars($_POST['user_id']);
   $post_id = htmlspecialchars($_POST['post_id']);
   $sanitize_comment = htmlspecialchars($_POST['comment']);
   
}
