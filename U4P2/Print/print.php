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

function printTable($table_data_array, $titles)
{
    echo '<br><table>';

    printTableTitles($titles);

    foreach ($table_data_array as $table_data) {
        echo '<tr>';

        for ($i = 0; $i < count($table_data); $i++) {
            echo '<td>' . $table_data[$i] . '</td>';
        }

        echo '</tr>';
    }
    echo '</table>';
}

function printTableWithKeys($table_data_array, $titles)
{
    echo '<br><table>';

    printTableTitles($titles);

    foreach ($table_data_array as $keys => $value) {
        echo '<tr>';

        echo '<td>' . $keys . '</td>';
        echo '<td>' . $value . '</td>';

        echo '</tr>';
    }
    echo '</table>';
}

function printTableWithArrays($table_data_array, $titles)
{
    echo '<br><table>';

    printTableTitles($titles);

    foreach ($table_data_array as $array) {
        foreach ($array as $table_data) {
            echo '<tr>';

            for ($i = 0; $i < count($table_data); $i++) {
                echo '<td>' . $table_data[$i] . '</td>';
            }

            echo '</tr>';
        }
    }
    echo '</table>';
}


function printTableTitles($titles)
{
    echo '<tr>';

    foreach ($titles as $title) {

        echo '<th>' . $title . '</th>';
    }

    echo '</tr>';
}

function printString($string)
{
    echo $string;
}
