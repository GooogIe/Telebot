<?php

define('api', 'https://api.telegram.org/bot'.token.'/');

//tb
$version = "2.7";
echo "Telebot $version by Telebot Team";
//tb

$data = file_get_contents("php://input");
$update = json_decode($data, true);

$message = $update["message"];
$text = $message["text"];
$msgid = $message["message_id"];
$reply = $message["reply_to_message"];
$title = $message["from"]["chat"]["title"];

$chat = $message["chat"];

$from = $message["from"];
$username = $from["username"];
$first = $from["first_name"];
$last = $from["last_name"];
$uid = $from["id"];
$cid = $from["id"];

$cbid = $update["callback_query"]["id"];
$cbdata = $update["callback_query"]["data"];
$cbuid = $update["callback_query"]["from"]["id"];

$inline = $update["inline_query"]["id"];
$msgin = $update["inline_query"]["query"];
$userIDin = $update["inline_query"]["from"]["id"];
$usernamein = $update["inline_query"]["from"]["username"];
$namein = $update["inline_query"]["from"]["first_name"];

$channel = $update["channel_post"];

function loadplugin($name){
	return include "plugins/$name.php";
}

function safeip(){
  $ip = $_SERVER["REMOTE_ADDR"];
  $api = json_decode(file_get_contents("http://api.lgtc.ga/geoip?ip=$ip"), true);
  
  if($api["org"] == "Telegram Messenger Network"){
    return true;
  }
  return false;
}

function type($cha){
  return $cha["type"];
}

if($message['chat']['type'] == 'private'){
  $cid = $message['from']['id'];
}else if($message['chat']['type'] == 'group' || $message['chat']['type'] == 'supergroup'){
  $cid = $message['chat']['id'];
}

function is($word,$con)
{
    return strncmp($word, $con, strlen($con)) === 0;
}

function apiRequest($method, $var)
{
   	 $ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, api.$method);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $var);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$req = curl_exec($ch);
	curl_close($ch);
	return $req;
}

function pwrRequest($method)
{
       	 $ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "https://api.pwrtelegram.xyz/bot".token."/".$method);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $var);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$req = curl_exec($ch);
	curl_close($ch);
	return $req;
}

function deleteMessages($cd, $ms){
    return pwrRequest("deleteMessages", "chat_id=$cd&ids=" . json_encode($ms));
}

function getMe(){
  return json_decode(apiRequest("getMe"))["result"];
}

function send($id, $text, $mark, $webp, $reply_id, $hk){
	if(strpos($text, "\n")){
		$text = urlencode($text);
	}
	
	$r = "text=$text&chat_id=$id";
	
	if($mark == true){
		$r .= "&parse_mode=Markdown";
	}
	
	if($webp == false){
		$r .= "&disable_web_page_preview=true";
	}
	
	if(isset($reply_id)){
		$r .= "&reply_to_message_id=$reply_id";
	}
	
	if($hk == true){
		$r .= "&remove_keyboard=true";
	}
	
	apiRequest("sendMessage", $r);
	
}

function sendPhoto($id, $im, $cap){
	return apiRequest("sendPhoto", "photo=$im&chat_id=$id&caption=$cap");
}

function sendAudio($id, $au, $ti){
	return apiRequest("sendAudio", "audio=$au&chat_id=$id&title=$ti");
}

function sendVoice($id, $au, $ti){
	return apiRequest("sendVoice", "audio=$au&chat_id=$id&title=$ti");
}

function sendDocument($id, $dc, $ti){
	return apiRequest("sendDocument", "document=$dc&chat_id=$id&caption=$ti");
}

function keyboard($tasti, $text, $cd){
	$dioporco = $tasti;
	$diocane = json_encode($dioporco);
	
	if(strpos($text, "\n")){
		$text = urlencode($text);
	}
	
	apiRequest("sendMessage", "text=$text&chat_id=$cd&reply_markup=$diocane");
}

function callback($up){
  return $up["callback_query"];
}

function newMember($up){
  return $up["new_chat_member"];
}

function leftMember($up){
  return $up["left_chat_member"];	
}

function ban($kid, $cd){
  apiRequest("kickChatMember", "chat_id=$kid&user_id=$cd");
}

function unban($kid, $cd){
  apiRequest("unbanChatMember", "chat_id=$kid&user_id=$cd");
}

function callbackanswer($id, $text, $alert){
  apiRequest("answerCallbackQuery", "callback_query_id=$id&show_alert=$alert&text=$text");
}

function edit($cd, $mid, $tx, $inline){
	if(strpos($tx, "\n")){
		$tx = urlencode($tx);
	}
	
	if($inline == false){
            apiRequest("editMessageText", "chat_id=$cd&message_id=$mid&text=$tx");
	}else{
            apiRequest("editMessageText", "chat_id=$cd&inline_message_id=$mid&text=$tx");
	}
}

function forward($id, $frm, $mid){
  return apiRequest("forwardMessage", "chat_id=$id&from_chat_id=$frm&message_id=$mid");
}

function getAdmins($cha){
   $req = apiRequest("getChatAdministrators", "chat_id=$cha");
   $admins = json_decode($req, true);
   $idlist = array();
	
   foreach($admins['result'] as $adm){
	   $dd = $adm["user"];
	   array_push($idlist, $dd);
   }

   $a = $idlist;
   $robe = array();
   foreach($a as $koko){
     array_push($robe, $koko["id"]);
   }
	
  return $robe;
}

function getCreator($cha){
   $req = apiRequest("getChatAdministrators", "chat_id=$cha");
   $j = json_decode($req, true);
   foreach($j as $a){
	$x = array();
	array_push($x, $a);
    }

    $i = 0;
    foreach($x as $d){
        foreach($d as $q){
	    if($q["status"] != "creator"){
		    $i += 1;
		}else{
		    return array_diff($q["user"], [$q[$i]]);
		}
	    }
        }

}
		   

function inlineKeyboard($menud, $chat, $tx){
$menu = $menud;
	
	if(strpos($tx, "\n")){
		$tx = urlencode($tx);
	}
	
	$d2 = array(
		"inline_keyboard" => $menu,
	);
	
	$d2 = json_encode($d2);
	
	return apiRequest("sendmessage", "chat_id=$chat&text=$tx&reply_markup=$d2");
}

function answerInlineQuery($in, $arr, $ct = 5) {
	$json = json_encode($array);
	return apiRequest("answerInlineQuery", "inline_query_id=$inline&results=$json&cache_time=$ct");
}

function sendInvoice($ch, $t, $d, $pl, $pt, $sp, $cr, $prc){
	apiRequest("sendInvoice", "chat_id=$cd&title=$t&description=$d&payload=$pl&provider_token=$pt&start_parameter=$sp&currency=$cr&prices=$prc");
}

?>
