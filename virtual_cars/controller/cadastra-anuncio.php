<?php

require "../model/Anuncio.php";
require "../conexaoMysql.php";

session_start();

//Comentar aqui
//$_SESSION['user_id'] = 1; 

//Descometnar aqui
// if (!isset($_SESSION['user_id'])) {
//     http_response_code(401); 
//     echo json_encode(['success' => false, 'message' => 'Usuário não autenticado.']);
//     exit();
// }


if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    http_response_code(405); 
    echo json_encode(['success' => false, 'message' => 'Método de requisição não permitido.']);
    exit();
}


$marca = $_POST['marca'] ?? '';
$modelo = $_POST['modelo'] ?? '';
$ano = $_POST['ano'] ?? 0;
$cor = $_POST['cor'] ?? '';
$quilometragem = $_POST['quilometragem'] ?? 0;
$descricao = $_POST['descricao'] ?? '';
$valor = $_POST['valor'] ?? 0.0;
$estado = $_POST['estado'] ?? '';
$cidade = $_POST['cidade'] ?? '';
$idAnunciante = $_SESSION['user_id'];


$nomesFotosSalvas = [];
$uploadDir = '../public/images/anuncios/'; 

if (isset($_FILES['fotos']) && count($_FILES['fotos']['name']) > 0) {
    for ($i = 0; $i < count($_FILES['fotos']['name']); $i++) {
        if ($_FILES['fotos']['error'][$i] === UPLOAD_ERR_OK) {
            $tmpName = $_FILES['fotos']['tmp_name'][$i];
            
            $originalName = basename($_FILES['fotos']['name'][$i]);
            $extension = pathinfo($originalName, PATHINFO_EXTENSION);
            $novoNome = uniqid() . '-' . time() . '.' . $extension;
            
            $destination = $uploadDir . $novoNome;

            if (move_uploaded_file($tmpName, $destination)) {
                $nomesFotosSalvas[] = $novoNome;
            }
        }
    }
}

if (empty($nomesFotosSalvas)) {
    http_response_code(400); 
    echo json_encode(['success' => false, 'message' => 'Nenhuma foto válida foi enviada.']);
    exit();
}



try {
    $pdo = mysqlConnect();
    $anuncioModel = new Anuncio($pdo);

    $sucesso = $anuncioModel->cadastrar(
        $marca, $modelo, (int)$ano, $cor, (int)$quilometragem, 
        $descricao, (float)$valor, $estado, $cidade, 
        (int)$idAnunciante, $nomesFotosSalvas
    );


    header('Content-Type: application/json');
    if ($sucesso) {
        echo json_encode(['success' => true, 'message' => 'Anúncio cadastrado com sucesso!']);
    } else {
        http_response_code(500); 
        echo json_encode(['success' => false, 'message' => 'Falha ao cadastrar o anúncio no banco de dados.']);
    }

} catch (Exception $e) {
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Ocorreu um erro inesperado: ' . $e->getMessage()]);
}
?>