<?php
include("../enable-cors.php");
require_once("../db/connection.inc.php");
require_once("../user/userDAO.php");
require_once("salesDAO.php");
require_once("../auth/login.php");

$userDAO = new UserDAO(getConnection());
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

// Obter o corpo da requisição
$json = file_get_contents('php://input');

// Transforma o JSON em um Objeto PHP
$data = json_decode($json);

// Verifica se o ID da compra foi fornecido
if (!isset($_GET['id'])) {
    http_response_code(400); // bad request
    echo '{ "message": "O ID da compra deve ser fornecido." }';
    exit();
}

// Obtém o ID da compra a ser atualizada
$idCompra = $_GET['id'];
if (!is_numeric($idCompra)) {
    http_response_code(400); // bad request
    echo '{ "message": "O ID da compra deve ser um valor numérico." }';
    exit();
}

// Verificar se a compra existe antes de atualizar
$sale = $salesDAO->getById($idCompra);

if (!$sale) {
    http_response_code(400); // bad request
    echo '{ "message": "Compra não encontrada." }';
    exit();
}

// Verifica se o ID do usuário foi fornecido
if (!isset($data->codUsuario)) {
    http_response_code(400); // bad request
    echo '{ "message": "O ID do usuário deve ser fornecido." }';
    exit();
}

$idUsuario = $data->codUsuario;
if (!is_numeric($idUsuario)) {
    http_response_code(400); // bad request
    echo '{ "message": "O ID do usuário deve ser um valor numérico." }';
    exit();
}

$user = $userDAO->getById($idUsuario);

if (!$user) {
    http_response_code(400); // bad request
    echo '{ "message": "Usuário não encontrado." }';
    exit();
}

// Extrair os dados da compra do objeto $data
$valor = $data->valor;
$dt_compra = $data->dt_compra;

try {
    $salesDAO->update($idCompra, $idUsuario, $valor, $dt_compra);
    $sale = $salesDAO->getById($idCompra);
    header('Content-Type: application/json');
    echo json_encode($sale);
} catch (Exception $e) {
    // Muda o código de resposta HTTP para 'bad request'
    http_response_code(400);
    $responseBody = '{ "message": "Ocorreu um erro ao tentar executar esta ação. Erro: Código: ' .  $e->getCode() . '. Mensagem: ' . $e->getMessage() . '" }';
    echo $responseBody;
}

?>