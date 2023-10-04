<?php
class AuthorDAO {
    private $pdo;
  
    function __construct($pdo) {
      $this->pdo = $pdo;
    }
  
    // Método para criar um novo autor
    public function create($nome) {
      $stmt = $this->pdo->prepare("INSERT INTO tb_autor (nomeAutor) VALUES (?)");
      $stmt->execute([$nome]);
      return $this->pdo->lastInsertId();
    }
  
    // Método para atualizar os dados de um autor
    public function update($id, $nome) {
      // Prepara a query para atualizar os dados do autor
      $stmt = $this->pdo->prepare("UPDATE tb_autor SET nomeAutor = ? WHERE codAutor = ?");
      $stmt->execute([$nome, $id]);
      return $stmt->rowCount() > 0;
    }
  
    // Método para deletar um autor pelo ID
    public function delete($id) {
      $stmt = $this->pdo->prepare("DELETE FROM tb_autor WHERE codAutor = ?");
      $stmt->execute([$id]);
      return $stmt->rowCount() > 0;
    }
  
    // Método para buscar um autor pelo ID
    public function getById($id) {
      $stmt = $this->pdo->prepare("SELECT * FROM tb_autor WHERE codAutor = ?");
      $stmt->execute([$id]);
      return $stmt->fetchObject();
    }
  
    // Método para buscar todos os autores
    public function getAll() {
      $stmt = $this->pdo->prepare("SELECT * FROM tb_autor ORDER BY nomeAutor");
      $stmt->execute();
      return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getCodeByName($nome) {
      $nomeAuthor = strtoupper($nome);
      $stmt = $this->pdo->prepare("SELECT codAutor FROM tb_autor WHERE UPPER(nomeAutor) = ?");
      $stmt->execute([$nomeAuthor]);
      return $stmt->fetch(PDO::FETCH_COLUMN);
  }
  
  }
  
?>