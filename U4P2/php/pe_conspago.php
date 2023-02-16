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
    <h2>Comprobar compras</h2>

    <form method='post' action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <label for="name">Clientes</label>
        <select name="customer_code">
            <?php foreach ($customers as $customer_code) : ?>
                <option value="<?php echo $customer_code[0] ?>"> <?php echo $customer_code[0] ?> </option>
            <?php endforeach; ?>
        </select>
        <br><br>
        <label for="in_date">Initial date</label>
        <input type="text" name="in_date" id="in_date">
        <br><br>
        <label for="end_date">End date</label>
        <input type="text" name="end_date" id="end_date">
        <br><br>
        <input type="submit" name="check" id="check" value="Mostrar compras">
    </form>
</body>

</html>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['check'])) {
        $total = getSalesTwoDates($conn, $_POST['customer_code'], $_POST['in_date'], $_POST['end_date']);

        printTableWithArrays($total, ['Fecha Compra', 'Cantidad']);
    }
}
?>