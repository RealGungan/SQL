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
        <h2>Modificar Salario</h2>
            
            <?php
            $dptoActual = "";
            require_once("funciones.php");
            $conn = connection();
            $dnis = obtenerDnis($conn);
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
            <div><input type="submit" name="mostrarActual" value="Mostrar Salario Actual"></div>
            <legend>Salario Actual</legend><br>
            <?php
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if (isset($_POST["mostrarActual"])) {
                    $salarioActual = obtenerSalarioActual($conn);
                    echo $salarioActual;
                }
            }
            ?>
            <!-- <input type="text" name="deptoActual" value="" readonly> -->
            <br>
            <legend>Salario Nuevo</legend><br>
            <input type="text" name="salario"><br>
            <input type="submit" name="cambiar" value="CAMBIAR">
            <?php
             if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if (isset($_POST["cambiar"])) {
                    modificarSalario($conn);
                }  
            }          
            ?>
    </form>
</body>