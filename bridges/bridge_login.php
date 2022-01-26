<?php
session_start();
require_once("globals.php");
_is_csrf_valid();
// ----------------------------------------------------------
// Backend Login validation

//Validate email
if (!filter_var($_POST['user_email'], FILTER_VALIDATE_EMAIL)) {
    $error_message = 'Invalid email or password';
    header("Location: /login?error=$error_message");
    exit();
}
//Validate password - at least 8 characters, one uppercase, one number, one special character
// $uppercase = preg_match('@[A-Z]@', $_POST['user_password']);
// $lowercase = preg_match('@[a-z]@', $_POST['user_password']);
// $number    = preg_match('@[0-9]@', $_POST['user_password']);
// $specialChars = preg_match('@[^\w]@', $_POST['user_password']);

// if(!$uppercase || !$lowercase || !$number || !$specialChars || strlen($_POST['user_password']) < 8) {
//     $error_message = 'Invalid email or password';
//     header("Location: /login?error=$error_message");
//     exit();
// }

// ----------------------------------------------------------
// Connect to db, check if the user exists, start session
require_once(__DIR__ . '/../db/db.php');


try {
    $q = $db->prepare('SELECT * FROM users WHERE email = :email');
    $q->bindValue(':email', $_POST['user_email']);
    $q->execute();
    $user = $q->fetch();

    $user_email = $user->email;

    //If the user is not found in the database
    if (!$user) {
        $error_message = 'Your email or password are not correct. Try again';
        header("Location: /login?error=$error_message");
        exit();
    }

    //If the user is found in the database

    //If user is not active
    if ($user->active == 0) {
        $error_message = 'Your account has been deactivated';
        header("Location: /login?error=$error_message");
        exit();
    }
    //If user is not verified
    if ($user->verified == 0) {
        $error_message = 'You have to verify your account first';
        header("Location: /login?error=$error_message");
        exit();
    }

    $q = $db->prepare('SELECT * FROM login_information WHERE email = :email');
    $q->bindValue(':email', $user_email);
    $q->execute();
    $email_found = $q->fetch();

    //If password is incorrect
    if (!password_verify($_POST['user_password'], $user->password)) {

        //If the email was not found in the login_information table
        if(!$email_found) {
            $q = $db->prepare('INSERT INTO login_information (email, last_login_attempt, number_failed_login ) VALUES(:email, :last_login_attempt, :number_failed_login)');
            $q->bindValue(':email', $user_email);
            $q->bindValue(':last_login_attempt', date("Y-m-d H:i:s", time()));
            $q->bindValue(':number_failed_login', 1);
            $q->execute();
        } else {
            //If the last failed login happened in less than 5 minutes (it's consecutive)
            if (round((strtotime(date("Y-m-d H:i:s", time())) - strtotime($email_found->last_login_attempt)) / 60) < 5) {
                //Check if the email is blocked
                if (round((strtotime(date("Y-m-d H:i:s", time())) - strtotime($email_found->blocked_at)) / 60) < 5) {
                    $error_message = 'Your account has been blocked';
                    header("Location: /login?error=$error_message");
                    exit();
                }
                //If the email it's not blocked
                else {
                    //If the email already has 2 failed logins
                    if ($email_found->number_failed_login == 2) {
                        $q = $db->prepare('UPDATE login_information SET last_login_attempt=:last_login_attempt, number_failed_login=:number_failed_login, blocked_at=:blocked_at WHERE email=:email');
                        $q->bindValue(':email', $user_email);
                        $q->bindValue(':last_login_attempt', date("Y-m-d H:i:s", time()));
                        $q->bindValue(':number_failed_login', 0);
                        $q->bindValue(':blocked_at', date("Y-m-d H:i:s", time()));
                        $q->execute();

                        $error_message = 'Please try again in 5 minutes';
                        header("Location: /login?error=$error_message");
                        exit();
                    }
                    //If it has less than 2 failed logins
                    else {
                        $q = $db->prepare('UPDATE login_information SET last_login_attempt=:last_login_attempt, number_failed_login=:number_failed_login WHERE email=:email');
                        $q->bindValue(':email', $user_email);
                        $q->bindValue(':last_login_attempt', date("Y-m-d H:i:s", time()));
                        $q->bindValue(':number_failed_login', $email_found->number_failed_login + 1);
                        $q->execute();
                    }
                }
            }
            //If it is not consecutive
            else {
                $q = $db->prepare('UPDATE login_information SET last_login_attempt=:last_login_attempt, number_failed_login=:number_failed_login WHERE email=:email');
                $q->bindValue(':email', $user_email);
                $q->bindValue(':last_login_attempt', date("Y-m-d H:i:s", time()));
                $q->bindValue(':number_failed_login', 1);
                $q->execute();
            }

        }
        $error_message = 'Your email or password are not correct';
        header("Location: /login?error=$error_message");
        exit();
    }
    if (round((strtotime(date("Y-m-d H:i:s", time())) - strtotime($email_found->blocked_at)) / 60) < 5) {
        $error_message = 'Your account has been blocked';
        header("Location: /login?error=$error_message");
        exit();
    }

    //Logged in successfully
    session_start();
    $_SESSION['uuid'] = $user->uuid;
    $_SESSION['role'] = $user->user_role;
    if ($user->user_role == 1) {
        header('Location: /user');
        exit();
    } else if ($user->user_role == 2) {
        header('Location: /therapist');
        exit();
    }
} catch (PDOException $ex) {
    echo $ex;
}