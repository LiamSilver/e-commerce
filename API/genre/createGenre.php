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
if (!$payload || (!isset($payload['admin']) || $payload['admin'] !== 1) ) {
    http_response_code(403); // forbidden
    echo '{ "message": "Você não tem privilégios para acessar essa página." }';
    exit();
}

// Obter o corpo da requisição
$json = file_get_contents('php://input');

// Transformar o JSON em um objeto PHP
$data = json_decode($json);

// Extrair os dados do gênero do objeto $data
$descGenero = $data->descGenero;

$responseBody = '';

try {
    // Inserir o novo gênero utilizando a classe GenreDAO
    $id = $genreDAO->create($descGenero);

    // Buscar o gênero recém-criado pelo ID utilizando a classe GenreDAO
    $genre = $genreDAO->getById($id);

    $responseBody = json_encode($genre);
} catch (Exception $e) {
    // Muda o código de resposta HTTP para 'bad request'
    http_response_code(400);
    $responseBody = '{ "message": "Ocorreu um erro ao tentar executar esta ação. Erro: Código: ' .  $e->getCode() . '. Mensagem: ' . $e->getMessage() . '" }';
}

// Define o cabeçalho HTTP 'Content-Type: application/json'
header('Content-Type: application/json');

// Exibe a resposta
echo $responseBody;

?>