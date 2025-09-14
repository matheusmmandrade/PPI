<?php

class Anuncio
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function cadastrar(
        string $marca, string $modelo, int $ano, string $cor, int $quilometragem, 
        string $descricao, float $valor, string $estado, string $cidade, 
        int $idAnunciante, array $fotos
    ): bool
    {
        $this->pdo->beginTransaction();

        try {
            $sqlAnuncio = <<<SQL
                INSERT INTO Anuncio (marca, modelo, ano, cor, quilometragem, descricao, valor, estado, cidade, idAnunciante)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            SQL;

            $stmtAnuncio = $this->pdo->prepare($sqlAnuncio);
            $stmtAnuncio->execute([
                $marca, $modelo, $ano, $cor, $quilometragem, $descricao, 
                $valor, $estado, $cidade, $idAnunciante
            ]);

            $idAnuncio = $this->pdo->lastInsertId();

            $sqlFoto = <<<SQL
                INSERT INTO Foto (idAnuncio, nomeArqFoto)
                VALUES (?, ?)
            SQL;
            
            $stmtFoto = $this->pdo->prepare($sqlFoto);
            
            foreach ($fotos as $nomeFoto) {
                $stmtFoto->execute([$idAnuncio, $nomeFoto]);
            }

            $this->pdo->commit();
            return true;

        } catch (Exception $e) {
            $this->pdo->rollBack();

            return false;
        }
    }

    public function buscarPorAnunciante(int $idAnunciante): array
    {
        $sql = <<<SQL
            SELECT
                a.id, 
                a.marca, 
                a.modelo, 
                a.ano,
                (SELECT nomeArqFoto FROM Foto WHERE idAnuncio = a.id LIMIT 1) as foto
            FROM Anuncio a
            WHERE a.idAnunciante = ?
            ORDER BY a.dataHora DESC
        SQL;

        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$idAnunciante]);
            
            return $stmt->fetchAll();

        } catch (Exception $e) {
            error_log($e->getMessage());
            return [];
        }
    }

    public function excluir(int $idAnuncio, int $idAnunciante): array
    {
        try {
            $sqlFotos = "SELECT nomeArqFoto FROM Foto WHERE idAnuncio = ?";
            $stmtFotos = $this->pdo->prepare($sqlFotos);
            $stmtFotos->execute([$idAnuncio]);
            
            $nomesFotos = $stmtFotos->fetchAll(PDO::FETCH_COLUMN);

            $sqlDelete = "DELETE FROM Anuncio WHERE id = ? AND idAnunciante = ?";
            $stmtDelete = $this->pdo->prepare($sqlDelete);
            $stmtDelete->execute([$idAnuncio, $idAnunciante]);

            if ($stmtDelete->rowCount() > 0) {
                return $nomesFotos;
            } else {
                return []; 
            }

        } catch (Exception $e) {
            error_log($e->getMessage());
            return [];
        }
    }

    public function buscarPorId(int $idAnuncio): ?array
    {
        try {
            $sqlAnuncio = "SELECT * FROM Anuncio WHERE id = ?";
            $stmtAnuncio = $this->pdo->prepare($sqlAnuncio);
            $stmtAnuncion->execute([$idAnuncio]);
            $anuncio = $stmtAnuncio->fetch();

            if (!$anuncio) {
                return null;
            }

            $sqlFotos = "SELECT nomeArqFoto FROM Foto WHERE idAnuncio = ?";
            $stmtFotos = $this->pdo->prepare($sqlFotos);
            $stmtFotos->execute([$idAnuncio]);
            
            $fotos = $stmtFotos->fetchAll(PDO::FETCH_COLUMN);

            $anuncio['fotos'] = $fotos;

            return $anuncio;

        } catch (Exception $e) {
            error_log($e->getMessage());
            return null;
        }
    }


    public function getMarcasDistintas(): array
    {
        $sql = "SELECT DISTINCT marca FROM Anuncio WHERE marca IS NOT NULL AND marca != '' ORDER BY marca ASC";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function getModelosDistintosPorMarca(string $marca): array
    {
        if (empty($marca)) return [];
        $sql = "SELECT DISTINCT modelo FROM Anuncio WHERE marca = ? ORDER BY modelo ASC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$marca]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
    
    public function getCidadesDistintasPorMarcaModelo(string $marca, string $modelo): array
    {
        if (empty($marca) || empty($modelo)) return [];
        $sql = "SELECT DISTINCT cidade FROM Anuncio WHERE marca = ? AND modelo = ? ORDER BY cidade ASC";
        $stmt = $this.pdo->prepare($sql);
        $stmt->execute([$marca, $modelo]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function buscarPublico(array $filtros): array
    {
        $sql = <<<SQL
            SELECT
                a.id, a.marca, a.modelo, a.ano, a.cidade, a.valor,
                (SELECT nomeArqFoto FROM Foto WHERE idAnuncio = a.id LIMIT 1) as fotoCapa
            FROM Anuncio a
            WHERE 1=1
        SQL;

        $params = [];

        if (!empty($filtros['marca'])) {
            $sql .= " AND a.marca = ?";
            $params[] = $filtros['marca'];
        }
        if (!empty($filtros['modelo'])) {
            $sql .= " AND a.modelo = ?";
            $params[] = $filtros['modelo'];
        }
        if (!empty($filtros['cidade'])) {
            $sql .= " AND a.cidade = ?";
            $params[] = $filtros['cidade'];
        }
        
        $sql .= " ORDER BY a.dataHora DESC LIMIT 20";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}