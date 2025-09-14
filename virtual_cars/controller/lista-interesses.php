<?php

require "../model/Anuncio.php";
require "../model/Interesse.php";
require "../conexaoMysql.php";

session_start();
header('Content-Type: application/json; charset=utf-8');

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Usuário não autenticado.']);
    exit();
}

$idAnuncio = $_GET['id'] ?? null;
if (null === $idAnuncio || !filter_var($idAnuncio, FILTER_VALIDATE_INT) || $idAnuncio <= 0) {
    http_response_code(400); 
    echo json_encode(['success' => false, 'message' => 'ID do anúncio inválido.']);
    exit();
}

$idAnuncianteLogado = $_SESSION['user_id'];

try {
    $pdo = mysqlConnect();

    $anuncioModel = new Anuncio($pdo);
    $anuncio = $anuncioModel->buscarPorId((int)$idAnuncio);

    if (!$anuncio || $anuncio['idAnunciante'] != $idAnuncianteLogado) {
        http_response_code(403); // Forbidden
        echo json_encode(['success' => false, 'message' => 'Você não tem permissão para ver os interesses deste anúncio.']);
        exit();
    }

    $interesseModel = new Interesse($pdo);
    $interesses = $interesseModel->buscarPorAnuncio((int)$idAnuncio);

    echo json_encode($interesses);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Ocorreu uma falha no servidor: ' . $e->getMessage()]);
}
?>