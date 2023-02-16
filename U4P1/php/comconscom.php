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
        include("functions.php");
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
    </form>
</body>

</html>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST["submit"]) && !empty($_POST['date1']) && !empty($_POST['date2'])) {
        $result = getPurchaseInfo($conn, $_COOKIE['generic'], $_POST['date1'], $_POST['date2']);
        printPurchaseInfo($result);
    } else {
        echo "Por favor rellene y seleccione los campos necesarios <br>.";
    }
}
?>