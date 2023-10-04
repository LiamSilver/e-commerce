<?php
include("../enable-cors.php");
require_once "genreDAO.php";
require_once "../db/connection.inc.php";
require_once("../auth/login.php");

$authController = new AuthenticationController();

$token = $_GET['token'] ?? '';

if (empty($token)) {
    http_response_code(400); // bad request
    echo json_encode(['message' => 'Token não fornecido']);
    exit();
}


// Decodifica o token JWT e verifica se o usuário é administrador
$payload = $authController->decodeJwtToken($token);
if (!$payload || (!isset($payload['admin']) || $payload['admin'] !== 1)) {
    http_response_code(403); // forbidden
    echo '{ "message": "Você não tem privilégios para acessar essa página." }';
    exit();
}

// Obtém o ID do gênero a ser excluído do parâmetro GET
$id = $_GET["id"];

$genreDAO = new GenreDAO(getConnection());

$genre = $genreDAO->getById($id);

if (!$genre) {
    // Se o gênero não existe, exibe uma mensagem de erro
    echo "Gênero não encontrado.";
} else {
    // Se o gênero existe, exclui o gênero usando a função delete
    $genreDAO->delete($id);
    echo "Gênero excluído com sucesso.";
}

?>