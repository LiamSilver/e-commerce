<?php
class BookDAO {
    private $pdo;
  
    function __construct($pdo) {
      $this->pdo = $pdo;
    }
  
    // Método para criar um novo livro
    public function create($codAutor, $codGenero, $descLivro, $preco, $quantidade, $dt_lancamento) {
      $stmt = $this->pdo->prepare("INSERT INTO tb_livro (codAutor, codGenero, descLivro, preco, quantidade, dt_lancamento) VALUES (?, ?, ?, ?, ?, ?)");
      $stmt->execute([$codAutor, $codGenero, $descLivro, $preco, $quantidade, $dt_lancamento]);
      return $this->pdo->lastInsertId();
    }
  
    // Método para atualizar os dados de um livro
    public function update($codLivro, $codAutor, $codGenero, $descLivro, $preco, $quantidade, $dt_lancamento) {
      // Prepara a query para atualizar os dados do livro
      $stmt = $this->pdo->prepare("UPDATE tb_livro SET codAutor = ?, codGenero = ?, descLivro = ?, preco = ?, quantidade = ?, dt_lancamento = ? WHERE codLivro = ?");
      $stmt->execute([$codAutor, $codGenero, $descLivro, $preco, $quantidade, $dt_lancamento, $codLivro]);
      return $stmt->rowCount() > 0;
    }
  
    // Método para deletar um livro pelo ID
    public function delete($codLivro) {
      $stmt = $this->pdo->prepare("DELETE FROM tb_livro WHERE codLivro = ?");
      $stmt->execute([$codLivro]);
      return $stmt->rowCount() > 0;
    }
  
    // Método para buscar um livro pelo ID
    public function getById($codLivro) {
      $stmt = $this->pdo->prepare("SELECT * FROM tb_livro WHERE codLivro = ?");
      $stmt->execute([$codLivro]);
      return $stmt->fetchObject();
    }
  
    // Método para buscar todos os livros
    public function getAll() {
      $stmt = $this->pdo->prepare("SELECT * FROM tb_livro ORDER BY descLivro");
      $stmt->execute();
      return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    // Método para buscar livros pelo nome
    public function getDescByName($name) {
      $name = strtoupper($name);
      $stmt = $this->pdo->prepare("SELECT descLivro FROM tb_livro WHERE upper(descLivro) LIKE ?");
      $stmt->execute(["$name%"]);
      $result = $stmt->fetchAll(PDO::FETCH_COLUMN);
      return $result;
  }
   
  // Método para buscar livros pelo genero
  public function getByGenre($id) {
    $stmt = $this->pdo->prepare("SELECT * FROM tb_livro INNER JOIN tb_genero ON tb_livro.codGenero = tb_genero.codGenero WHERE tb_livro.codGenero = ? ORDER BY descLivro");
    $stmt->execute([$id]);
    return $stmt->fetchAll(PDO::FETCH_OBJ);
}

// Metodo para buscar livros pelo autor	
  public function getByAuthor($id) {
    $stmt = $this->pdo->prepare("SELECT * FROM tb_livro INNER JOIN tb_autor ON tb_livro.codAutor = tb_autor.codAutor WHERE tb_livro.codAutor = ? ORDER BY descLivro");
    $stmt->execute([$id]);
    return $stmt->fetchAll(PDO::FETCH_OBJ);
}

public function getBookByName($name) {
  $name = strtoupper($name);
  $stmt = $this->pdo->prepare("SELECT codLivro,nomeAutor, descGenero, descLivro, dt_lancamento,preco FROM tb_livro INNER JOIN tb_autor ON tb_livro.codAutor = tb_autor.codAutor
  INNER JOIN tb_genero ON tb_livro.codGenero = tb_genero.codGenero WHERE UPPER(descLivro) = ?");

  $stmt->execute([$name]);
  $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
  return $result;
}

}
  
?>