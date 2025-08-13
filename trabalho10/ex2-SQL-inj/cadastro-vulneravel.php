<?php

require "../conexaoMysql.php";
$pdo = mysqlConnect();

$nome = $_POST["nome"] ?? "";
$telefone = $_POST["telefone"] ?? "";

$sql = <<<SQL
 INSERT INTO aluno(nome, telefone)
 VALUES(? , ?)
 SQL;

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$nome, $telefone]);
} catch (Exception $e) {
    exit('Falhainesperada: ' . $e->getMessage());
}

// try {

//   // NÃO FAÇA ISSO! Exemplo de código vulnerável a inj. de S-Q-L
//   $sql = <<<SQL
//   INSERT INTO aluno (nome, telefone)
//   VALUES ('$nome', '$telefone');
//   SQL;  

//   // Experimente fazer o cadastro de um novo aluno preenchendo 
//   // o campo telefone utilizando o texto disponibilizado pelo professor
//   // nos slides de aula
//   $pdo->exec($sql);
//   header("location: mostra-alunos.php");
//   exit();
// } 
// catch (Exception $e) {  
//   exit('Falha ao cadastrar os dados: ' . $e->getMessage());
// }

// Quando o PHP avalia a string $sql e substitui os nomes das variáveis $nome e $telefone pelos respectivos conteúdos (fornecidos pelo usuário), uma nova 
// string é obtida, a qual passa a ter dois comandos SQL válidos seguidos de um comentário em SQL. Através do método exec, essa string é então repassada 
// ao servidor do MySQL, que irá executá-la sem "saber" que ela foi alterada indevidamente no código PHP.  Repare que o usuário acaba conseguindo 
// introduzir seu próprio comando SQL (DELETE) dentro da string SQL do desenvolvedor, sem produzir qualquer erro de sintaxe

//  Ao inserir o texto tolo ' or ''=' 
// nos campos do formulário o 
// usuário conseguiria burlar a 
// validação de login injetando 
// condições na consulta SQL 
// que resultariam sempre em 
// verdadeiro e mudaria o 
// propósito da consulta original