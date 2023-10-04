<?php
include("../enable-cors.php");
require_once("../db/connection.inc.php");
require_once("genreDAO.php");

$genreDAO = new GenreDAO(getConnection());

// Obtém o ID do gênero a ser buscado a partir da query string
// Se o ID não for fornecido, busca todos os gêneros
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    if (!is_numeric($id)) {
        http_response_code(400); // bad request
        echo '{ "message": "O ID do gênero deve ser um valor numérico." }';
        exit();
    }
    $genre = $genreDAO->getById($id);
    if (!$genre) {
        http_response_code(404); // not found
        echo '{ "message": "Gênero não encontrado." }';
        exit();
    }
    $genres = [$genre];
} else {
    $genres = $genreDAO->getAll();
}

$responseBody = '';

try {
    // Converte o array de gêneros para JSON
    $responseBody = json_encode($genres);
} catch (Exception $e) {
    http_response_code(400);
    $responseBody = '{ "message": "Ocorreu um erro ao tentar executar esta ação. Erro: Código: ' .  $e->getCode() . '. Mensagem: ' . $e->getMessage() . '" }';
}

header('Content-Type: application/json');

echo $responseBody;


?>