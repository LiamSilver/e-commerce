<?php

include("../enable-cors.php");
require_once("../db/connection.inc.php");
require_once("bookDAO.php");

$bookDAO = new BookDAO(getConnection());

// Obtém o nome do livro a ser buscado a partir da query string
$livro = isset($_GET['livro']) ? $_GET['livro'] : '';

// Busca os livros pelo nome e atribui o resultado a variável $books
$books = $bookDAO->getBookByName($livro);

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
