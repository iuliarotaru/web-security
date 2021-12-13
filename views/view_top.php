<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="/css/styles.css" type="text/css">
    <title>APP</title>
</head>

<body>

    <?php
  session_start();
  if (isset($_SESSION['uuid'])) {
    require_once($_SERVER['DOCUMENT_ROOT'] . '/views/view_logged_nav.php');
  } else {
    require_once($_SERVER['DOCUMENT_ROOT'] . '/views/view_nav.php');
  }
  ?>