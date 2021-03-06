<?php
try {
  // $DatabaseAccess = parse_ini_file($_SERVER['DOCUMENT_ROOT'] . '/config/access.ini');
  // print_r($DatabaseAccess);
  // $dbpass = $DatabaseAccess['dbpass'];
  // $dbhost = $DatabaseAccess['dbhost'];
  // $dbname = $DatabaseAccess['dbname'];
  // $dbuser = $DatabaseAccess['dbuser'];
  $sDatabaseUserName = 'root';
  $sDatabasePassword = '';
  $sDatabaseConnection = "mysql:host=localhost; dbname=web_security; charset=utf8mb4";
  // $sDatabaseConnection = "mysql:host=$dbhost; dbname=$dbname; charset=utf8mb4";
  $aDatabaseOptions = array(
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ // Array with object
  );
  // $db = new PDO($sDatabaseConnection, $dbuser, $dbpass, $aDatabaseOptions);
  $db = new PDO($sDatabaseConnection, $sDatabaseUserName, $sDatabasePassword, $aDatabaseOptions);
} catch (PDOException $e) {
  echo 'Connectin failed:' . $e->getMessage();
  exit();
}  