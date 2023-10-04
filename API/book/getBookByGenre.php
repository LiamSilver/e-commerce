<?php
include("../enable-cors.php");
require_once("../db/connection.inc.php");
require_once("bookDAO.php");
require_once("../genre/genreDAO.php");

$bookDAO = new BookDAO(getConnection());
$genreDAO = new GenreDAO(getConnection());

$genre = isset($_GET['genre']) ? $_GET['genre'] : '';
$id = $genreDAO->getByName($genre);

$books = $bookDAO->getByGenre($id);
$responseBody = json_encode($books);

// Verifica se houve algum erro na conversão para JSON
if (json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(400);
    $responseBody = json_encode(array(
        "message" => "Ocorreu um erro ao tentar executar esta ação. Erro: " . json_last_error_msg()
    ));
}

// Define o tipo de conteúdo da resposta
$contentType = "application/json";
header("Content-Type: $contentType");

echo $responseBody;
exit();
?>
