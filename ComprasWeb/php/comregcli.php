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
        <label for="name">NOMBRE</label>
        <input type="text" name="name" id="name">
        <br><br>
        <label for="apellido">Apellido</label>
        <input type="text" name="apellido" id="apellido">
        <br><br>
        <label for="dir">Dirección</label>
        <input type="text" name="dir" id="dir">
        <br><br>
        <label for="city">Ciudad</label>
        <input type="text" name="city" id="city">
        <br><br>
        <label for="usrname">Nombre</label>
        <input type="text" name="usrname" id="usrname">
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

        $nif = test_input($_POST['dni']);
        $nombre = test_input($_POST['name']);
        $apellido = test_input($_POST['apellido']);
        $dni = test_input($_POST['dni']);
        $dir = test_input($_POST['dir']);
        $city = test_input($_POST['city']);
        $valid = isValidDni($nif);
        $usrname = test_input($_POST['usrname']);

        if ($valid == false) {
            echo "No se puede dar de alta al cliente </br>";
        } else {
            addClient($conn, $nif, $nombre, $apellido, $dni, $dir, $city, $usrname);

            $link = "<script>window.open('comlogincli.php')</script>";
            echo $link;
        }
    } else {
        echo "Por favor introduza un valores correctos";
    }
}
?>