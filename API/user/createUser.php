<?php
require_once("../db/connection.inc.php");
require_once("userDAO.php");
require_once '../enable-cors.php';

$userDAO = new UserDAO(getConnection());

// Obter o corpo da requisição
$json = file_get_contents('php://input');

// Transformar o JSON em um objeto PHP
$data = json_decode($json);

// Extrair os dados do usuário do objeto $data
$nome = $data->nome;
$nascimento = date('y-m-d', strtotime($data->nascimento));
$email = $data->email;
$senha = $data->senha;

$responseBody = '';

try {
    $emailExistente = $userDAO->checkUserByEmail($email);

    if ($emailExistente) {
        http_response_code(400);
        $responseBody = '{ "message": "Já existe um usuário com esse email." }';
    } else {
        http_response_code(200);
        $id = $userDAO->create($nome, $email, $senha, $nascimento);
        ob_start();
        require_once("../auth/login.php");
        $responseBody = ob_get_clean();
        
    }

} catch (Exception $e) {
    http_response_code(400);
    $responseBody = '{ "message": "Ocorreu um erro ao tentar executar esta ação. Erro: Código: ' .  $e->getCode() . '. Mensagem: ' . $e->getMessage() . '" }';
}


header('Content-Type: application/json');

echo $responseBody;
