<?php
// define uma função reutilizável para proteger páginas.

function exitWhenNotLoggedIn()
{   // verifica se a variável 'loggedIn' não existe na sessão do usuário.

  if (!isset($_SESSION['loggedIn'])) {    // se não existir, redireciona para a página de login.

    header("Location: index.html");
    // para a execução do script para garantir que nenhum conteúdo restrito seja exibido.

    exit();
  }
}
