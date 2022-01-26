<?php
require_once(__DIR__.'/db.php');

try{
  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
  $q = $db->prepare('DROP TABLE IF EXISTS login_information');
  $q->execute();
  $q = $db->prepare('CREATE TABLE login_information(
    uuid                SERIAL,
    email               TEXT UNIQUE,
    last_login_attempt  TIMESTAMP,
    number_failed_login INT,
    blocked_at          TIMESTAMP)');
  $q->execute();
  echo 'Table created successfully';
}catch(PDOException $ex){
echo 'Error'.$ex->getMessage();
exit();}