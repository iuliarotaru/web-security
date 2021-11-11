<?php
session_start();
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
// ----------------------------------------------------------
// Connect to the db and insert values
require_once(__DIR__ . '/../db/db.php');
try {
    $reply = $_POST['reply-content'];

    $q = $db->prepare('INSERT INTO replies VALUES(DEFAULT, :reply_text, :time, :user_id, :post_id)');
    $q->bindValue(':reply_text', $reply);
    $q->bindValue(':time', date('Y-m-d H:i:s'));
    $q->bindValue(':user_id', $_SESSION['uuid']);
    $q->bindValue(':post_id', $_POST['post-id']);
    $q->execute();

    if (!$q->rowCount()) {
    http_response_code(400);
    echo 'Error replying to this post';
    exit();
    }

    http_response_code(200);
    echo $reply;
    exit();
} catch (PDOException $ex) {
    echo $ex->getMessage();
}
