<?php
include("../enable-cors.php");
require_once("../db/connection.inc.php");
require_once("genreDAO.php");
require_once("../auth/login.php");

$genreDAO = new GenreDAO(getConnection());
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

// Obter o corpo da requisição
$json = file_get_contents('php://input');

// Transforma o JSON em um Objeto PHP
$genre = json_decode($json);

// Verifica se o ID do gênero foi fornecido
if (!isset($_GET['id'])) {
    http_response_code(400); // bad request
    echo '{ "message": "O ID do gênero deve ser fornecido." }';
    exit();
}

// Obtém o ID do gênero a ser atualizado
$id = $_GET['id'];
if (!is_numeric($id)) {
    http_response_code(400); // bad request
    echo '{ "message": "O ID do gênero deve ser um valor numérico." }';
    exit();
}

$genreDAO->update($id, $genre->descGenero);

// Retorna o gênero atualizado como JSON
$genre = $genreDAO->getById($id);
header('Content-Type: application/json');
echo json_encode($genre);


?>