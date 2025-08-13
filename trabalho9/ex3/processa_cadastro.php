<?php

// Coleta os dados do formulário
$email = $_POST['email'] ?? '';
$senha = $_POST['senha'] ?? '';

// Validação mínima para garantir que os campos não estão vazios
if (!empty($email) && !empty($senha)) {
    
    // Gera o hash da senha usando o algoritmo padrão e mais seguro
    $hashSenha = password_hash($senha, PASSWORD_DEFAULT);
    
    // Formata a linha a ser salva no arquivo (email;hash)
    $linha = $email . ";" . $hashSenha . "\n";
    
    // Abre o arquivo usuarios.txt no modo 'append' (adicionar ao final)
    // A função file_put_contents com a flag FILE_APPEND é uma forma segura e simples de fazer isso.
    file_put_contents('usuarios.txt', $linha, FILE_APPEND);
    
    // Redireciona o usuário de volta para a página principal
    echo "Usuário cadastrado com sucesso!";
    header('Refresh: 2; URL=index.html'); // Aguarda 2 segundos antes de redirecionar
    
} else {
    // Caso algum campo esteja vazio, informa o erro e redireciona
    echo "Erro: E-mail e senha são obrigatórios.";
    header('Refresh: 2; URL=cadastrar.html');
}

exit();
?>