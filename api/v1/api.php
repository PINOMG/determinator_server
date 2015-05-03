<?php
require_once('MyAPI.class.php');

if (!array_key_exists('HTTP_ORIGIN', $_SERVER)) {
    $_SERVER['HTTP_ORIGIN'] = $_SERVER['SERVER_NAME'];
}

try {
    $API = new MyAPI($_REQUEST['request'], $_SERVER['HTTP_ORIGIN']);
    echo successResponse($API, $API->processAPI());
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

function successResponse($API, $input){
	$arr = Array(
		'apiVersion' => $API->getVersion(),
		'method' => $API->getEndpoint().'.'.$API->getMethod(),
		'data' => Array(
			
		)
	);

	if( is_array($input) )
		$arr['data']['items'] = $input;
	else 
		$arr['data']['message'] = $input;

	return json_encode($arr);
}