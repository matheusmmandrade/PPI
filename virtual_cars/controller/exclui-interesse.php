<?php

require "../model/Interesse.php";
require "../conexaoMysql.php";

//session_start();
header('Content-Type: application/json; charset=utf-8');

if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] != 'POST') {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Acesso não autorizado.']);
    exit();
}

$idInteresse = $_POST['id'] ?? null;
$idAnuncianteLogado = $_SESSION['user_id'];

if (!$idInteresse || !filter_var($idInteresse, FILTER_VALIDATE_INT)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'ID de interesse inválido.']);
    exit();
}

try {
    $pdo = mysqlConnect();
    $interesseModel = new Interesse($pdo);

    $sucesso = $interesseModel->excluir((int)$idInteresse, (int)$idAnuncianteLogado);

    if ($sucesso) {
        echo json_encode(['success' => true, 'message' => 'Mensagem excluída com sucesso!']);
    } else {
        http_response_code(403); 
        echo json_encode(['success' => false, 'message' => 'Não foi possível excluir a mensagem. Verifique suas permissões.']);
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Ocorreu uma falha no servidor.']);
}
?>