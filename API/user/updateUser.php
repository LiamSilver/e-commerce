<?php
include("../enable-cors.php");
require_once("../db/connection.inc.php");
require_once("userDAO.php");
require_once("../auth/login.php");

$userDAO = new UserDAO(getConnection());
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

// Verifica se o ID do usuário foi fornecido
if (!isset($_GET['id'])) {
    http_response_code(400); // bad request
    echo '{ "message": "O ID do usuário deve ser fornecido." }';
    exit();
}

// Obtém o ID do usuário a ser atualizado
$id = $_GET['id'];
if (!is_numeric($id)) {
    http_response_code(400); // bad request
    echo '{ "message": "O ID do usuário deve ser um valor numérico." }';
    exit();
}

// Obter o corpo da requisição
$json = file_get_contents('php://input');

// Transforma o JSON em um Objeto PHP
$user = json_decode($json);

$userDAO->update($id, $user->nome, $user->email, $user->senha, $user->nascimento);

// Retorna o usuário atualizado como JSON
$user = $userDAO->getById($id);
header('Content-Type: application/json');
echo json_encode($user);
?>
