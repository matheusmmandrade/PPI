<?php

class Contato
{
  public $nome;
  public $cpf;
  public $email;
  public $senhaHash; // Armazenaremos um hash seguro da senha
  public $cep;
  public $endereco;
  public $bairro;
  public $cidade;
  public $estado;

   function __construct($nome, $cpf, $email, $senhaHash, $cep, $endereco, $bairro, $cidade, $estado)
    {
        $this->nome = $nome;
        $this->cpf = $cpf;
        $this->email = $email;
        $this->senhaHash = $senhaHash;
        $this->cep = $cep;
        $this->endereco = $endereco;
        $this->bairro = $bairro;
        $this->cidade = $cidade;
        $this->estado = $estado;
    }

  public function SalvaEmArquivo()
    {
        // Abre o arquivo de texto para escrita no final (append)
        // O nome do arquivo agora é 'clientes.txt'
        $arq = fopen("clientes.txt", "a");

        // Formata a string com todos os novos dados, separados por ';'
        $dados = "{$this->nome};{$this->cpf};{$this->email};{$this->senhaHash};{$this->cep};{$this->endereco};{$this->bairro};{$this->cidade};{$this->estado}\n";
        
        fwrite($arq, $dados);
        fclose($arq);
    }
}

// Carrega as informações dos contatos do arquivo de texto
// e retorna um array de objetos correspondente
function carregaContatosDeArquivo()
{
    $arrayContatos = [];
    $nomeArquivo = "clientes.txt";

    // Verifica se o arquivo existe antes de tentar abrir
    if (!file_exists($nomeArquivo)) {
        return $arrayContatos;
    }

    $arq = fopen($nomeArquivo, "r");
    if (!$arq)
        return $arrayContatos;

    // Lê o arquivo linha por linha
    while (($linha = fgets($arq)) !== false) {
        $contato = trim($linha);

        if ($contato != "") {
            // Separa os dados usando ';' como separador
            // array_pad garante que 9 elementos existirão, mesmo que vazios, evitando erros
            $dados = array_pad(explode(';', $contato), 9, null);
            list($nome, $cpf, $email, $senhaHash, $cep, $endereco, $bairro, $cidade, $estado) = $dados;

            // Cria novo objeto e adiciona ao array
            $novoContato = new Contato($nome, $cpf, $email, $senhaHash, $cep, $endereco, $bairro, $cidade, $estado);
            $arrayContatos[] = $novoContato;
        }
    }

    fclose($arq);
    return $arrayContatos;
}
