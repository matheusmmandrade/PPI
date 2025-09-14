<?php
//TO DO:
session_start();
//TO DO: referencia correta?
if (!isset($_SESSION['user_id'])) {
    header('Location: ../user/login.html');
    exit();
}

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Interna</title>

    <link rel="stylesheet" href="../../public/css/style.css">
    <link rel="stylesheet" href="interna.css">
</head>

<body>
    <header>
        <nav>
            <a href="../home/index.html">
                <img class="logo-image-nav" src="../../public/images/icon.png" alt="logo">
            </a>

            <a href="../../controller/logout.php" class="btn-login">
                Logout
            </a>
        </nav>
    </header>
    <main>
        <div class="funcionalidades">
            <h1>Painel do Usuário</h1>
            <div class="funcionalidade">
                <a href="criarAnuncio.html">
                    <div class="botao">Novo Anúncio</div>
                </a>
            </div>
            <div class="funcionalidade">
                <a href="../anuncio/listagem/listagemAnuncios.html">
                    <div class="botao">Listagem de Anúncio</div>
                </a>
            </div>
            <div class="funcionalidade">
                <a href="../anuncio/listagem/listagemInteresses.html">
                    <div class="botao">Listagem de Interesses</div>
                </a>
            </div>
        </div>
    </main>
    <footer>
        <h3>Vitual Cars não é um site real</h3>
    </footer>
</body>

</html>