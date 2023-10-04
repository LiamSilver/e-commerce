<?php
include("../enable-cors.php");
require_once("../db/connection.inc.php");
require_once("authorDAO.php");
require_once("../auth/login.php");

$authorDAO = new AuthorDAO(getConnection());
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

// Transformar o JSON em um objeto PHP
$data = json_decode($json);

// Extrair os dados do autor do objeto $data
$nome = $data->nome;

$responseBody = '';

try {
    // Inserir o novo autor utilizando a classe AuthorDAO
    $id = $authorDAO->create($nome);

    // Buscar o autor recém-criado pelo ID utilizando a classe AuthorDAO
    $author = $authorDAO->getById($id);

    $responseBody = json_encode($author);
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