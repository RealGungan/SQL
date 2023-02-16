<?php
include('../includes.php');

$conn = connection();
$products = getProducts($conn);
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
    <h2>Realizar Pedidos</h2>

    <form method='post' action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <label for="name">Producto</label>
        <select name="products">
            <?php foreach ($products as $product => $value) : ?>
                <option value="<?php echo $value['productName'] ?>"> <?php echo $value['productName'] ?> </option>
            <?php endforeach; ?>
        </select>
        <br><br>
        <input type="submit" name="check" id="check" value="Consultar cantidad">
    </form>
</body>

</html>

<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['check'])) {
        $quantity = getStock($conn, $_POST['products']);

        printString('<br>Actualmente hay ' . $quantity[0][0] . ' ' . '<i>' . $_POST['products'] . '</i>');
    }
}
?>