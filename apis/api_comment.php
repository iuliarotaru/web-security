<?php
require_once("globals.php");
session_start();

if( $_POST["csrf"] != $_SESSION["csrf"] ){
    http_response_code(400);
    echo "mmm... you are doing a CSRF";
    exit();
}

if (!isset($_SESSION['uuid'])) {
    $error_message = 'Unauthorized';
    http_response_code(400);
    echo $error_message;
    exit();
}

if (!isset($_POST['post-id'])) {
    http_response_code(400);
    echo 'Invalid id';
    exit();
}

if (!isset($_POST['comment-text'])) {
    http_response_code(400);
    echo 'Invalid comment';
    exit();
}

// ----------------------------------------------------------
// Connect to the db and insert values
require_once(__DIR__ . '/../db/db.php');
try {
    $comment = $_POST['comment-text'];
    $post_id = (int)$_POST['post-id'];
    $parent_id = (int)$_POST['parent-id'];

    $q = $db->prepare('INSERT INTO comments VALUES(DEFAULT, :comment_text, :time, :user_id, :post_id, :parent_id)');
    $q->bindValue(':comment_text', $comment);
    $q->bindValue(':time', date('Y-m-d H:i:s'));
    $q->bindValue(':user_id', $_SESSION['uuid']);
    $q->bindValue(':post_id', $post_id);
    $q->bindValue(':parent_id', $parent_id);
    $q->execute();

    if (!$q->rowCount()) {
    http_response_code(400);
    echo 'Error replying to this post';
    exit();
    }

    http_response_code(200);
    _out($db->lastInsertId());
    exit();
} catch (PDOException $ex) {
    echo $ex->getMessage();
}
