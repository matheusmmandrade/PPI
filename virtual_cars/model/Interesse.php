<?php

class Interesse
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }


    public function buscarPorAnuncio(int $idAnuncio): array
    {
        $sql = <<<SQL
            SELECT nome, telefone, mensagem, dataHora 
            FROM Interesse 
            WHERE idAnuncio = ? 
            ORDER BY dataHora DESC
        SQL;

        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$idAnuncio]);
            
            return $stmt->fetchAll();

        } catch (Exception $e) {
            error_log($e->getMessage());
            return [];
        }
    }

    public function excluir(int $idInteresse, int $idAnuncianteLogado): bool
    {
        $sql = <<<SQL
            DELETE Interesse 
            FROM Interesse
            INNER JOIN Anuncio ON Interesse.idAnuncio = Anuncio.id
            WHERE Interesse.id = ? AND Anuncio.idAnunciante = ?
        SQL;

        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$idInteresse, $idAnuncianteLogado]);
            
            return $stmt->rowCount() > 0;

        } catch (Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }
}