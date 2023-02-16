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
        $productos = getNamesOfProduct($conn);
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
        <input type="submit" name="buy" id="submit" value="Comprar">
        <input type="submit" name="add" id="add" value="Añadir">
    </form>
</body>

</html>
<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST["buy"])) {
        finalizePurchase();
    }

    if (isset($_POST["add"])) {
        $product = $_POST['products'];
        $quantity = $_POST['cantidad'];

        if (!isset($_COOKIE['purchase'])) {
            isAvailable($conn, $product, $quantity);
            storePurchase($product, $quantity);
        } else {
            isAvailable($conn, $product, $quantity);
            addPurchaseToCookie($product, $quantity);
        }
    }
}
?>