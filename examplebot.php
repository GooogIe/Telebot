<?php

define('token', 'YOUR_TOKEN');
include 'base.php';

if($text == "/start"){
  send($cid, "Hey $first, thank you for using Telebot by Neon!");
}

?>
