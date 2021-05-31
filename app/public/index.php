<?php
use \Psr\Http\Message\ResponseInterface as Response;
use \Psr\Http\Message\ServerRequestInterface as Request;
use Selective\BasePath\BasePathMiddleware;
use Slim\Factory\AppFactory;
use Slim\Exception\NotFoundException;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../includes/DbOperations.php';



$app = AppFactory::create();
$app->setBasePath('/api/');
$app->addBodyParsingMiddleware();
$app->addRoutingMiddleware();
$errorMiddleware = $app->addErrorMiddleware(true, true, true);

$app->get('/test', function(Request $request, Response $response){
	$response->getBody()->write(json_encode(["status"=>"ok"]));
	return $response
			->withHeader('Content-type', 'application/json')
			->withStatus(422);
});

var_dump($_SERVER);
die;

$app->post('/createuser', function(Request $request, Response $response){
	 if(!haveEmptyParameters(array('username', 'email', 'firstName', 'lastName', 'phoneNumber', 'address', 'password', 'rePassword'), $request, $response)){
		 $request_data = $request->getParsedBody();
		 $username = $request_data['username'];
		 $email = $request_data['email'];
		 $firstName = $request_data['firstName'];
		 $lastName = $request_data['lastName'];
		 $phoneNumber = $request_data['phoneNumber'];
		 $address = $request_data['address'];
		 $password = $request_data['password'];
		 $rePassword = $request_data['rePassword'];

		 $hashPassword = password_hash($password, PASSWORD_DEFAULT);
		 $hashRePassword = password_hash($rePassword, PASSWORD_DEFAULT);

		 $db = new DbOperations;

		 $result = $db -> createUser($username, $email, $firstName, $lastName, $phoneNumber, $address, $hashPassword, $hashRePassword);

		 if($result == USER_CREATED){

			$message = array();
			$message['error'] = false;
			$message['message'] = 'User created successfully';

			$response->getBody()->write(json_encode($message));

			return $response
						->withHeader('Content-type', 'application/json')
						->withStatus(201);

		 }else if($result == USER_FAILURE){

			$message = array();
			$message['error'] = true;
			$message['message'] = 'Some error occurred';

			$response->getBody()->write(json_encode($message));

			return $response
						->withHeader('Content-type', 'application/json')
						->withStatus(422);
		 }else if($result == USER_EXISTS){
			$message = array();
			$message['error'] = true;
			$message['message'] = 'User already exists';

			$response->getBody()->write(json_encode($message));

			return $response
						->withHeader('Content-type', 'application/json')
						->withStatus(422);
		 }
	 }
});

$app->get('/allusers', function(Request $request, Response $response){
	$email = $db = new DbOperations;

	$users = $db->getAllUsers($email);

	$response_data = array();

	$response_data['error'] = false;
	$response_data['users'] = $users;

	$response->getBody()->write(json_encode($response_data));

	return $response
	->withHeader('Content-type', 'application/json')
	->withStatus(200);
});

function haveEmptyParameters($reqired_params, $response){
	$error = false;
	$error_params = '';
	$request_params = $_REQUEST;

	foreach($reqired_params as $param){
		if(!isset($request_params[$param]) || strlen($request_params[$param])<=0){
			$error = true;
			$error_params .= $param . ', ';
		}
	}
	if($error){
		$error_detail = array();
		$error_detail['error'] = true;
		$error_detail['message'] = 'Required parameters ' . substr($error_params, 0, -2) . ' are missing or empty.';
		$response->getBody()->write(json_encode($error_detail));
	}
	return $error;
}

$app->run();