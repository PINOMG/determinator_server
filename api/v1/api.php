<?php
$api_version = 1.0;

require_once('MyAPI.class.php');

if (!array_key_exists('HTTP_ORIGIN', $_SERVER)) {
    $_SERVER['HTTP_ORIGIN'] = $_SERVER['SERVER_NAME'];
}

try {
    $API = new MyAPI($_REQUEST['request'], $_SERVER['HTTP_ORIGIN']);
    echo $API->processAPI();
} catch (Exception $e) {
    echo errorResponse($API, $e->getMessage(), 1);
}

function errorResponse($API, $message, $code){
	global $api_version;

	echo json_encode(Array(
		'apiVersion' => $api_version,
		'method' => $API->getEndpoint().'.'.$API->getMethod(),
		'error' => Array(
			'code' => $code,
			'message' => $message
		)
	));
}