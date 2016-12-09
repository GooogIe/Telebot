<?php

define('api', 'https://api.telegram.org/bot'.token.'/');

$data = file_get_contents("php://input");
$update = json_decode($data, true);

$message = $update["message"];
$text = $message["text"];
$msgid = $message["message_id"];
$reply = $message["reply_to_message"];

$from = $message["from"];
$username = $from["username"];
$first = $from["first_name"];
$last = $from["last_name"];
$cid = $from["id"];

if($message['chat']['type'] == 'private'){
  $cid = $message['from']['id'];
}else if($message['chat']['type'] == 'group' || $message['chat']['type'] == 'supergroup'){
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

function sendMark($id, $text){
	apiRequest("sendMessage?text=$text&chat_id=$id&parse_mode=Markdown&disable_web_page_preview=true");
}

function sendReply($id, $text, $mgi){
       apiRequest("sendMessage?text=$text&chat_id=$id&reply_to_message_id=$mgi");
}

function sendPhoto($id, $im, $cap){
	apiRequest("sendPhoto?photo=$im&chat_id=$id&caption=$cap");
}

function sendAudio($id, $au, $ti){
	apiRequest("sendAudio?audio=$au&chat_id=$id&title=$ti");
}

function keyboard($tasti, $text, $cd){
      $dioporco = array(
    'keyboard' => array(
        $tasti
    )
);

$diocane = json_encode($dioporco);
apiRequest("sendMessage?text=$text&chat_id=$cd&reply_markup=$diocane");
}

function newMember($up){
  return $message["new_chat_member"];
}

function leftMember($up){
  return $message["left_chat_member"];	
}

function ban($kid, $cd){
  apiRequest("kickChatMember?chat_id=$kid&user_id=$cd");
}

function unban($kid, $cd){
  apiRequest("unbanChatMember?chat_id=$kid&user_id=$cd");
}

?>
