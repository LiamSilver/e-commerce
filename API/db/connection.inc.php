<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db_ecommerce";

function getConnection()
{
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "db_ecommerce";

    try {
        $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        die("Erro ao conectar ao banco de dados: " . $e->getMessage());
    }
}