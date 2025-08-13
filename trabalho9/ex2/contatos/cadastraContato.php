<?php

require "contatos.php";

// Coleta todos os dados do formulário
$nome = $_POST["nome"] ?? "";
$cpf = $_POST["cpf"] ?? "";
$email = $_POST["email"] ?? "";
$senha = $_POST["senha"] ?? "";
$cep = $_POST["cep"] ?? "";
$endereco = $_POST["endereco"] ?? "";
$bairro = $_POST["bairro"] ?? "";
$cidade = $_POST["cidade"] ?? "";
$estado = $_POST["estado"] ?? "";

// CRÍTICO: Nunca salve senhas em texto puro. Gere um hash.
$senhaHash = password_hash($senha, PASSWORD_DEFAULT);

// Cria um novo contato com todos os dados e o salva
$novoContato = new Contato($nome, $cpf, $email, $senhaHash, $cep, $endereco, $bairro, $cidade, $estado);
$novoContato->SalvaEmArquivo();

// Redireciona para a página de listagem
header("location: listaContatos.php");
exit(); // Boa prática: encerrar o script após redirecionar

?>