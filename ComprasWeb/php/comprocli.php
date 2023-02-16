<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <h2>Registrar Usuario</h2>
    <form method='post' action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <label for="dni">DNI</label>
        <input type="text" name="dni" id="dni">
        <br><br>
        <label for="dni">NOMBRE</label>
        <input type="text" name="name" id="name">
        <br><br>
        <label for="dni">Apellido</label>
        <input type="text" name="apellido" id="apellido">
        <br><br>
        <label for="dni">CP</label>
        <input type="text" name="cp" id="cp">
        <br><br>
        <label for="dni">Direccion</label>
        <input type="text" name="direc" id="direc">
        <br><br>
        <label for="dni">Ciudad</label>
        <input type="text" name="ciu" id="ciu">
        <br><br>
        <label for=" usrname">Nombre</label>
        <input type="text" name="usrname" id="usrname">
        <br><br>
        <label for=" password">Contraseña</label>
        <input type="text" name="password" id="password">
        <br><br>
        <input type="submit" name="submit" id="submit" value="Registrar usuario">
    </form>
</body>

</html>


<?php
include '../includes.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (isset($_POST["submit"])) {
        $conn = connection();
    } else {
        echo "Por favor introduza un valores correctos";
    }
}
?>