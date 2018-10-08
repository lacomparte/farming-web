<?php
include_once("./server_side/function.php");

define("COMPANY_NAME", "주식회사 쉐어러블");
define("DIARY_TYPE_MGMT", 3);
define("DIARY_TYPE_PEST", 7);

$pageTitle = "스마트 영농일지 파밍노트";
$description = "노트에 영농일지를 작성하기란 쉽지 않습니다. 파밍노트를 통해 쉽고 정확하게 영농일지를 작성하고 인증기관에 제출하세요.";
$pageUrl = "http://".$_SERVER['HTTP_HOST'];
$imageUrl = "http://".$_SERVER['HTTP_HOST']."/images/logo";

// $actualLink = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$pathIdentifier = array_key_exists("p", $_GET) ? $_GET["p"] : NULL;
if($pathIdentifier == "demo") { // 공개된 일지 둘러보기에 대한 title, description 처리
	$pageTitle = "공개된 일지 둘러보기";
	$description = "파밍노트에 공개로 등록된 영농일지입니다.";
}

function processGoogleBot() {
	global $pathIdentifier;

	$userAgent = $_SERVER['HTTP_USER_AGENT'];
	$googleBot = strpos($userAgent, "Googlebot");

	if(!$googleBot) {
		$googleBot = !empty($_GET["googlebot"]);
	}

	if(empty($googleBot)) {
		return false;
	}
	
	if(empty($pathIdentifier)) {
		processGoogleBotMain();
		return true;
	} else if($pathIdentifier == "demo") {
		processGoogleBotDemo();
		return true;
	} else if($pathIdentifier == "diary_item_detail") {
		processGoogleBotDiaryItemDetail();
		return true;
	}

	return false;
}

function processGoogleBotMain() {
	global $pageTitle, $description, $pageUrl, $imageUrl;
	include_once("./server_side/main.php");
}

function processGoogleBotDemo() {
	global $pageTitle, $description, $pageUrl, $imageUrl;
	include_once("./server_side/demo.php");
}

function processGoogleBotDiaryItemDetail() {
	global $pageTitle, $description, $pageUrl, $imageUrl;
	include_once("./server_side/diary_item_detail.php");
}

?>