<?php
// discourse 2 telegrambot - gpl3
// cvillavicencio.com

$token = ""; // bot_token
$cid   = ""; // chat_id

function sendMethod($token, $method, $params = array()) {
  $ch = curl_init("https://api.telegram.org/bot$token/$method");
	curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS, ($params));
  curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (X11; Linux i686; rv:32.0) Gecko/20100101 Firefox/40.0');
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
  curl_setopt($ch, CURLOPT_FRESH_CONNECT, TRUE);
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5000);
  curl_setopt($ch, CURLOPT_TIMEOUT, 5000);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
  return curl_exec($ch);
}

if ($_POST){
	$_POST = json_decode(file_get_contents('php://input'), true);
	$info = $_POST["post"];

	$url = 'https://discourse.cuatrolibertades.org/t/'.$info["topic_id"].'/'.$info["post_number"];

	$texto = intval($info["post_number"]) == "1" ? 'Nuevo tema en el foro: '.$info['topic_title'].PHP_EOL.'creado por: '.$info['username'] .'.': 'Nueva respuesta en: '.$info['topic_title'].PHP_EOL.'por: '.$info['username'].'.';
	$texto = PHP_EOL . $texto . PHP_EOL . "url: $url";
	$params = array(
		"chat_id" => $cid,
		"parse_mode" => "HTML",
		"text" => $texto
	);

	sendMethod($token, "sendMessage", $params);
}

?>
