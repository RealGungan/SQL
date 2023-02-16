<?php
include_once('../includes.php');
include('../API_PHP/redsysHMAC256_API_PHP_7.0.0/apiRedsys.php');

$conn = connection();
$total_price = getTotalPrice($conn);
deleteCartCookie();
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
    <form name="form" action="https://sis-t.redsys.es:25443/sis/realizarPago" method="POST">
        <input type="hidden" name="Ds_SignatureVersion" value="HMAC_SHA256_V1" />
        <input type="hidden" name="Ds_MerchantParameters" value="<?php echo getMerchantParametersPayPal($conn, $total_price) ?>" />
        <input type="hidden" name="Ds_Signature" value="<?php echo getSignaturePayPal($conn, $total_price) ?>" />
    </form>
</body>

</html>

<script>
    window.onload = function() {
        document.forms['form'].submit();
    }
</script>