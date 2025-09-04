<?php
/*
  o fato de os dados serem passados por POST não oferece proteção automática contra CSRF.
  um site malicioso pode criar um formulário oculto e usar JavaScript para enviá-lo
  automaticamente para este script (altera-senha.php) via POST.
  como o navegador do usuário envia os cookies de sessão automaticamente com qualquer
  requisição para este domínio, o servidor acreditaria que a solicitação é legítima.
  o token CSRF é necessário para garantir que a solicitação foi originada pelo nosso
  próprio formulário, pois só ele conhece o valor do token correto para aquela sessão.
*/
require "conexaoMysql.php";
require "sessionVerification.php";

session_start();
exitWhenNotLoggedIn();
// validação CSRF:
// o script verifica se o token enviado pelo formulário via POST existe e se é
// exatamente igual ao token que foi armazenado na sessão do usuário durante o login.
// se os tokens não baterem, significa que a solicitação pode ser forjada,
// então o script é interrompido para evitar a alteração de senha não autorizada.

if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token'])
  exit('Operação não permitida.');

$pdo = mysqlConnect();
$email = $_POST['email'] ?? "";
$novaSenha = $_POST['novaSenha'] ?? "";
$senhaHash = password_hash($novaSenha, PASSWORD_DEFAULT);

try {
  $stmt = $pdo->prepare(
    <<<SQL
      UPDATE cliente
      SET senhaHash = ?
      WHERE email = ?
    SQL
  );
  $stmt->execute([$senhaHash, $email]);
  header("location: sucesso.html");
} catch (Exception $e) {
  exit('Falha inesperada: ' . $e->getMessage());
}