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
        <h2>Consulta Listado de Empleados por Departamento</h2>
            <?php
            $dptoActual = "";
            require_once("funciones.php");
            
            $conn = connection();
            $departamentos = obtenerDepartamentos($conn);
            ?>
            </BR>
            <legend>Seleccionar Departamento</legend><br>
            <div>
                <select name="departamentos">
                    <?php foreach ($departamentos as $departamento => $value) : ?>
                        <option> <?php echo $value['NOMBRE'] ?> </option>
                    <?php endforeach; ?>
                </select>
            </div>
            </BR>
            <div><input type="submit" name="mostrarlista" value="Mostrar Listado Empleados"></div>
            </BR>
            <?php
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if (isset($_POST["mostrarlista"])) {
                    mostrarEmpleadoPorDepartamento($conn);
                                      
                }
            }
            ?>
         
    </form>
</body>