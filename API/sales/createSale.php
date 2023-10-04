<?php
include("../enable-cors.php");
require_once("../db/connection.inc.php");
require_once("../user/userDAO.php");
require_once("salesDAO.php");
require_once("itemSalesDAO.php");
require_once("../auth/login.php");

$userDAO = new UserDAO(getConnection());
$salesDAO = new SalesDAO(getConnection());
$itemCompraDAO = new PurchaseBookDAO(getConnection());
$authController = new AuthenticationController();

$token = $_GET['token'] ?? '';

if (empty($token)) {
    http_response_code(400); // bad request
    echo json_encode(['message' => 'Token não fornecido']);
    exit();
}

// Decodifica o token JWT e verifica se o usuário é administrador ou está logado
$payload = $authController->decodeJwtToken($token);
if (!$payload || ((!isset($payload['admin']) || $payload['admin'] !== 1) && (!isset($payload['is_logged_in']) || !$payload['is_logged_in']))) {
    http_response_code(403);
    echo '{ "message": "Você não tem privilégios para acessar essa página." }';
    exit();
}

// Obter o corpo da requisição
$json = file_get_contents('php://input');

// Transformar o JSON em um objeto PHP
$data = json_decode($json);

$responseBody = '';

try {
    // Verificar se o usuário existe antes de criar a compra
    $user = $userDAO->getById($data->codUsuario);

    if (!$user) {
        http_response_code(400);
        $responseBody = '{ "message": "Usuário não encontrado." }';
    } else {
        $id = $salesDAO->create($data->codUsuario, $data->valor, $data->dt_compra);
        
        foreach($data->itens as $item) {
            $item->codCompra = $id;
            $itemCompraDAO->create($item);
        }
        $sale = $salesDAO->getById($id);
        $responseBody = json_encode($sale);
    }
} catch (Exception $e) {
    http_response_code(400);
    $responseBody = '{ "message": "Ocorreu um erro ao tentar executar esta ação. Erro: Código: ' .  $e->getCode() . '. Mensagem: ' . $e->getMessage() . '" }';
}

// Define o cabeçalho HTTP 'Content-Type: application/json'
header('Content-Type: application/json');

// Exibe a resposta
echo $responseBody;


?>