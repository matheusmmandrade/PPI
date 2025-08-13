<?php

class Produto
{

  // Método estático para criar um novo cliente por meio
  // da inserção na tabela 'cliente' do BD.
  // Métodos estáticos estão associados à classe em si, e não a uma instância.
  // No PHP devem ser chamados com a sintaxe: NomeDaClasse::NomeDoMétodoEstático
  static function Create($pdo, $nome, $marca, $descricao)
  {
    // Neste caso é necessário utilizar prepared statements para prevenir
    // inj. de S Q L, pois temos parâmetros (dados do cliente) fornecidos pelo usuário.
    // Repare que a coluna Id foi omitida por ser do tipo auto_increment.
    $stmt = $pdo->prepare(
      <<<SQL
      INSERT INTO produto (nome, marca, descricao)
      VALUES (?, ?, ?)
      SQL
    );

    // Executa a declaração preparada fornecendo valores aos parâmetros (pontos-de-interrogação)
    $stmt->execute([$nome, $marca, $descricao]);

    // retorna o Id do novo cliente criado
    return $pdo->lastInsertId();
  }

  // Busca um cliente na tabela a partir do Id e retorna
  // os dados na forma de um objeto PHP.
  static function Get($pdo, $id)
  {
    $stmt = $pdo->prepare(
      <<<SQL
      SELECT id, nome, marca, descricao
      WHERE id = ?
      SQL
    );

    $stmt->execute([$id]);
    if ($stmt->rowCount() == 0)
      throw new Exception("Produto não localizado");

    $cliente = $stmt->fetch(PDO::FETCH_OBJ);
    return $cliente;
  }

  // Retorna os 30 clientes iniciais da tabela na forma de um array de objetos.
  static function GetFirst30($pdo)
  {
    // Neste exemplo não é necessário utilizar prepared statements
    // porque não há a possibilidade de inj. de S Q L, 
    // pois nenhum parâmetro do usuário é utilizado na query SQL. 
    $stmt = $pdo->query(
      <<<SQL
      SELECT id, nome, marca, descricao
      SQL
    );

    // Resgata os dados dos clientes como um array de objetos
    $arrayClientes = $stmt->fetchAll(PDO::FETCH_OBJ);
    return $arrayClientes;
  }

  // Método estático para excluir um cliente dado o seu Id
  public static function Remove($pdo, $id)
  {
    $sql = <<<SQL
    DELETE 
    FROM produto
    WHERE id = ?
    LIMIT 1
    SQL;

    // Necessário utilizar prepared statements devido ao 
    // parâmetro informado pelo usuário
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
  }
}
