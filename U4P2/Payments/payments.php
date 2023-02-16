<?php
function getTotalPrice($conn)
{
    $cart = unserialize($_COOKIE['cart']);
    $total_price = 0;

    foreach ($cart as $product => $quantity) {
        try {
            $sql = $conn->prepare("SELECT buyPrice from products WHERE productCode = :product_code");

            $sql->bindParam('product_code', $product);

            $sql->execute();

            $price = $sql->fetchAll();
            $price = $price[0][0];

            $total_price += $price * $quantity;
        } catch (PDOException $e) {
            echo '<br /> ERROR: ' . $e->getMessage();
        }
    }

    return $total_price;
}

function performPayment($conn, $check_number, $total_price)
{
    try {
        $date = new DateTime();
        $string_date = $date->format("Y-m-d H:i:s");

        $sql = $conn->prepare("INSERT INTO payments VALUES(:customer_number, :check_number, :string_date, :amount)");

        $sql->bindParam('customer_number', $_COOKIE['generic']);
        $sql->bindParam('check_number', $check_number);
        $sql->bindParam('string_date', $string_date);
        $sql->bindParam('amount', $total_price);

        $sql->execute();

        return true;
    } catch (PDOException $e) {
        echo '<br /> ERROR: ' . $e->getMessage();
    }
}

// ------------------ REDSYS ------------------

function createRedsysObj($conn, $total_price, $terminal_number, $payment_type)
{
    // get last order number
    try {
        $sql = $conn->prepare("SELECT orderNumber from orders ORDER BY orderNumber DESC LIMIT 1");

        $sql->execute();

        $sql->setFetchMode(PDO::FETCH_NUM);
        $order_number = $sql->fetchAll();
        $order_number = $order_number[0][0];
    } catch (PDOException $e) {
        echo '<br /> ERROR: ' . $e->getMessage();
    }

    $redsysData = new RedsysAPI;

    // Valores de entrada que no hemos cmbiado para ningun ejemplo
    $fuc = "999008881";
    $terminal = $terminal_number;
    $moneda = "978";
    $trans = "0";
    $urlOKKO = "http://localhost:3000/SQL/U4P2/php/pe_inicio.php";
    $id = $order_number;
    if ($payment_type == 'bizum') {
        $amount = '900';
        $redsysData->setParameter("DS_MERCHANT_PAYMETHODS", 'z');
    } else
        $amount = $total_price;


    // Se Rellenan los campos
    $redsysData->setParameter("DS_MERCHANT_AMOUNT", $amount);
    $redsysData->setParameter("DS_MERCHANT_ORDER", $id);
    $redsysData->setParameter("DS_MERCHANT_MERCHANTCODE", $fuc);
    $redsysData->setParameter("DS_MERCHANT_CURRENCY", $moneda);
    $redsysData->setParameter("DS_MERCHANT_TRANSACTIONTYPE", $trans);
    $redsysData->setParameter("DS_MERCHANT_TERMINAL", $terminal);
    $redsysData->setParameter("DS_MERCHANT_URLOK", $urlOKKO);
    $redsysData->setParameter("DS_MERCHANT_URLKO", $urlOKKO);

    if ($payment_type == 'paypal') {
        $redsysData->setParameter("DS_MERCHANT_PAYMETHODS", 'p');
        $redsysData->setParameter("PayPal_PayerId", 'SHZWS4KWMSX96');
        $redsysData->setParameter("PayPal_PayerMail", 'danielfuentesstw@gmail.com');
        $redsysData->setParameter("PayPal_Token", '1');
        $redsysData->setParameter("PayPal_TransactionId", '1000');
        $redsysData->setParameter("PayPal_CountryCode", 'ES');
        $redsysData->setParameter("PayPal_PaymentStatus", '');
    }

    return $redsysData;
}

// ------------------ VISA ------------------

function getMerchantParametersVISA($conn, $total_price)
{
    $total_price = str_replace('.', '', getTotalPrice($conn));

    $readsys_data = createRedsysObj($conn, $total_price, '1', '');

    return $params = $readsys_data->createMerchantParameters();
}

function getSignatureVISA($conn, $total_price)
{
    $total_price = str_replace('.', '', getTotalPrice($conn));

    $readsys_data =
        createRedsysObj($conn, $total_price, '1', '');


    $SHA256_code = 'sq7HjrUOBfKmC576ILgskD5srU870gJ7';

    return $signature = $readsys_data->createMerchantSignature($SHA256_code);
}

// ------------------------------------

// ------------------ BIZUM ------------------

function getMerchantParametersBizum($conn, $total_price)
{
    $total_price = str_replace('.', '', getTotalPrice($conn));

    $readsys_data = createRedsysObj($conn, $total_price, '7', 'bizum');

    return $params = $readsys_data->createMerchantParameters();
}

function getSignatureBizum($conn, $total_price)
{
    $total_price = str_replace('.', '', getTotalPrice($conn));

    $readsys_data = createRedsysObj($conn, $total_price, '7', 'bizum');

    $SHA256_code = 'sq7HjrUOBfKmC576ILgskD5srU870gJ7';


    return $signature = $readsys_data->createMerchantSignature($SHA256_code);
}

// ------------------------------------

// ------------------ PAYPAL ------------------

function getMerchantParametersPayPal($conn, $total_price)
{
    $total_price = str_replace('.', '', getTotalPrice($conn));

    $readsys_data = createRedsysObj($conn, $total_price, '7', 'paypal');


    return $params = $readsys_data->createMerchantParameters();
}

function getSignaturePayPal($conn, $total_price)
{
    $total_price = str_replace('.', '', getTotalPrice($conn));

    $readsys_data = createRedsysObj($conn, $total_price, '7', 'paypal');


    $SHA256_code = 'sq7HjrUOBfKmC576ILgskD5srU870gJ7';

    return $signature = $readsys_data->createMerchantSignature($SHA256_code);
}

//------------------------------------

// ------------------------------------
