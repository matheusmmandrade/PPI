<?php

require "../model/Anuncio.php";
require "../conexaoMysql.php";

header('Content-Type: application/json; charset=utf-8');

$acao = $_GET['acao'] ?? '';
$pdo = mysqlConnect();
$anuncioModel = new Anuncio($pdo);

try {
    switch ($acao) {
        case 'marcas':
            $resultado = $anuncioModel->getMarcasDistintas();
            break;
        
        case 'modelos':
            $marca = $_GET['marca'] ?? '';
            $resultado = $anuncioModel->getModelosDistintosPorMarca($marca);
            break;
            
        case 'cidades':
            $marca = $_GET['marca'] ?? '';
            $modelo = $_GET['modelo'] ?? '';
            $resultado = $anuncioModel->getCidadesDistintasPorMarcaModelo($marca, $modelo);
            break;
            
        case 'buscar':
            $filtros = [
                'marca' => $_GET['marca'] ?? '',
                'modelo' => $_GET['modelo'] ?? '',
                'cidade' => $_GET['cidade'] ?? ''
            ];
            $resultado = $anuncioModel->buscarPublico($filtros);
            break;
            
        default:
            http_response_code(400); // Bad Request
            $resultado = ['success' => false, 'message' => 'Ação inválida.'];
            break;
    }
    
    echo json_encode($resultado);

} catch (Exception $e) {
    http_response_code(500); // Internal Server Error
    echo json_encode(['success' => false, 'message' => 'Erro no servidor: ' . $e->getMessage()]);
}

?>