<?php

require "../model/Anuncio.php";
require "../conexaoMysql.php";

header('Content-Type: application/json; charset=utf-8');

$idAnuncio = $_GET['id'] ?? null;

if (null === $idAnuncio || !filter_var($idAnuncio, FILTER_VALIDATE_INT) || $idAnuncio <= 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'ID do anúncio é inválido.']);
    exit();
}

try {
    $pdo = mysqlConnect();
    $anuncioModel = new Anuncio($pdo);

    $anuncio = $anuncioModel->buscarPorId((int)$idAnuncio);

    if ($anuncio) {
        echo json_encode($anuncio);
    } else {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Anúncio não encontrado.']);
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Ocorreu uma falha no servidor: ' . $e->getMessage()]);
}

?>