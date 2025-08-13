<?php
// Força o cabeçalho para UTF-8 para evitar problemas de codificação
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuários Cadastrados</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
    <div class="container">
        <h2>Usuários Cadastrados</h2>
        <p>A senha nunca é armazenada, apenas seu HASH.</p>
        
        <table class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>E-mail</th>
                    <th>Hash da Senha</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $arquivo = 'usuarios.txt';
                if (file_exists($arquivo) && filesize($arquivo) > 0) {
                    // Lê o arquivo para um array, cada linha se torna um elemento
                    $linhas = file($arquivo, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                    
                    foreach ($linhas as $linha) {
                        // Separa a linha em email e hash usando o ';'
                        list($email, $hash) = explode(';', $linha, 2);
                        
                        // Exibe os dados na tabela, usando htmlspecialchars por segurança
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($email) . "</td>";
                        echo "<td>" . htmlspecialchars($hash) . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo '<tr><td colspan="2" class="text-center">Nenhum usuário cadastrado.</td></tr>';
                }
                ?>
            </tbody>
        </table>
        <a href="index.html" class="btn btn-primary mt-3">Voltar para a página principal</a>
    </div>
</body>
</html>