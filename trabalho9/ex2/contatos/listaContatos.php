<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Página Dinâmica - Listagem de Contatos - Segura</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
</head>

<body>

    <div class="container">
        <h3 class="my-4">Contatos Carregados do Arquivo <i>clientes.txt</i></h3>

        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>CPF</th>
                        <th>E-mail</th>
                        <th>Endereço</th>
                        <th>Cidade</th>
                        <th>Estado</th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                    require "contatos.php";
                    $arrayContatos = carregaContatosDeArquivo();
                    foreach ($arrayContatos as $contato) {
                        // Aplica htmlspecialchars em cada campo para segurança (prevenção de XSS)
                        $nome = htmlspecialchars($contato->nome);
                        $cpf = htmlspecialchars($contato->cpf);
                        $email = htmlspecialchars($contato->email);
                        $endereco = htmlspecialchars($contato->endereco);
                        $cidade = htmlspecialchars($contato->cidade);
                        $estado = htmlspecialchars($contato->estado);
                        
                        echo <<<HTML
                            <tr>
                                <td>$nome</td>
                                <td>$cpf</td>
                                <td>$email</td>
                                <td>$endereco</td>
                                <td>$cidade</td>
                                <td>$estado</td>
                            </tr>
                        HTML;
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <a href="novoContato.html" class="btn btn-primary">Cadastrar Novo Contato</a>
    </div>

</body>
</html>