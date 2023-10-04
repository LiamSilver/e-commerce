<?php
include_once 'connection.inc.php';

// Cria conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Cria tabela tb_usuario
$sql_usuario = "CREATE TABLE tb_usuario (
    codUsuario INT(6) UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(50) NOT NULL,
    email VARCHAR(50) NOT NULL UNIQUE,
    senha VARCHAR(50) NOT NULL,
    dt_nascimento DATE,
    admin INT DEFAULT 0
    )";

if ($conn->query($sql_usuario) === TRUE) {
    echo "Tabela tb_usuario criada com sucesso!";
} else {
    echo "Erro ao criar tabela tb_usuario: " . $conn->error;
}

// Cria tabela tb_autor
$sql_autor = "CREATE TABLE tb_autor (
    codAutor INT(6) UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    nomeAutor VARCHAR(50) NOT NULL
    )";

if ($conn->query($sql_autor) === TRUE) {
    echo "Tabela tb_autor criada com sucesso!";
} else {
    echo "Erro ao criar tabela tb_autor: " . $conn->error;
}

// Cria tabela tb_genero
$sql_genero = "CREATE TABLE tb_genero (
    codGenero INT(6) UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    descGenero VARCHAR(50) NOT NULL
    )";

if ($conn->query($sql_genero) === TRUE) {
    echo "Tabela tb_genero criada com sucesso!";
} else {
    echo "Erro ao criar tabela tb_genero: " . $conn->error;
}

// Cria tabela tb_livro
$sql_livro = "CREATE TABLE tb_livro (
    codLivro INT(6) UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    codAutor INT(6) UNSIGNED,
    codGenero INT(6) UNSIGNED,
    descLivro VARCHAR(100) NOT NULL,
    preco FLOAT NOT NULL,
    quantidade INT NOT NULL,
    dt_lancamento DATE NOT NULL,
    FOREIGN KEY (codAutor) REFERENCES tb_autor(codAutor),
    FOREIGN KEY (codGenero) REFERENCES tb_genero(codGenero)
    )";

if ($conn->query($sql_livro) === TRUE) {
    echo "Tabela tb_livro criada com sucesso!";
} else {
    echo "Erro ao criar tabela tb_livro: " . $conn->error;
}

// Cria tabela tb_compra
$sql_compra = "CREATE TABLE tb_compra (
    codCompra INT(6) UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    codUsuario INT(6) UNSIGNED,
    valor FLOAT NOT NULL,
    dt_compra DATE NOT NULL,
    FOREIGN KEY (codUsuario) REFERENCES tb_usuario(codUsuario)
    )";

if ($conn->query($sql_compra) === TRUE) {
    echo "Tabela tb_compra criada com sucesso!";
} else {
    echo "Erro ao criar tabela tb_compra: " . $conn->error;
}

// Cria tabela tb_compra_livro
$sql_compra_livro = "CREATE TABLE tb_compra_livro (
    codCompra INT(6) UNSIGNED,
    codLivro INT(6) UNSIGNED,
    qtd INT NOT NULL,
    valorUnitario FLOAT NOT NULL,
    FOREIGN KEY (codCompra) REFERENCES tb_compra(codCompra),
    FOREIGN KEY (codLivro) REFERENCES tb_livro(codLivro)
    )";

if ($conn->query($sql_compra_livro) === TRUE) {
    echo "Tabela tb_compra_livro criada com sucesso!";
} else {
    echo "Erro ao criar tabela tb_compra_livro: " . $conn->error;
}

// Inserção de 3 usuários
$sql_usuarios = "INSERT INTO tb_usuario (nome, email, senha, dt_nascimento, admin) VALUES 
('Usuario1', 'usuario1@gmail.com', '123456', '1990-01-01', 0),
('Usuario2', 'usuario2@gmail.com', '654321', '1995-01-01', 0),
('admin', 'admin@gmail.com', 'admin', '1980-01-01', 1)";

if ($conn->query($sql_usuarios) === TRUE) {
    echo "Dados inseridos na tabela tb_usuario com sucesso!";
} else {
    echo "Erro ao inserir dados na tabela tb_usuario: " . $conn->error;
}

// Inserção de 5 generos de livros
$sql_generos = "INSERT INTO tb_genero (descGenero) VALUES 
('Fantasia'),
('Terror'),
('Suspense'),
('Autoajuda'),
('Ficção Científica')";

if ($conn->query($sql_generos) === TRUE) {
    echo "Dados inseridos na tabela tb_genero com sucesso!";
} else {
    echo "Erro ao inserir dados na tabela tb_genero: " . $conn->error;
}

// Inserção de 5 Autores de livros

$sql_autores = "INSERT INTO tb_autor (nomeAutor) VALUES 
('J.K. Rowling'),
('Stephen King'),
('Dan Brown'),
('Paulo Coelho'),
('George R.R. Martin')";

if ($conn->query($sql_autores) === TRUE) {
    echo "Autores inseridos com sucesso!";
} else {
    echo "Erro ao inserir autores: " . $conn->error;
}
// Inserção de Livros 
$sql_livros = "INSERT INTO tb_livro (codAutor, codGenero, descLivro, preco, quantidade, dt_lancamento) VALUES 
(1, 1, 'Harry Potter e a Pedra Filosofal', 39.90, 50, '1997-06-26'),
(1, 1, 'Harry Potter e a Câmara Secreta', 39.90, 40, '1998-07-02'),
(1, 1, 'Harry Potter e o Prisioneiro de Azkaban', 49.90, 30, '1999-07-08'),
(2, 2, 'It: A Coisa', 49.90, 20, '1986-09-15'),
(2, 2, 'O Iluminado', 49.90, 25, '1977-01-28'),
(2, 2, 'Cemitério Maldito', 39.90, 30, '1983-11-14'),
(3, 3, 'O Código Da Vinci', 49.90, 50, '2003-03-18'),
(3, 3, 'Anjos e Demônios', 39.90, 30, '2000-05-01'),
(3, 3, 'Inferno', 49.90, 25, '2013-05-14'),
(4, 4, 'O Alquimista', 29.90, 100, '1988-01-01'),
(4, 4, 'Brida', 29.90, 50, '1990-01-01'),
(4, 4, 'As Valkírias', 39.90, 30, '1992-01-01'),
(5, 1, 'Macunaíma', 39.90, 20, '1928-01-01'),
(5, 2, 'Amar, Verbo Intransitivo', 29.90, 25, '1927-01-01'),
(5, 5, 'Contos Novos', 29.90, 30, '1947-01-01')";

if ($conn->multi_query($sql_livros) === TRUE) {
    echo "Livros inseridos com sucesso!";
} else {
    echo "Erro ao inserir livros: " . $conn->error;
}

// Inserção das compras na tabela tb_compra
$sql_compras = "INSERT INTO tb_compra (codUsuario, valor, dt_compra) VALUES 
(1, 129.80, '2021-06-01'),
(1, 89.70, '2021-06-05'),
(1, 59.90, '2021-06-08'),
(2, 89.70, '2021-06-02'),
(2, 119.80, '2021-06-04'),
(2, 39.90, '2021-06-09'),
(3, 109.80, '2021-06-03'),
(3, 69.90, '2021-06-06'),
(3, 99.80, '2021-06-07')";

if ($conn->query($sql_compras) === TRUE) {
    echo "Compras inseridas com sucesso!";
} else {
    echo "Erro ao inserir compras: " . $conn->error;
}

// Inserção dos livros vendidos na tabela tb_compra_livro
$sql_livros_vendidos = "INSERT INTO tb_compra_livro (codCompra, codLivro, qtd,valorUnitario) VALUES 
(1, 1, 2, 129.80),
(1, 2, 1, 89.70),
(2, 4, 1, 59.90),
(2, 5, 3, 89.70),
(3, 6, 1, 39.90),
(3, 7, 2, 49.90)";

if ($conn->query($sql_livros_vendidos) === TRUE) {
    echo "Livros vendidos inseridos com sucesso!";
} else {
    echo "Erro ao inserir livros vendidos: " . $conn->error;
}

$conn->close();
