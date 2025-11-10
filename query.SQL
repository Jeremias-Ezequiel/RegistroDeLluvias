CREATE DATABASE lluvias;

USE lluvias;

CREATE TABLE lluvias(
fecha_ID DATE PRIMARY KEY ,
cantidad INT(3) NOT NULL CHECK (cantidad BETWEEN 0 AND 400),
CHECK(fecha_ID >= "2025-01-01" AND fecha_ID <= "2025-12-31")
);

-- Obtener meses y cantidades totales por mes

SELECT
    MONTH(fecha_ID) AS mes,
    SUM(cantidad) AS total_cantidad
FROM lluvias
GROUP BY MONTH(fecha)
ORDER BY mes;


-- Obtenemos solamente el valor máximo en cuanto a la cantidad de lluvia por mes
SELECT
    MAX(total_lluvia) AS max_lluvia
FROM (
    -- Subconsulta: Calcula la suma de lluvia para CADA mes
    SELECT
        SUM(cantidad) AS total_lluvia
    FROM
        lluvias
    GROUP BY
        MONTH(fecha)
) AS LluviasMensual;

-- UH - 003
SELECT
    DATE(l1.fecha_ID) AS dia_lluvioso,
    l1.cantidad
FROM
    lluvias l1
INNER JOIN (
    -- Subconsulta: Encuentra la MÁXIMA cantidad de lluvia por día para CADA mes
    SELECT
        MONTH(fecha_ID) AS mes,
        MAX(cantidad) AS maxima_lluvia_diaria
    FROM
        lluvias
    GROUP BY
        mes
) AS MaximosPorMes
ON
    -- Condición 1: Une los registros por el mismo mes
    MONTH(l1.fecha_ID) = MaximosPorMes.mes
    AND
    -- Condición 2: Filtra solo aquellos registros cuya cantidad coincide con la máxima de su mes
    l1.cantidad = MaximosPorMes.maxima_lluvia_diaria
ORDER BY
    dia_lluvioso;