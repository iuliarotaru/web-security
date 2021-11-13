<?php
session_start();
require_once("globals.php");
// _is_csrf_valid();

if (!isset($_SESSION['uuid'])) {
    header('Location: /login');
    exit();
}
session_destroy();
$error_message = 'You have been logged out';
header("Location: /login?notification=$error_message");
exit();