<?php

$email_form = $_POST['email'] ?? '';
$senha_form = $_POST['senha'] ?? '';

$login_sucesso = false;
$arquivo = 'usuarios.txt';

if (!empty($email_form) && !empty($senha_form) && file_exists($arquivo)) {
    
    $linhas = file($arquivo, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    
    foreach ($linhas as $linha) {
        list($email_armazenado, $hash_armazenado) = explode(';', $linha, 2);
        
        // Verifica se o e-mail do formulário corresponde ao e-mail da linha atual
        if ($email_form === $email_armazenado) {
            
            // Se o e-mail bate, verifica se a senha do formulário corresponde ao hash
            if (password_verify($senha_form, $hash_armazenado)) {
                $login_sucesso = true;
                break; // Encontrou o usuário e a senha está correta, para o loop
            }
        }
    }
}

// Redireciona com base no sucesso ou falha do login
if ($login_sucesso) {
    header('Location: login_sucesso.html');
} else {
    // Em um sistema real, seria bom adicionar uma mensagem de erro na página de login
    header('Location: login.html');
}

exit();
?>