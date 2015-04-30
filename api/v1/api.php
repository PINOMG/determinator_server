<?php
require_once('MyAPI.class.php');

if (!array_key_exists('HTTP_ORIGIN', $_SERVER)) {
    $_SERVER['HTTP_ORIGIN'] = $_SERVER['SERVER_NAME'];
}

try {
    $API = new MyAPI($_REQUEST['request'], $_SERVER['HTTP_ORIGIN']);
    echo $API->processAPI();
} catch (Exception $e) {
    echo errorResponse($API, $e->getMessage(), $e->getCode());
}

function errorResponse($API, $message, $code){
	return json_encode(Array(
		'apiVersion' => $API->getVersion(),
		'method' => $API->getEndpoint().'.'.$API->getMethod(),
		'error' => Array(
			'code' => $code,
			'message' => $message
		)
	));
}

function successResponse($API, $message, $items){
	return json_encode(Array(
		'apiVersion' => $API->getVersion(),
		'method' => $API->getEndpoint().'.'.$API->getMethod(),
		'data' => Array(
			'message' => $message,
			'items' => $items
		)
	));
}