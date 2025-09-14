<?php

require "../model/Anuncio.php";
require "../conexaoMysql.php";

header('Content-Type: application/json; charset=utf-8');

$pdo = mysqlConnect();
$anuncioModel = new Anuncio($pdo);

$action = $_GET['action'] ?? '';

try {
    switch ($action) {
        case 'getMarcas':
            $response = $anuncioModel->getMarcasDistintas();
            break;

        case 'getModelos':
            $marca = $_GET['marca'] ?? '';
            $response = $anuncioModel->getModelosDistintos($marca);
            break;

        case 'getCidades':
            $marca = $_GET['marca'] ?? '';
            $modelo = $_GET['modelo'] ?? '';
            $response = $anuncioModel->getCidadesDistintas($marca, $modelo);
            break;

        case 'getAnuncios':
            $filtros = [
                'marca' => $_GET['marca'] ?? '',
                'modelo' => $_GET['modelo'] ?? '',
                'cidade' => $_GET['localizacao'] ?? ''
            ];
            $response = $anuncioModel->buscarPorFiltros($filtros);
            break;

        default:
            http_response_code(400);
            $response = ['error' => 'Ação inválida'];
            break;
    }

    echo json_encode($response);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Falha no servidor: ' . $e->getMessage()]);
}

?>