<?php

require "conexaoMysql.php";
$pdo = mysqlConnect();

$cep = $_GET['cep'] ?? '';

try {
  $stmt = $pdo->prepare(
    <<<SQL
    SELECT cep, logradouro AS rua, bairro, cidade
    FROM endereco
    WHERE cep = ?
    SQL
  );

  $stmt->execute([$cep]);
  $endereco = $stmt->fetch(PDO::FETCH_OBJ);
  header('Content-Type: application/json; charset=utf-8');
  echo json_encode($endereco ?: new stdClass());
} catch (Exception $e) {
  exit("Falha ao buscar endereco: " . $e->getMessage());
}




