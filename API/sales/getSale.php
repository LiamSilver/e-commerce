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

// Decodifica o token JWT e verifica se o usuário é administrador ou está logado
$payload = $authController->decodeJwtToken($token);
if (!$payload || (!isset($payload['admin']) || $payload['admin'] !== 1) || (!isset($payload['is_logged_in']) || !$payload['is_logged_in'])) {
    http_response_code(403); // forbidden
    echo '{ "message": "Você não tem privilégios para acessar essa página." }';
    exit();
}

// Obtém o ID da compra a ser buscada a partir da query string
// Se o ID não for fornecido, busca todas as compras
if (isset($_GET['id'])) {
    $idCompra = $_GET['id'];
    if (!is_numeric($idCompra)) {
        http_response_code(400); // bad request
        echo '{ "message": "O ID da compra deve ser um valor numérico." }';
        exit();
    }
    $sale = $salesDAO->getById($idCompra);
    if (!$sale) {
        http_response_code(404); // not found
        echo '{ "message": "Compra não encontrada." }';
        exit();
    }
    $sales = [$sale];
} else {
    $sales = $salesDAO->getAll();
}

$responseBody = '';

try {
    // Converte o array de compras para JSON
    $responseBody = json_encode($sales);
} catch (Exception $e) {
    http_response_code(400);
    $responseBody = '{ "message": "Ocorreu um erro ao tentar executar esta ação. Erro: Código: ' .  $e->getCode() . '. Mensagem: ' . $e->getMessage() . '" }';
}

header('Content-Type: application/json');

print_r($responseBody);

?>