<?php
include("../enable-cors.php");
require_once("../db/connection.inc.php");
require_once("authorDAO.php");

$authorDAO = new AuthorDAO(getConnection());

// Obtém o ID do autor a ser buscado a partir da query string
// Se o ID não for fornecido, busca todos os autores
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    if (!is_numeric($id)) {
        http_response_code(400); // bad request
        echo '{ "message": "O ID do autor deve ser um valor numérico." }';
        exit();
    }
    $author = $authorDAO->getById($id);
    if (!$author) {
        http_response_code(404); // not found
        echo '{ "message": "Autor não encontrado." }';
        exit();
    }
    $authors = [$author];
} else {
    $authors = $authorDAO->getAll();
}

$responseBody = '';

try {
    // Converte o array de autores para JSON
    $responseBody = json_encode($authors);
} catch (Exception $e) {
    http_response_code(400);
    $responseBody = '{ "message": "Ocorreu um erro ao tentar executar esta ação. Erro: Código: ' .  $e->getCode() . '. Mensagem: ' . $e->getMessage() . '" }';
}

header('Content-Type: application/json');

echo $responseBody;

?>