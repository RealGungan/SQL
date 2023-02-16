<?php
include('../includes.php');

$conn = connection();
$products_line = getProductLines($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        table,
        th,
        td {
            border: 1px solid black;
            text-align: center;
        }
    </style>
</head>

<body>
    <h2>Realizar Pedidos</h2>

    <form method='post' action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <label for="name">Línea de productos</label>
        <select name="products_line">
            <?php foreach ($products_line as $product => $value) : ?>
                <option value="<?php echo $value['productLine'] ?>"> <?php echo $value['productLine'] ?> </option>
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
        $lines_quantity = getStockProductLine($conn, $_POST['products_line']);

        printTable($lines_quantity, ['Product Name', 'Quantity']);
    }
}
?>