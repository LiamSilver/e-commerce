<?php
include("../enable-cors.php");
require_once "salesDAO.php";
require_once "../db/connection.inc.php";
require_once("../auth/login.php");

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

// Obtém o ID da compra a ser excluída do parâmetro GET
$id = $_GET["id"];

$salesDAO = new SalesDAO(getConnection());

$sale = $salesDAO->getById($id);

if (!$sale) {
    // Se a compra não existe, exibe uma mensagem de erro
    echo "Compra não encontrada.";
} else {
    // Se a compra existe, exclui a compra usando a função delete
    $salesDAO->delete($id);
    echo "Compra excluída com sucesso.";
}

?>