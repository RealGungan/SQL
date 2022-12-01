<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <h2>Compra de Productos</h2>
    <form method='post' action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <?php
        include("functions.php");
        $conn = connection();
        $products = getNamesOfProduct($conn);
        $dnies = getDnies($conn);
        $productos = getNamesOfProduct($conn);
        ?>
        <label for="name">DNI Clientes</label>
        <select name="dnies">
            <?php foreach ($dnies as $dnie => $value) : ?>
                <option value="<?php echo $value['NIF'] ?>"> <?php echo $value['NIF'] ?> </option>
            <?php endforeach; ?>
        </select>
        <br /><br />
        <label for="name">Producto</label>
        <select name="products">
            <?php foreach ($products as $product => $value) : ?>
                <option value="<?php echo $value['ID_PRODUCTO'] ?>"> <?php echo $value['NOMBRE'] ?> </option>
            <?php endforeach; ?>
        </select>
        <br /><br />
        <label for="name">Cantidad</label>
        <input type="text" name="cantidad" id="cantidad" value="">
        <br /><br />
        <input type="submit" name="submit" id="submit" value="Comprar">
    </form>
</body>

</html>
<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //$conn = connection();
    if (isset($_POST["submit"])) {
        $nif = $_POST['dnies'];
        $producto = $_POST['products'];
        $cantidad = $_POST['cantidad'];

        $valido = isAvailable($conn, $producto, $cantidad);
        if ($valido == false) {
            echo "No es posible realizar la compra </br>";
        } else {
            buyProduct($conn, $nif, $producto, $cantidad);
        }
    } else {
        echo "Por favor, introduzca y elija valores correcto </br>";
    }
}
?>