<?php
include("../enable-cors.php");
require_once "userDAO.php";
require_once "../db/connection.inc.php";
require_once("../auth/login.php");

$userDAO = new UserDAO(getConnection());
$authController = new AuthenticationController();

$token = $_GET['token'] ?? '';

if (empty($token)) {
    http_response_code(400); // bad request
    echo json_encode(['message' => 'Token não fornecido']);
    exit();
}

// Decodifica o token JWT e verifica se o usuário é administrador ou está logado
$payload = $authController->decodeJwtToken($token);
if (!$payload || (!isset($payload['admin']) || $payload['admin'] !== 1) || (!isset($payload['is_logged_in']) || !$payload['is_logged_in'])) {
    http_response_code(403); // forbidden
    echo '{ "message": "Você não tem privilégios para acessar essa página." }';
    exit();
}

// Obtém o ID do usuário a ser excluído do parâmetro GET
$id = $_GET["id"];

$user = $userDAO->getById($id);

if (!$user) {
    // Se o usuário não existe, exibe uma mensagem de erro
    echo "Usuário não encontrado.";
} else {
    // Se o usuário existe, exclui o usuário usando a função delete
    $userDAO->delete($id);
    echo "Usuário excluído com sucesso.";
}

?>


