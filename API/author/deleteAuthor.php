<?php
include("../enable-cors.php");
require_once "authorDAO.php";
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
if (!$payload || (!isset($payload['admin']) || $payload['admin'] !== 1)) {
    http_response_code(403); // forbidden
    echo '{ "message": "Você não tem privilégios para acessar essa página." }';
    exit();
}

// Obtém o ID do autor a ser excluído do parâmetro GET
$id = $_GET["id"];

$authorDAO = new AuthorDAO(getConnection());

$author = $authorDAO->getById($id);

if (!$author) {
    // Se o autor não existe, exibe uma mensagem de erro
    echo "Autor não encontrado.";
} else {
    // Se o autor existe, exclui o autor usando a função delete
    $authorDAO->delete($id);
    echo "Autor excluído com sucesso.";
}

?>