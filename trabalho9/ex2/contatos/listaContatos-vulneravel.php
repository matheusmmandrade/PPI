<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Página Dinâmica - Listagem de Contatos - Vulnerável</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
</head>

<body>
    <div class="container">
        <h3 class="my-4">Contatos Carregados do Arquivo <i>clientes.txt</i></h3>
        <p class="text-danger"><strong>AVISO:</strong> Esta página é vulnerável a ataques XSS.</p>

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
                        // Os dados são impressos diretamente, sem tratamento de segurança
                        echo <<<HTML
                            <tr>
                                <td>$contato->nome</td>
                                <td>$contato->cpf</td>
                                <td>$contato->email</td>
                                <td>$contato->endereco</td>
                                <td>$contato->cidade</td>
                                <td>$contato->estado</td>
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