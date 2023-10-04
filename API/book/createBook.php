<?php
include("../enable-cors.php");
require_once("../db/connection.inc.php");
require_once("bookDAO.php");
require_once("../author/authorDAO.php");
require_once("../genre/genreDAO.php");
require_once("../auth/login.php");

$bookDAO = new BookDAO(getConnection());
$authorDAO = new AuthorDAO(getConnection());
$genreDAO = new GenreDAO(getConnection());
$authController = new AuthenticationController();

$token = $_GET['token'] ?? '';

if (empty($token)) {
    http_response_code(400); // bad request
    echo json_encode(['message' => 'Token não fornecido']);
    exit();
}


// Decodifica o token JWT e verifica se o usuário é administrador ou está logado
$payload = $authController->decodeJwtToken($token);
if (!$payload || (!isset($payload['admin']) || $payload['admin'] !== 1)) {
    http_response_code(403); // forbidden
    echo '{ "message": "Você não tem privilégios para acessar essa página." }';
    exit();
}

// Obter o corpo da requisição
$json = file_get_contents('php://input');

// Transformar o JSON em um objeto PHP
$data = json_decode($json);

// Extrair os dados do livro do objeto $data
$codAutor = $data->codAutor;
$codGenero = $data->codGenero;
$descLivro = $data->descLivro;
$preco = $data->preco;
$quantidade = $data->quantidade;
$dt_lancamento = $data->dt_lancamento;

$responseBody = '';

try {
    // Verificar se o autor existe
    $author = $authorDAO->getById($codAutor);
    if (!$author) {
        http_response_code(404);
        $responseBody = '{ "message": "O autor informado não existe." }';
    } else {
        // Verificar se o gênero existe
        $genre = $genreDAO->getById($codGenero);
        if (!$genre) {
            http_response_code(404);
            $responseBody = '{ "message": "O gênero informado não existe." }';
        } else {
            $codLivro = $bookDAO->create($codAutor, $codGenero, $descLivro, $preco, $quantidade, $dt_lancamento);
            $book = $bookDAO->getById($codLivro);

            $responseBody = json_encode($book);
        }
    }
} catch (Exception $e) {
    // Muda o código de resposta HTTP para 'bad request'
    http_response_code(400);
    $responseBody = '{ "message": "Ocorreu um erro ao tentar executar esta ação. Erro: Código: ' .  $e->getCode() . '. Mensagem: ' . $e->getMessage() . '" }';
}

// Define o cabeçalho HTTP 'Content-Type: application/json'
header('Content-Type: application/json');

// Exibe a resposta
echo $responseBody;

?>