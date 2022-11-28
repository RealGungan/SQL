<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CAMBIO DEPARTAMENTO</title>
</head>

<body>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <h2>Consulta Cambio Departamento/h2>
            <legend>Elige DNI</legend>
            <?php
            $dptoActual = "";
            require_once("funciones.php");
            // require_once("funcionesCambio.php");
            $conn = connection();
            $departamentos = obtenerDepartamentos($conn);
            $dnis = obtenerDnis($conn);
            //$departamentoActual = obtenerDptoActual($conn);
            ?>
            <div>
                <label for="dni">DNI:</label>
                <select name="dnies">
                    <?php foreach ($dnis as $dni => $value) : ?>
                        <option> <?php echo $value['DNI'] ?> </option>
                    <?php endforeach; ?>
                </select>
            </div>
            </BR>
            <div><input type="submit" name="mostrarActual" value="Mostrar Departamento Actual"></div>
            <legend>Departamento Actual</legend><br>
            <?php
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if (isset($_POST["mostrarActual"])) {
                    $dptoActual = obtenerDptoActual($conn);
                    echo $dptoActual;
                }
            }
            ?>
            <input type="text" name="deptoActual" value="" readonly>
            <br>
            <div>
                <label for="dni">DEPARTAMENTOS:</label>
                <select name="departamentos">
                    <?php foreach ($departamentos as $departamento => $value) : ?>
                        <option> <?php echo $value['NOMBRE'] ?> </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <legend>Fecha De Cambio</legend><br>
            <input type="text" name="fecha"><br>
            <input type="submit" name="cambiar" value="CAMBIAR">
            <?php
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if (isset($_POST["cambiar"])) {
                    actualizarDptoAnterior($conn);
                    crearCambioDepartamento($conn);
                }
            }
            ?>
    </form>
</body>