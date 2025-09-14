<?php

function mysqlConnect()
{
  $db_host = "sql208.infinityfree.com";
  $db_username = "if0_39210014";
  $db_password = "pouM4xu1ngY";
  $db_name = "if0_39210014_ppi";

  $options = [
    PDO::ATTR_EMULATE_PREPARES => false,
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC // Define o padrão de busca para array associativo

  ];

  try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8mb4", $db_username, $db_password, $options);
    return $pdo;
  } catch (Exception $e) {
    exit('Ocorreu uma falha na conexão com o MySQL: ' . $e->getMessage());
  }
}
?>