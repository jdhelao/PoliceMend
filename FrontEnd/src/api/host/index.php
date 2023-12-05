<?php
header("Access-Control-Allow-Origin: *");
//header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Access-Control-Request-Headers: X-Requested-With, accept, content-type");
/*
header('Access-Control-Allow-Methods: POST, GET, PUT, OPTIONS, PATCH, DELETE');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Headers: Authorization, Content-Type, x-xsrf-token, x_csrftoken, Cache-Control, X-Requested-With');
*/
print_r('zzzz');
if (isset($_SERVER['HTTP_ORIGIN'])) {
	header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
	header('Access-Control-Allow-Credentials: true');
	header('Access-Control-Max-Age: 3600');
}
include 'clsGetData.php';
parse_str($_SERVER['QUERY_STRING'], $query_string);
$body = json_decode(file_get_contents('php://input'));
print_r($_SERVER);
print_r('xxxxx');
print_r($body);

// get request method
$method = $_SERVER['REQUEST_METHOD'];

if (!isset($body) || !isset($body->id) || !isset($body->parameters)) {
	echo json_encode($body);
	http_response_code(400);
	exit();
}
else if ($method == 'POST') {
	$body->parameters = base64_decode($body->parameters);
	$associative = (isset($body->associative)?$body->associative:0);
	$fromCache = (isset($body->cache)?$body->cache:false);
	///header("HTTP/1.1 200");
	//$xx = clsGetData2::get_DataSet(32,'3,"1"',1);
	//$xx = clsGetData::get_DataSet(85,'3,1',3,true);
	//echo json_encode('AAA' /*. var_dump($body)*/ . 'ZZZ');
	/*
	if (isset($xx) && is_array($xx) && (int)sizeof($xx)>0) {
		//echo json_encode($xx,JSON_PRETTY_PRINT);
		echo json_encode('AAA' . var_dump($body) . 'ZZZ');
		http_response_code(200);
	}
	else {
		http_response_code(503);
	}
	*/
	//response(200,"Product Found","zzzzzzzzzzzzzzzzzzz");
	print_r('aaa');
	print_r($body->id);
	print_r('bbb');
	print_r($body->parameters);
	print_r('ccc');
	print_r($associative);
	print_r('ddd');
	print_r($fromCache);
	print_r('eee');
	$data = clsGetData::get_DataSet($body->id,$body->parameters,$associative,$fromCache);
	print_r($data);
	print_r('fff');
	
	if (isset($data) && is_array($data)) {
		echo json_encode($data);
		http_response_code(200);
	}
	else {
		http_response_code(503);
	}
}

function response($status,$status_message,$data)
{
	header("HTTP/1.1 ".$status);
	
	$response['status']=$status;
	$response['status_message']=$status_message;
	$response['data']=$data;
	
	$json_response = json_encode($response,JSON_PRETTY_PRINT);
	echo $json_response;
	http_response_code($status);
}
exit();
?>