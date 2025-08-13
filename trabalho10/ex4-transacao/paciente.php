<?php

class Paciente
{

    static function Create(
        $pdo,
        $nome,
        $sexo,
        $email,
        $peso,
        $altura,
        $tipoSanguineo
    ) {
        try {
            $pdo->beginTransaction();

            $sql1 = <<<SQL
        INSERT INTO Pessoa (Nome, Sexo, Email)
        VALUES (?, ?, ?)
      SQL;

            $stmt1 = $pdo->prepare($sql1);
            $stmt1->execute([$nome, $sexo, $email]);

            $idNovaPessoa = $pdo->lastInsertId();

            $sql2 = <<<SQL
        INSERT INTO Paciente (IdPessoa, Peso, Altura, TipoSanguineo)
        VALUES (?, ?, ?, ?)
      SQL;

            $stmt2 = $pdo->prepare($sql2);
            $stmt2->execute([$idNovaPessoa, $peso, $altura, $tipoSanguineo]);

            $pdo->commit();

        } catch (Exception $e) {
            $pdo->rollBack();
            throw $e;
        }
    }
}