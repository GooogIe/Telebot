<?php
define('token', 'YOUR_TOKEN');
include 'base.php';

if($text == "/start"){
  send($cid, "Hey $first, thank you for using Telebot!");
}

if ($update["inline_query"]) {
	$array = array(
		array(
			'type' => 'article',
			'id' => 'testid',
			'title' => 'Send Message',
			'description' => "Inline Test",
			'message_text' => "Inline Worked!",
			'parse_mode' => 'Markdown'
		) ,
	);
	answerInlineQuery($inline, $array, 5);
}
?>
