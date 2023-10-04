<?php

include("../enable-cors.php");
require_once("../db/connection.inc.php");
require_once("userDAO.php");
require_once("../auth/login.php");

$userDAO = new UserDAO(getConnection());
$authController = new AuthenticationController();

$token = $_GET['token'] ?? '';

if (empty($token)) {
    http_response_code(400); // bad request
    echo json_encode(['message' => 'Token não fornecido']);
    exit();
}

// Decodifica o token JWT e verifica se o usuário é administrador
$payload = $authController->decodeJwtToken($token);
if (!$payload || !isset($payload['admin']) || $payload['admin'] !== 1) {
    http_response_code(403); // forbidden
    echo '{ "message": "Você não tem privilégios para acessar essa página." }';
    exit();
}

// Obtém o ID do usuário a ser buscado a partir da query string
// Se o ID não for fornecido, busca todos os usuários
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    if (!is_numeric($id)) {
        http_response_code(400); // bad request
        echo '{ "message": "O ID do usuário deve ser um valor numérico." }';
        exit();
    }
    $user = $userDAO->getById($id);
    if (!$user) {
        http_response_code(404); // not found
        echo '{ "message": "Usuário não encontrado." }';
        exit();
    }
    $users = [$user];
} else {
    $users = $userDAO->getAll();
}

$responseBody = '';

try {
    // Converte o array de usuários para JSON
    $responseBody = json_encode($users);
} catch (Exception $e) {
    http_response_code(400);
    $responseBody = '{ "message": "Ocorreu um erro ao tentar executar esta ação. Erro: Código: ' .  $e->getCode() . '. Mensagem: ' . $e->getMessage() . '" }';
}

header('Content-Type: application/json');

print_r($responseBody);
?>