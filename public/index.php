<?php
// Obtenemos la fecha del dia del dia anterior
date_default_timezone_set("America/Argentina/Buenos_Aires");
$diaAnterior = date("Y-m-d", strtotime("-1 day"));

// Requerimos el archivo
require_once "../app/controllers/LluviaController.php";
$lluviaController = new LluviasController();

$accion = $_GET['accion'] ?? null;
$mensaje = null;
$resultado = [];
$seccion = $_GET['seccion'] ?? 'inicio';

$secuenciaDias = $_GET['secuenciaDias'] ?? 2;

if ($accion == "guardar") {
    $mensaje = $lluviaController->guardarLluvia();
}

if ($accion == "update") {
    $mensaje = $lluviaController->updateLluvia($_POST['fecha'], $_POST['cantidad']);
}

// Obtener luego de actualizar o ingresar un dato
$lluviasTotal = $lluviaController->obtenerMeses();

const MESES = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
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
            <a class="navbar-brand" href="?seccion=consultar">Consultar</a>
        </div>
    </nav>

    <main class="container mt-4">
        <?php if (isset($seccion) && $seccion == "inicio"): ?>
            <h1 class="mb-3">Registro de Lluvias por Mes</h1>
            <div class="row">
                <div class="col-md-8">
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Gráfico de Lluvias</h5>
                            <canvas id="miGraficoDeLluvias"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-5">
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
                                <div class="d-flex justify-content-around mb-3 gap-1">
                                    <button type="button" class="btn btn-danger">Cancelar</button>
                                    <button type="submit" class="btn btn-success">Registrar</button>
                                </div>
                            </form>
                            <?php
                            if (isset($mensaje)) {
                            ?>
                                <div class="alert alert-info"><?= $mensaje['message'] ?></div>
                            <?php
                            }

                            if (isset($mensaje) && $mensaje['status'] === 'update') {
                            ?>
                                <form action="?accion=update" method="post">
                                    <h5 class="text-center">¿Desea actualizar los valores?</h5>
                                    <input type="hidden" name="fecha" value="<?= $_POST['fecha'] ?>">
                                    <input type="hidden" name="cantidad" value="<?= $_POST['cantidad'] ?>">
                                    <div class="d-flex justify-content-around mb-3 gap-1">
                                        <a href="?" class="btn btn-danger">Cancelar</a>
                                        <button class="btn btn-success" type="submit">Actualizar</button>
                                    </div>
                                </form>
                            <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php elseif (isset($seccion) && $seccion == "consultar"): ?>
            <div class="row mb-3">
                <div class="col-md-6 mb-5">
                    <h3>Cantidad de Lluvia por Mes</h3>
                    <table class="table table-success table-striped-columns">
                        <thead>
                            <tr>
                                <th>Mes</th>
                                <th>Cantidad (mm)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $max_lluvia = $lluviaController->getTotalLluviaPorMes();
                            $meses_max = [];

                            foreach ($lluviasTotal['mes'] as $index => $fecha) {
                                $cantidad = $lluviasTotal['cantidad'][$index];
                                if ($cantidad === $max_lluvia) {
                                    $meses_max[] = MESES[$fecha - 1];
                                }

                                $fecha = MESES[$fecha - 1];
                            ?>
                                <tr>
                                    <td><?= $fecha ?></td>
                                    <td><?= $cantidad ?></td>
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>

                    <h3>Los meses más lluviosos son:</h3>
                    <ul class="list-group">
                        <?php
                        foreach ($meses_max as $meses) {
                        ?>
                            <li class="list-group-item"><?= $meses ?></li>
                        <?php
                        }
                        ?>
                    </ul>
                </div>
                <div class="col-md-6">
                    <h3>Máxima cantidad de lluvia por mes</h3>
                    <table class="table table-primary table-striped-columns">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Cantidad (mm)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $lluviasDias = $lluviaController->getMaxLluviaPorMes();
                            foreach ($lluviasDias as $lluvias) {
                            ?>
                                <tr>
                                    <th><?= $lluvias['dia_lluvioso'] ?></th>
                                    <th><?= $lluvias['cantidad'] ?> </th>
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row text-center">
                <form action="" method="get" autocomplete="off">
                    <input type="hidden" name="seccion" value="consultar">
                    <h3><?= $secuenciaDias ?> dias seguidos de lluvia</h3>
                    <label for=" secuenciaDias"></label>
                    <input type="number" name="secuenciaDias" id="secuenciaDias" required min="1">
                    <button type="submit" class="btn btn-primary">Filtrar</button>
                </form>

                <?php
                $lluviasFiltro = $lluviaController->getSecuence($secuenciaDias);

                if (isset($lluviasFiltro['status']) && $lluviasFiltro['status'] === "error") {
                ?>
                    <div class="alert alert-danger my-3"><?= $lluviasFiltro['message'] ?></div>
                <?php
                } else {
                ?>
                    <table class="table table-striped my-3">
                        <thead>
                            <tr>
                                <th>Dia</th>
                                <th>Cantidad (mm)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($lluviasFiltro as $lluvia) {
                            ?>
                                <tr>
                                    <th><?= $lluvia->getFecha() ?></th>
                                    <th><?= $lluvia->getCantidad() ?></th>
                                </tr>
                        <?php
                            }
                        }
                        ?>
                        </tbody>
                    </table>
            </div>
        <?php endif; ?>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <?php if (isset($resultado)): ?>
        <script>
            const lluviasResult = <?= json_encode($lluviasTotal) ?>;
        </script>
    <?php endif; ?>

    <script src="assets/js/main.js"></script>

    <!-- Sweet Alert JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>

</html>