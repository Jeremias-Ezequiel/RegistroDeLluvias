<?php

class Lluvias
{
    private $fecha;
    private $cantidad;

    public function __construct($fecha, $cantidad)
    {
        $this->fecha = $this->setFecha($fecha);
        $this->cantidad = $this->setCantidad($cantidad);
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
        if ($cantidad < 0 || $cantidad > 400) {
            return "La cantidad no puede ser menor a 0 o mayor a 400 mm";
        }

        $this->cantidad = $cantidad;
    }
}
