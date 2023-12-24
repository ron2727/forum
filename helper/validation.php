<?php



function validation($data){
 
    $sanitize_inputs = [];
    $errors = [];

    foreach ($data as $name => $value) {
        $sanitize_inputs[$name] = htmlspecialchars($value);
    }

    foreach ($sanitize_inputs as $name => $value) {
       if ($name == 'repassword') {
         continue;
       }
       if (is_required($value, $name)) {
          $errors[$name] = is_required($value, $name);
       }
    }
    if (!isset($errors['email'])) {
        if (email($sanitize_inputs['email'])) {
            $errors['email'] = email($sanitize_inputs['email']);
        }
    }
    if (!isset($errors['password'])) {
        if (password($sanitize_inputs['password'])) {
            $errors['password'] = password($sanitize_inputs['password']);
        }
    }
    if ($sanitize_inputs['repassword'] != $sanitize_inputs['password']) {
        $errors['repassword'] = "The password you entered is incorrect";
    }
    
    return [$sanitize_inputs, $errors];
}


function is_required($data, $name){
    if (empty(trim($data))) {
       return "Please input your $name";
    }
    return false;
}

function email($data){
    $is_email_valid = filter_var($data, FILTER_VALIDATE_EMAIL);
    if (!$is_email_valid) {
       return "email is invalid";
    }

    return false;
}

function password($data){
    if (strlen($data) < 8) {
      return "password must be atleast 8 characters";
    }
    
    return false;
}

function hasError($value){
   return !empty($value) ? 'error' : '';
}