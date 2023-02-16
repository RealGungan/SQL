<?php
include('../includes.php');

$conn = connection();
$customers = getCustomers($conn);
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
        <label for="name">Clientes</label>
        <select name="customer_code">
            <?php foreach ($customers as $customer_code) : ?>
                <option value="<?php echo $customer_code[0] ?>"> <?php echo $customer_code[0] ?> </option>
            <?php endforeach; ?>
        </select>
        <br><br>
        <input type="submit" name="check" id="check" value="Consultar perdido/s">
    </form>
</body>

</html>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['check'])) {
        $orders = getOrders($conn, $_POST['customer_code']);

        printTable($orders, ['Código Pedido', 'Fecha Pedido', 'Estado Pedido']);
    }
}
?>