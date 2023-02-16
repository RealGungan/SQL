<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <h2>Iniciar sesión</h2>
    <form method='post' action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <label for=" usrname">Nombre</label>
        <input type="text" name="usrname" id="usrname">
        <br><br>
        <label for=" password">Contraseña</label>
        <input type="text" name="password" id="password">
        <br><br>
        <input type="submit" name="submit" id="submit" value="Iniciar sesión">
    </form>
</body>

</html>


<?php
include '../includes.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (isset($_POST["submit"])) {
        $conn = connection();

        $password = test_input($_POST['password']);
        $usrname = test_input($_POST['usrname']);

        userExists($conn, $usrname, $password);
    } else {
        echo "Por favor introduza un valores correctos";
    }
}
?>