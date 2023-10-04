<?php
class PurchaseBookDAO {
    private $pdo;

    function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Método para criar uma nova compra de livro
    public function create($purchaseBook) {
        $stmt = $this->pdo->prepare("INSERT INTO tb_compra_livro (codCompra, codLivro, qtd,valorUnitario) VALUES (?, ?, ?,?)");
        $stmt->execute([$purchaseBook->codCompra, $purchaseBook->codLivro, $purchaseBook->quantidade, $purchaseBook->valorUnitario]);
        return $stmt->rowCount() > 0;
    }

    // Método para atualizar uma compra de livro
    public function update($codCompra, $purchaseBook) {
        $stmt = $this->pdo->prepare("UPDATE tb_compra_livro SET qtd = ?, codLivro = ?, valorUnitario = ? WHERE codCompra = ?");
        $stmt->execute([$purchaseBook->qtd, $purchaseBook->codLivro,$purchaseBook->valorUnitario, $codCompra]);
        return $stmt->rowCount() > 0;
    }
    // Método para deletar uma compra de livro pelo ID da compra
    public function deleteByPurchaseId($codCompra) {
        $stmt = $this->pdo->prepare("DELETE FROM tb_compra_livro WHERE codCompra = ?");
        $stmt->execute([$codCompra]);
        return $stmt->rowCount() > 0;
    }

    // Método para buscar uma compra de livro pelo ID da compra e do livro
    public function getByPurchaseAndBookIds($codCompra, $codLivro) {
        $stmt = $this->pdo->prepare("SELECT * FROM tb_compra_livro WHERE codCompra = ? AND codLivro = ?");
        $stmt->execute([$codCompra, $codLivro]);
        return $stmt->fetchObject();
    }

    // Método para buscar todas as compras de livro de uma determinada compra
    public function getByPurchaseId($codCompra) {
        $stmt = $this->pdo->prepare("SELECT * FROM tb_compra_livro WHERE codCompra = ?");
        $stmt->execute([$codCompra]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
}

?>