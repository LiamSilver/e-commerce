<?php


class UserDAO {
  private $pdo;

  function __construct($pdo) {
    $this->pdo = $pdo;
  }

  // Método para criar um novo usuário
  public function create($nome, $email, $senha, $dt_nascimento) {
    $stmt = $this->pdo->prepare("INSERT INTO tb_usuario (nome, email, senha, dt_nascimento) VALUES (?, ?, ?, ?)");
    $stmt->execute([$nome, $email, $senha, $dt_nascimento]);
    return $this->pdo->lastInsertId();
  }

  // Método para atualizar os dados de um usuário
  public function update($id, $nome, $email, $senha, $dt_nascimento) {
    // Prepara a query para atualizar os dados do usuário
    $stmt = $this->pdo->prepare("UPDATE tb_usuario SET nome = ?, email = ?, senha = ?, dt_nascimento = ? WHERE codUsuario = ?");
    $stmt->execute([$nome, $email, $senha, $dt_nascimento, $id]);
    return $stmt->rowCount() > 0;
  }

  // Método para deletar um usuário pelo ID
  public function delete($id) {
    $stmt = $this->pdo->prepare("DELETE FROM tb_usuario WHERE codUsuario = ?");
    $stmt->execute([$id]);
    return $stmt->rowCount() > 0;
  }

  // Método para buscar um usuário pelo ID
  public function getById($id) {
    $stmt = $this->pdo->prepare("SELECT * FROM tb_usuario WHERE codUsuario = ?");
    $stmt->execute([$id]);
    return $stmt->fetchObject();
  }

  // Método para buscar todos os usuários
  public function getAll() {
    $stmt = $this->pdo->prepare("SELECT * FROM tb_usuario");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_OBJ);
  }

    // Método para buscar um usuário pelo email e senha
    public function getUserByEmailAndPassword($email, $senha) {
      $stmt = $this->pdo->prepare("SELECT * FROM tb_usuario WHERE email = ? AND senha = ?");
      $stmt->execute([$email, $senha]);
      return $stmt->fetchObject();
    }

      public function checkUserByEmail($email) {
    $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM tb_usuario WHERE email = ?");
    $stmt->execute([$email]);
    $count = $stmt->fetchColumn();
    return $count > 0;
  }
}
?>
