<?php
class SalesDAO {
    private $pdo;
  
    function __construct($pdo) {
      $this->pdo = $pdo;
    }
  
    // Método para criar uma nova compra
    public function create($codUsuario, $valor, $dt_compra) {
      $stmt = $this->pdo->prepare("INSERT INTO tb_compra (codUsuario, valor, dt_compra) VALUES (?, ?, ?)");
      $stmt->execute([$codUsuario, $valor, $dt_compra]);
      return $this->pdo->lastInsertId();
    }
  
    // Método para atualizar os dados de uma compra
    public function update($codCompra, $codUsuario, $valor, $dt_compra) {
      // Prepara a query para atualizar os dados da compra
      $stmt = $this->pdo->prepare("UPDATE tb_compra SET codUsuario = ?, valor = ?, dt_compra = ? WHERE codCompra = ?");
      $stmt->execute([$codUsuario, $valor, $dt_compra, $codCompra]);
      return $stmt->rowCount() > 0;
    }
  
    // Método para deletar uma compra pelo ID
    public function delete($codCompra) {
      $stmt = $this->pdo->prepare("DELETE FROM tb_compra WHERE codCompra = ?");
      $stmt->execute([$codCompra]);
      return $stmt->rowCount() > 0;
    }
  
    // Método para buscar uma compra pelo ID
    public function getById($codCompra) {
      $stmt = $this->pdo->prepare("SELECT * FROM tb_compra WHERE codCompra = ?");
      $stmt->execute([$codCompra]);
      return $stmt->fetchObject();
    }
  
  // Método para buscar as compras pelo usuário
  public function getByUser($codUsuario) {
    $stmt = $this->pdo->prepare("SELECT * FROM tb_compra WHERE codUsuario = ?");
    $stmt->execute([$codUsuario]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
  
    // Método para buscar todas as compras
    public function getAll() {
      $stmt = $this->pdo->prepare("SELECT * FROM tb_compra");
      $stmt->execute();
      return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
  }
  
?>