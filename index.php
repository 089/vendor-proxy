<?php
include "config.php";

$path = $_GET["path"];

if(!isset($path)) {
	die();
}

$url = "https://" . $path;
$stringifiedFile = file_get_contents($url);

if(!$stringifiedFile) {
	http_response_code(404);
	die("Couldn't find the requested url " . $url);
}

$prePath = substr($path, 0, strpos($path, "/"));
if(!in_array($prePath, $positiveList)) {
	http_response_code(403);
	die("Couldn't find " . $prePath . " on positive list.");
}

$headerKeysToCopy = array(
	"Content-Length",
	"Access-Control-Allow-Origin:",
	"Access-Control-Expose-Headers:",
	"Cache-Control:",
	"Cross-Origin-Resource-Policy:",
	"X-Content-Type-Options:",
	"Strict-Transport-Security:",
	"Content-Type:",
	"Accept-Ranges:",
	"Vary:"
);

header("X-Original-URL: " . $url);

foreach($http_response_header as $headerLine) {

	$colonPos = strpos($headerLine, ":");
	if($colonPos === false) {
		continue;
	}

	# set copied header
	$headerKey = substr($headerLine, 0,  $colonPos + 1);
	if(stripos($headerLine, $headerKey) === 0) {
		header($headerLine);
	}
}

echo $stringifiedFile;
?>
