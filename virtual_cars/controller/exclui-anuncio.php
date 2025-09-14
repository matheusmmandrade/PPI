<?php

require "../model/Anuncio.php";
require "../conexaoMysql.php";

session_start();
header('Content-Type: application/json; charset=utf-8');

if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] != 'POST') {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Acesso não autorizado.']);
    exit();
}

$idAnuncio = $_POST['id'] ?? null;
$idAnunciante = $_SESSION['user_id'];

if (!$idAnuncio) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'ID do anúncio não fornecido.']);
    exit();
}

try {
    $pdo = mysqlConnect();
    $anuncioModel = new Anuncio($pdo);

    $nomesFotos = $anuncioModel->excluir((int)$idAnuncio, (int)$idAnunciante);

    if (!empty($nomesFotos)) {
        $uploadDir = '../public/images/anuncios/';
        foreach ($nomesFotos as $nomeFoto) {
            $filePath = $uploadDir . $nomeFoto;
            if (file_exists($filePath)) {
                unlink($filePath); 
            }
        }
        echo json_encode(['success' => true, 'message' => 'Anúncio excluído com sucesso!']);
    } else {
        http_response_code(404); 
        echo json_encode(['success' => false, 'message' => 'Anúncio não encontrado ou você não tem permissão para excluí-lo.']);
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Ocorreu uma falha no servidor: ' . $e->getMessage()]);
}
?>