<?php

define('token', 'YOUR_BOT:TOKEN');
define('api', 'https://api.telegram.org/bot'.token.'/');

$data = file_get_contents("php://input");
$update = json_decode($data, true);

$message = $update["message"];
$text = $message["text"];
$msgid = $message["message_id"];

$from = $message["from"];
$username = $from["username"];
$first = $from["first_name"];
$last = $from["last_name"];
$cid = $from["id"];

function single(){
  return $message['from']['id'];
}

function group(){
  return $message['chat']['id'];
}

if($message['chat']['type'] == 'private'){
  $cid = $message['from']['id'];
}else if($message['chat']['type'] == 'group' || $msg['chat']['type'] == 'supergroup'){
  $cid = $message['chat']['id'];
}

function is($word,$con)
{
    return strncmp($word, $con, strlen($con)) === 0;
}

function apiRequest($method)
{
    $req = file_get_contents(api.$method);
    return $req;
}

function send($id, $text){
	apiRequest("sendMessage?text=$text&chat_id=$id");
}

function sendReply($id, $text, $mgi){
       apiRequest("sendMessage?text=$text&chat_id=$id&reply_to_message_id=$mgi");
}

function sendPhoto($id, $im, $cap){
	apiRequest("sendPhoto?photo=$im&chat_id=$id&caption=$cap");
}

function tastiera($tasti, $text, $cd){
      $dioporco = array(
    'keyboard' => array(
        $tasti
    )
);

$diocane = json_encode($dioporco);
apiRequest("sendMessage?text=$text&chat_id=$cd&reply_markup=$diocane");
}

?>
