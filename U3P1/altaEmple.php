<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ALTA EMPLEADO</title>
</head>

<body>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <h2>DAR DE ALTA EMPLEADO/A</h2>
        <legend>NOMBRE</legend>
        <input type="text" name="nombre" value=""><br>
        <legend>DNI</legend>
        <input type="text" name="dni" value=""><br>
        <legend>SALARIO</legend>
        <input type="text" name="salario" value=""><br>
        <legend>FECHA DE NACIMIENTO</legend>
        <input type="text" name="fecha_nac" value=""><br>
        <?php
        require_once("funciones.php");
        $conn = connection();
        $departamentos = obtenerDepartamentos($conn);
        $dnis = obtenerDnis($conn);
        ?>
        </BR>
        <legend>Selecciona departamento</legend>
        </BR>
        <div>
            <label for="dni">DEPARTAMENTOS:</label>
            <select name="departamentos">
                <?php foreach ($departamentos as $departamento => $value) : ?>
                    <option> <?php echo $value['NOMBRE'] ?> </option>
                <?php endforeach; ?>
            </select>
        </div>
        </BR>
        <div><input type="submit" name="alta" value="Dar de Alta"></div>
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST["alta"])) {
                darAltaEmpleado($conn);
                incluirEmpleadoEnTablaNN($conn);     
            }
        }
        ?>
    </form>
</body>