<?php

require_once __DIR__ . '/app/config/database.php';
require_once __DIR__ . '/app/helpers/system.php';

$conn = open_connection();
var_dump($conn);

$password = hash_password('member');
var_dump($password);

// $sql = "UPDATE `accounts` SET `password` = '{$password}', `email` = 'jeromesavc@gmail.com' 
//   WHERE `account_id` = 1 LIMIT 1";

$sql = "UPDATE `accounts` SET `password` = '{$password}', `username` = 'member', `email` = 'member@gmail.com'
  WHERE `account_id` = 18 LIMIT 1";
$stmt = $conn->prepare($sql);
$result = $stmt->execute();
var_dump($result);
