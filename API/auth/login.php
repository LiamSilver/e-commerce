<?php

require_once __DIR__ . '/../db/connection.inc.php';
require_once '../user/userDAO.php';
require_once '../JWT/jwtClassUtils.php';
require_once '../enable-cors.php';

// Define a chave secreta para a codificação do token JWT
define('JWT_SECRET_KEY', 'chave_secreta');

class AuthenticationController
{
    public static function handleRequest()
    {
        // Obtém a conexão PDO
        $pdo = getConnection();

            // Obtém o conteúdo do corpo da requisição
            $json = file_get_contents('php://input');
    
            // Transforma o JSON em um array associativo
            $data = json_decode($json, true);
    
            // Verifica se o email e a senha existem no array de dados
            if (isset($data['email']) && isset($data['senha'])) {
                $email = $data['email'];
                $senha = $data['senha'];
            } else {
                // Se o email e/ou senha não existirem no corpo da requisição, retorna uma resposta de erro
                http_response_code(400);
                echo json_encode(['message' => 'Email e/ou senha não fornecidos']);
                return;
            }
        // Busca o usuário no banco de dados
        $userDAO = new UserDAO($pdo);
        $user = $userDAO->getUserByEmailAndPassword($email, $senha);

        // Verifica se o usuário foi encontrado
        if (!$user) {
            // Resposta de erro, caso o usuário não tenha sido encontrado
            http_response_code(401);
            $responseBody = ['message' => 'Credenciais inválidas'];
            header('Content-Type: application/json');
            echo json_encode($responseBody);
            return;
        }

        // Cria um array com os dados do usuário para serem carregados no token
        $payload = [
            'id' => $user->codUsuario,
            'nome' => $user->nome,
            'email' => $user->email,
            'senha' => $user->senha,
            'is_logged_in' => true,
            'admin' => $user->admin
        ];

        // Cria um token JWT usando a classe JwtUtil
        $jwtUtil = new JwtUtil(JWT_SECRET_KEY);
        $token = $jwtUtil->encode($payload);
        
        // Resposta de sucesso, com o token JWT
        http_response_code(200);
        $responseBody = ['message' => 'Login efetuado com sucesso', 'token' => $token];
        header('Content-Type: application/json');
        echo json_encode($responseBody);

    }

    public function handleRequestAndLogin(){
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
        $email = $data['email'] ?? '';
        $senha = $data['senha'] ?? '';

        if($email==='' || $senha===''){
            return;
        }
        else{
            self::handleRequest();
        }
    }

    public static function decodeJwtToken($token): ?array
    {
        // Verifica se o token ainda está vazio
        if (empty($token)) {
            return null;
        }
        // Decodifica o token JWT usando a classe JwtUtil
        $jwtUtil = new JwtUtil(JWT_SECRET_KEY);
        $payload = $jwtUtil->decode($token);
        // Retorna um array com os dados do usuário
        return (array) $payload;
    }
    
}

$authController = new AuthenticationController();
$authController->handleRequestAndLogin();
