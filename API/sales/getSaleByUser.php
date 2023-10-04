<?php
include("../enable-cors.php");
require_once("../db/connection.inc.php");
require_once("salesDAO.php");
require_once("../auth/login.php");

$salesDAO = new SalesDAO(getConnection());
$authController = new AuthenticationController();

$token = $_GET['token'] ?? '';

if (empty($token)) {
    http_response_code(400); // bad request
    echo json_encode(['message' => 'Token não fornecido']);
    exit();
}

$payload = $authController->decodeJwtToken($token);

if ((!isset($payload['is_logged_in']) || !$payload['is_logged_in'])) {
    http_response_code(403); // forbidden
    echo '{ "message": "Você não tem privilégios para acessar essa página." }';
    exit();
}

$codUsuario = $payload['id'];
$sales = $salesDAO->getByUser($codUsuario);

if (empty($sales)) {
    echo '{ "message": "Não foram encontradas compras para este usuário." }';
    exit();
}

header('Content-Type: application/json');
echo json_encode($sales);

?>