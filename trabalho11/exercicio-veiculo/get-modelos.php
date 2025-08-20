<?php

require "conexaoMysql.php";
$pdo = mysqlConnect();

$marca = $_GET['marca'] ?? '';

try {
    $sql = "SELECT DISTINCT modelo FROM veiculo WHERE marca = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$marca]);

    $modelos = array();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $modelos[] = $row['modelo'];
    }

    header('Content-type: application/json');
    echo json_encode($modelos);

} catch (Exception $e) {
    http_response_code(500);
    header('Content-type: application/json');
    echo json_encode([]);
}

?>