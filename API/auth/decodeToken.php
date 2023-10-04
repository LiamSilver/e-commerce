<?php
require_once('login.php');
require_once('../enable-cors.php');

$token = $_GET['token'] ?? '';

if (empty($token)) {
    http_response_code(400); // bad request
    echo json_encode(['message' => 'Token não fornecido']);
    exit();
}

$decodedToken = AuthenticationController::decodeJwtToken($token);

if (!$decodedToken) {
    http_response_code(401); // unauthorized
    echo json_encode(['message' => 'Token inválido']);
    exit();
}

http_response_code(200);
header('Content-Type: application/json');
echo json_encode($decodedToken);?>

