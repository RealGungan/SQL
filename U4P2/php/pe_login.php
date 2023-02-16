<?php
include '../includes.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $connection = connection();

    if ($_POST["usrname"] != '' && $_POST["password"]) {
        if (!login($connection, $_POST["usrname"], $_POST["password"]))
            echo "Por favor, introduza un valor correcto";
    }
}
?>

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