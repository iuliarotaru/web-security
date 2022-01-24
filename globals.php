<?php
//session_start();

// ##############################
function _set_csrf(){
  if( ! isset($_SESSION["csrf"]) ){
    $token = bin2hex(random_bytes(50));
    $_SESSION["csrf"] = $token;
  }
  return $_SESSION["csrf"];
}

// ##############################
function _is_csrf_valid(){
  if( $_POST["csrf"] != $_SESSION["csrf"] ){
    echo "mmm... you are doing a CSRF";
    exit();
  }
}


function _out($data){
    echo htmlspecialchars($data);
}

