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
        //$products = getNamesOfProduct($conn);
        ?>
        <label for="name">DNI Clientes</label>
        <select name="products">
            <?php foreach ($clients as $client => $value) : ?>
                <option value="<?php echo $value['NIF'] ?>"> <?php echo $value['NIF'] ?> </option>
            <?php endforeach; ?>
        </select>
        <br /><br />
        <input type="text" name="date1" id="date1" value="Fecha Inicio">
        <br /><br />
        <input type="text" name="date2" id="date2" value="Fecha Fin">
        <br /><br />
        <input type="submit" name="submit" id="submit" value="Dar de alta">
    </form>
</body>

</html>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST["submit"])) {
    }
}
?>