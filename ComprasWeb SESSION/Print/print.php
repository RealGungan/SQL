<?php
function printThings(...$pirntables)
{
    for ($i = 0; $i < count($pirntables); $i++) {
        echo $pirntables[$i];
    }
}
function printPurchaseInfo($result)
{
    foreach ($result as $key => $value) {
        if (is_numeric($key)) {
            echo "Fecha de Compra: " . $value['FECHA_COMPRA'] . "</br>";
            echo "Nombre del producto: " . $value['NOMBRE'] . "</br>";
            echo 'Cantidad del producto: ' . $value['UNIDADES'] . '<br/>';
            echo "Precio del producto: " . (string)$value['PRECIO'] . "€</br>";
            echo 'Total precio ' . $value['NOMBRE'] . ' : ' . $value['PRECIO'] * $value['UNIDADES'] . '€' . '<br/><br/>';
        }
    }
    echo "</br>El total de sus compras asciende a: " . $result['total'] . " euros";
}

function printShoppingCart($cart)
{
    $cart = unserialize($cart);

    foreach ($cart as $html) {
        if ($html == 'table' || $html == 'tr' || $html == 'th' || $html == 'td') {
            echo '<' . $html . '>';
        } else {
            echo $html;
        }
    }
}
