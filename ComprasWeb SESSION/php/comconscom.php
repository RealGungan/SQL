<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <h2>Consulta de Comrpas</h2>
    <form method='post' action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <?php
        include '../includes.php';

        $conn = connection();
        $clients = getDnies($conn);
        ?>
        <h3>Hola
            <?php

            foreach ($_COOKIE as $key => $val) {
                if ($key == 'generic') {
                    getNameViaDNI($conn, $val);
                }
            }
            ?>
        </h3>
        <label for="">Desde</label>
        <input type="text" name="date1" id="date1">
        <br /><br />
        <label for="">Hasta</label>
        <input type="text" name="date2" id="date2">
        <br /><br />
        <input type="submit" name="submit" id="submit">
        <input type="submit" name="log_out" id="log_out" value="Log out">
    </form>
</body>

</html>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST["submit"]) && !empty($_POST['date1']) && !empty($_POST['date2'])) {
        session_start();

        $result = getPurchaseInfo($conn, $_SESSION['generic'], $_POST['date1'], $_POST['date2']);
        printPurchaseInfo($result);
    } else {
        echo "Por favor rellene y seleccione los campos necesarios <br>.";
    }

    if (isset($_POST['log_out'])) {
        logOut();
    }
}
?>