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

// Verificar se o ID do livro foi fornecido
if (!isset($_GET['id'])) {
    http_response_code(400); // bad request
    echo '{ "message": "O ID do livro deve ser fornecido." }';
    exit();
}

// Obtém o ID do livro a ser atualizado
$id = $_GET['id'];
if (!is_numeric($id)) {
    http_response_code(400); // bad request
    echo '{ "message": "O ID do livro deve ser um valor numérico." }';
    exit();
}

// Extrair os dados do livro do objeto $data
$codAutor = $data->codAutor;
$codGenero = $data->codGenero;
$descLivro = $data->descLivro;
$preco = $data->preco;
$quantidade = $data->quantidade;
$dt_lancamento = $data->dt_lancamento;

$responseBody = '';

try {
    // Verificar se o livro existe
    $book = $bookDAO->getById($id);
    if (!$book) {
        // Muda o código de resposta HTTP para 'not found'
        http_response_code(404);
        $responseBody = '{ "message": "O livro informado não existe." }';
    } else {
        // Verificar se o autor existe
        $author = $authorDAO->getById($codAutor);
        if (!$author) {
            http_response_code(404);
            $responseBody = '{ "message": "O autor informado não existe." }';
        } else {
            // Verificar se a categoria existe
            $genre = $genreDAO->getById($codGenero);
            if (!$genre) {
                http_response_code(404);
                $responseBody = '{ "message": "A categoria informada não existe." }';
            } else {
                $bookDAO->update($id, $codAutor, $codGenero, $descLivro, $preco, $quantidade, $dt_lancamento);

                $book = $bookDAO->getById($id);

                $responseBody = json_encode($book);
            }
        }
    }
} catch (Exception $e) {
    // Muda o código de resposta HTTP para 'bad request'
    http_response_code(400);
    $responseBody = '{ "message": "Ocorreu um erro ao tentar executar esta ação. Erro: Código: ' .  $e->getCode() . '. Mensagem: ' . $e->getMessage() . '" }';
}

header('Content-Type: application/json');
echo $responseBody;


?>