<?php

require "conexaoMysql.php";
$pdo = mysqlConnect();

$modelo = $_GET['modelo'] ?? '';

try {
    $sql = "SELECT modelo, ano, cor, quilometragem FROM veiculo WHERE modelo = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$modelo]);

    $veiculos = $stmt->fetchAll(PDO::FETCH_OBJ);

    header('Content-type: application/json');
    echo json_encode($veiculos);

} catch (Exception $e) {
    http_response_code(500);
    header('Content-type: application/json');
    echo json_encode([]);
}

?>