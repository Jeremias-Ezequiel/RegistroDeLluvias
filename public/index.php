<?php
// Obtenemos la fecha del dia del dia anterior
date_default_timezone_set("America/Argentina/Buenos_Aires");
$diaAnterior = date("Y-m-d", strtotime("-1 day"));

// Requerimos el archivo
require_once "../app/controllers/LluviaController.php";

$accion = $_GET['accion'] ?? null;
$mensaje = null;
$registros = [];

if ($accion == "guardar") {
    $mensaje = guardarLluvia();
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Lluvias</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <link href="assets/css/style.css" rel="stylesheet">
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="?">🌦️ RegistroDeLluvias</a>
            <a class="navbar-brand" href="#">Consultar</a>
        </div>
    </nav>

    <main class="container mt-4">
        <h1 class="mb-3">Registro de Lluvias</h1>
        <p>Aquí irá el contenido principal de tu página.</p>

        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Gráfico de Lluvias</h5>
                        <canvas id="miGraficoDeLluvias"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Registrar Lluvia</h5>

                        <form action="?accion=guardar" method="post" autocomplete="off">
                            <div class="mb-3">
                                <label for="formGroupExampleInput" class="form-label">Fecha: </label>
                                <input type="date" class="form-control" id="formGroupExampleInput" required min="2025-01-01" max="<?= $diaAnterior ?>" value="<?= $diaAnterior ?>" name="fecha">
                            </div>
                            <div class="mb-3">
                                <label for="formGroupExampleInput2" class="form-label">Cantidad de Lluvia (mm):</label>
                                <input type="number" class="form-control" id="formGroupExampleInput2" required min="0" value="0" max="400" name="cantidad" autofocus>
                            </div>
                            <div class="d-flex justify-content-around mb-3">
                                <button type="button" class="btn btn-danger">Cancelar</button>
                                <button type="submit" class="btn btn-success">Registrar</button>
                            </div>
                            <?php
                            if ($mensaje) {
                            ?>
                                <div class="alert alert-info"><?= $mensaje ?></div>
                            <?php
                            }
                            ?>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script src="assets/js/main.js"></script>
</body>

</html>