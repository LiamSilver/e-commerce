<?php
class GenreDAO {
    private $pdo;
  
    function __construct($pdo) {
      $this->pdo = $pdo;
    }
  
    // Método para criar um novo gênero
    public function create($descGenero) {
      $stmt = $this->pdo->prepare("INSERT INTO tb_genero (descGenero) VALUES (?)");
      $stmt->execute([$descGenero]);
      return $this->pdo->lastInsertId();
    }
  
    // Método para atualizar a descrição de um gênero
    public function update($id, $descGenero) {
      // Prepara a query para atualizar a descrição do gênero
      $stmt = $this->pdo->prepare("UPDATE tb_genero SET descGenero = ? WHERE codGenero = ?");
      $stmt->execute([$descGenero, $id]);
      return $stmt->rowCount() > 0;
    }
  
    // Método para deletar um gênero pelo ID
    public function delete($id) {
      $stmt = $this->pdo->prepare("DELETE FROM tb_genero WHERE codGenero = ?");
      $stmt->execute([$id]);
      return $stmt->rowCount() > 0;
    }
  
    // Método para buscar um gênero pelo ID
    public function getById($id) {
      $stmt = $this->pdo->prepare("SELECT * FROM tb_genero WHERE codGenero = ?");
      $stmt->execute([$id]);
      return $stmt->fetchObject();
    }
  
    // Método para buscar todos os gêneros
    public function getAll() {
      $stmt = $this->pdo->prepare("SELECT * FROM tb_genero ORDER BY descGenero");
      $stmt->execute();
      return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getByName($desc) {
      $descGenero = strtoupper($desc);
      $stmt = $this->pdo->prepare("SELECT codGenero FROM tb_genero WHERE UPPER(descGenero) = ?");
      $stmt->execute([$descGenero]);
      return $stmt->fetch(PDO::FETCH_COLUMN);
  }
  
  }
  
?>