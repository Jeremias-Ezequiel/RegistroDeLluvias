<?php

require_once __DIR__ . "/../config/Database.php";
require_once __DIR__ . "/../repositories/LluviaRepository.php";
require_once __DIR__ . "/../models/Lluvia.php";

class LluviasController
{
    private $lluviaRepository;

    public function __construct()
    {
        $this->lluviaRepository = new LluviaRepository();
    }

    function guardarLluvia()
    {
        if ($_SERVER['REQUEST_METHOD'] === "POST") {
            $lluvia = new Lluvia();
            $lluvia->setFecha($_POST['fecha']);
            $lluvia->setCantidad($_POST['cantidad']);

            $errores = $lluvia->validar();

            if (!empty($errores)) {
                return ["status" => "error", "message" => $errores];
            }

            if ($this->lluviaRepository->findByFecha($lluvia->getFecha())) {
                return ["status" => "update", "message" => "Desea actualizar el valor del dia {$lluvia->getFecha()}"];
            }

            try {
                $fecha = $this->lluviaRepository->guardar($lluvia);

                if ($fecha) {
                    return ["status" => "success", "message" => "Se ha registrado correctamente la lluvia en el dia " . $_POST['fecha'] . " con la cantidad de " . $_POST['cantidad']];
                } else {
                    return ["status" => "error", "message" => "No se ha podido registrar"];
                }
            } catch (PDOException $e) {
                return ["status" => "error", "message" => "Error en el servidor: " . $e->getMessage()];
            }
        }
    }

    function updateLluvia($fecha, $cantidad)
    {
        $lluvia = new Lluvia();
        $lluvia->setFecha($fecha);
        $lluvia->setCantidad($cantidad);

        $errores = $lluvia->validar();

        if (!empty($errores)) {
            return ["status" => "error", "message" => $errores];
        }

        if ($this->lluviaRepository->update($lluvia)) {
            return ["status" => "success", "message" => "Se ha actualizado con éxito el dia {$lluvia->getFecha()} con el valor de: {$lluvia->getCantidad()}"];
        } else {
            return ["status" => "error", "message" => "No se ha podido actualizar"];
        }
    }

    function obtenerMeses()
    {
        try {
            return $this->lluviaRepository->getMeses();
        } catch (PDOException $e) {
            return ["status" => "error", "message" => "Hubo un error en el servidor: {$e->getMessage()}"];
        }
    }

    function getTotalLluviaPorMes()
    {
        if ($res = $this->lluviaRepository->getTotalLluviaPorMes()) {
            return $res;
        } else {
            return ["status" => "error", "message" => "Hubo un error al obtener el total de lluvia por mes"];
        }
    }

    function getMaxLluviaPorMes()
    {
        try {
            if ($res = $this->lluviaRepository->getMaxLluviaPorMes()) {
                return $res;
            } else {
                return ["status" => "error", "message" => "Hubo un error al obtener los máximos de lluvia por mes"];
            }
        } catch (PDOException $e) {
            return "Hubo un error en el servidor: " . $e->getMessage();
        }
    }

    function getSecuence()
    {
        $lluvias = $this->lluviaRepository->getAll();
        return $this->lluviaRepository->getSecuenceByNumber($lluvias, 4);
    }
}
