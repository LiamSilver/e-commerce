<?php
include("../enable-cors.php");
require_once "bookDAO.php";
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

// Obtém o ID do livro a ser excluído do parâmetro GET
$id = $_GET["id"];

$bookDAO = new BookDAO(getConnection());

$book = $bookDAO->getById($id);

if (!$book) {
    // Se o livro não existe, exibe uma mensagem de erro
    echo "Livro não encontrado.";
} else {
    // Se o livro existe, exclui o livro usando a função delete
    $bookDAO->delete($id);
    echo "Livro excluído com sucesso.";
}

?>

?>