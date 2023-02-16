<?php
if (isset($_COOKIE['generic'])) {
    include('../includes.php');

    $conn = connection();
    $products = getProducts($conn);

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['add']) && $_POST['quantity'] > 0  && is_numeric($_POST['quantity'])) {
            if (!isset($_COOKIE['cart'])) {
                $valid = checkQuantity($conn, $_POST['products'], $_POST['quantity']);

                if ($valid)
                    generateCartCookie($_POST['products'], $_POST['quantity']);
            } else
                addProductCookie($_POST['products'], $_POST['quantity']);
        } else if (isset($_POST['purchase']) && isset($_COOKIE['cart'])) {
            if (testPaymentInfo($_POST['payment_info'])) {
                $total_price = getTotalPrice($conn);

                addOrder($conn, $_COOKIE['generic']);
                purchase($conn);

                if (performPayment($conn, $_POST['payment_info'], $total_price)) {
                    if (isset($_POST['visa']))
                        echo "<script>window.open('../php/formVISA.php', '_self')</script>";
                    else if (isset($_POST['bizum']))
                        echo "<script>window.open('../php/formBizum.php', '_self')</script>";
                    else if (isset($_POST['paypal']))
                        echo "<script>window.open('../php/formPayPal.php', '_self')</script>";
                    else
                        echo '<br>Selecciona un método de pago';
                }
            } else if (isset($_COOKIE['cart']))
                echo '<br/> Introduce la información de pago correcta';
        } else
            echo '<br/>Añada al carrito antes de ralizar la compra';
        if (isset($_POST['logout'])) {
            logOut();
            echo "<script>window.open('../php/pe_inicio.php', '_self')</script>";
        }
    }
} else
    echo "<script>window.open('../php/pe_login.php', '_self')</script>";
?>

<!DOCTYPE html>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
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
                <option value="<?php echo $value['productCode'] ?>"> <?php echo $value['productName'] ?> </option>
            <?php endforeach; ?>
        </select>
        <br><br>
        <label for="quantity">Cantidad</label>
        <input type="text" name="quantity" id="quantity">
        <br /><br />
        <label for="payment_info">Información de pago</label>
        <input type="text" name="payment_info" id="payment_info">
        <br /><br />
        <input type="submit" name="add" id="add" value="Añadir producto">
        <input type="submit" name="purchase" id="purchase" value="Realizar perdido">
        <input type="submit" name="logout" id="logout" value="Log out">
        <br><br>
        <input type="checkbox" name="visa" id="visa" value="VISA">VISA</input>
        <input type="checkbox" name="bizum" id="bizum" value="Bizum">Bizum</input>
        <input type="checkbox" name="paypal" id="paypal" value="PayPal">PayPal</input>
    </form>
</body>

</html>

<script>
    $('input[type="checkbox"]').on('change', function() {
        $('input[type="checkbox"]').not(this).prop('checked', false);
    });
</script>