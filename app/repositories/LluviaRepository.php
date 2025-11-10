<?php

class LluviaRepository
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getCon();
    }


    public function getAll()
    {
        $query = "SELECT * FROM lluvias ORDER BY fecha DESC;";

        $stmt = $this->db->prepare($query);
        $stmt->execute();

        $lluvias = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $lluvias[] = new Lluvia($row);
        }

        return $lluvias;
    }

    public function findByFecha($fecha)
    {
        $query = "SELECT * FROM lluvias WHERE fecha = :fecha LIMIT 1;";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":fecha", $fecha);
        $stmt->execute();

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return $data ? new Lluvia($data) : null;
    }

    public function update(Lluvia $lluvia)
    {
        if (!$lluvia->getFecha()) {
            throw new InvalidArgumentException("No se puede actualizar sin tener el dato de lluvia");
        }

        $query = "UPDATE lluvias SET cantidad = :cantidad WHERE fecha = :fecha;";
        $stmt = $this->db->prepare($query);

        $stmt->bindValue(":cantidad", $lluvia->getCantidad());
        $stmt->bindValue(":fecha", $lluvia->getFecha());

        return $stmt->execute();
    }

    public function guardar(Lluvia $lluvia)
    {
        $query = "INSERT INTO lluvias (fecha, cantidad) VALUES (:fecha, :cantidad);";

        $stmt = $this->db->prepare($query);

        // Utilizamos bindValue ya que hace una copia del valor del retorno de la función get

        $stmt->bindValue(":fecha", $lluvia->getFecha());
        $stmt->bindValue(":cantidad", $lluvia->getCantidad());

        return $stmt->execute() ? $lluvia->getFecha() : false;
    }

    public function getMeses()
    {
        $query = "SELECT
                MONTH(fecha) AS mes,
                SUM(cantidad) AS total_cantidad
                FROM lluvias
                GROUP BY MONTH(fecha)
                ORDER BY mes;";

        $stmt = $this->db->prepare($query);

        $resultado = [
            "mes" => [],
            "cantidad" => [],
        ];

        $stmt->execute();

        while ($res = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $resultado['mes'][] = $res['mes'];
            $resultado['cantidad'][] = $res['total_cantidad'];
        }

        return $resultado;
    }

    public function getTotalLluviaPorMes()
    {
        $query = "SELECT
                    MAX(total_lluvia) AS max_lluvia
                    FROM (
                    -- Subconsulta: Calcula la suma de lluvia para CADA mes
                    SELECT
                    SUM(cantidad) AS total_lluvia
                    FROM
                    lluvias
                    GROUP BY
                    MONTH(fecha)
                    ) AS LluviasMensual;";

        $stmt = $this->db->prepare($query);

        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC)['max_lluvia'] ?? false;
    }

    public function getMaxLluviaPorMes()
    {
        $query = "SELECT
                DATE(l1.fecha) AS dia_lluvioso,
                l1.cantidad
                FROM
                lluvias l1
                INNER JOIN (
                    -- Subconsulta: Encuentra la MÁXIMA cantidad de lluvia por día para CADA mes
                    SELECT
                        MONTH(fecha) AS mes,
                        MAX(cantidad) AS maxima_lluvia_diaria
                    FROM
                        lluvias
                    GROUP BY
                        mes
                ) AS MaximosPorMes
                ON
                    -- Condición 1: Une los registros por el mismo mes
                    MONTH(l1.fecha) = MaximosPorMes.mes
                    AND
                    -- Condición 2: Filtra solo aquellos registros cuya cantidad coincide con la máxima de su mes
                    l1.cantidad = MaximosPorMes.maxima_lluvia_diaria
                ORDER BY
                    dia_lluvioso;";

        $stmt = $this->db->prepare($query);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?? false;
    }
}
