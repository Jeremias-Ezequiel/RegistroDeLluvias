<?php

class Lluvia
{
    private $fecha;
    private $cantidad;

    const CANTIDAD_MIN = 0;
    const CANTIDAD_MAX = 400;

    public function __construct($data = [])
    {
        if (!empty($data)) {
            $this->hydrate($data);
        }
    }

    private function hydrate($data)
    {
        $this->fecha = $data['fecha'] ?? null;
        $this->cantidad = $data['cantidad'] ?? null;
    }

    public function getFecha()
    {
        return $this->fecha;
    }

    public function getCantidad()
    {
        return $this->cantidad;
    }

    public function setFecha($fecha)
    {
        $this->fecha = $fecha;
    }

    public function setCantidad($cantidad)
    {
        $this->cantidad = $cantidad;
    }

    public function validar()
    {
        $errores = [];

        if (empty($this->fecha)) {
            $errores['fecha'] = "La fecha es requerida";
        }

        if ($this->cantidad === null || $this->cantidad === '') {
            $errores['cantidad'] = "La cantidad es requerida";
        } elseif ($this->cantidad < self::CANTIDAD_MIN) {
            $errores['cantidad'] = "La cantidad no puede ser menor a " . self::CANTIDAD_MIN;
        } elseif ($this->cantidad > self::CANTIDAD_MAX) {
            $errores['cantidad'] = "La cantidad no puede ser mayor a " . self::CANTIDAD_MAX;
        }

        return $errores;
    }
}
