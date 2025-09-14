<?php

require "../model/Anuncio.php";
require "../conexaoMysql.php";

//session_start();

header('Content-Type: application/json; charset=utf-8');


//Comentar aqui
//$_SESSION['user_id'] = 1; 

// if (!isset($_SESSION['user_id'])) {
//     echo json_encode([]);
//     exit();
// }

$idAnunciante = $_SESSION['user_id'];

try {
    $pdo = mysqlConnect();
    $anuncioModel = new Anuncio($pdo);

    $anuncios = $anuncioModel->buscarPorAnunciante($idAnunciante);
    
    echo json_encode($anuncios);

} catch (Exception $e) {
    http_response_code(500);
    $response = ['success' => false, 'message' => 'Ocorreu uma falha no servidor: ' . $e->getMessage()];
    echo json_encode($response);
}

?>