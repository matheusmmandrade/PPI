<?php

require "conexaoMysql.php";
$pdo = mysqlConnect();

try {
    $sql = "SELECT DISTINCT marca FROM veiculo";
    $stmt = $pdo->query($sql);

    $marcas = array();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $marcas[] = $row['marca'];
    }

    header('Content-type: application/json');
    echo json_encode($marcas);

} catch (Exception $e) {
    http_response_code(500);
    header('Content-type: application/json');
    echo json_encode([]);
}

?>