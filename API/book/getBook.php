<?php

include("../enable-cors.php");
require_once("../db/connection.inc.php");
require_once("bookDAO.php");

$bookDAO = new BookDAO(getConnection());

// Obtém o ID do livro a ser buscado a partir da query string
// Se o ID não for fornecido, busca todos os livros
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    if (!is_numeric($id)) {
        http_response_code(400); // bad request
        echo '{ "message": "O ID do livro deve ser um valor numérico." }';
        exit();
    }
    $book = $bookDAO->getById($id);
    if (!$book) {
        http_response_code(404); // not found
        echo '{ "message": "Livro não encontrado." }';
        exit();
    }
    $books = [$book];
} else {
    $books = $bookDAO->getAll();
}

$responseBody = '';

try {
    // Converte o array de livros para JSON
    $responseBody = json_encode($books);
} catch (Exception $e) {
    http_response_code(400);
    $responseBody = '{ "message": "Ocorreu um erro ao tentar executar esta ação. Erro: Código: ' .  $e->getCode() . '. Mensagem: ' . $e->getMessage() . '" }';
}

header('Content-Type: application/json');

echo $responseBody;

?>