<?php

function is_user_logged($user){
    return $_SESSION['user_id'];
}