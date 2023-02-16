<?php
function buyProduct($conn, $dni, $product, $quantity)
{
    $valido = true;

    try {
        test_input($quantity);

        $fecha = new DateTime();
        $stringFecha = $fecha->format("Y-m-d");

        $sql = $conn->prepare("SELECT compra.nif, compra.id_producto, compra.fecha_compra FROM compra where compra.nif = :nif and compra.id_producto = :id_producto and compra.fecha_compra = :fecha");

        $sql->bindParam('nif', $dni);
        $sql->bindParam('id_producto', $product);
        $sql->bindParam('fecha', $stringFecha);

        $sql->execute();

        $exists = $sql->rowCount();
    } catch (PDOException $e) {
        echo 'ERRROR: ' . $e;
    }

    if ($exists = 1) {
        try {
            test_input($quantity);
            $fecha = new DateTime();
            $stringFecha = $fecha->format("Y-m-d");

            $sql = $conn->prepare("INSERT INTO COMPRA (NIF,ID_PRODUCTO,FECHA_COMPRA,UNIDADES) VALUES (:nif,:id_producto,:fecha,:unidades)");

            $sql->bindParam('nif', $dni);
            $sql->bindParam('id_producto', $product);
            $sql->bindParam('fecha', $stringFecha);
            $sql->bindParam('unidades', $quantity);

            $sql->execute();
        } catch (PDOException $e) {
            $valido = false;
            $error = $e->getCode();

            echo 'ERRROR: ' . $e;
        }

        return $valido;
    } else {
        addPurchase($conn, $dni, $product, $quantity);
    }
}
function getPurchaseInfo($conn, $dni, $date1, $date2)
{
    try {
        $sql = $conn->prepare("SELECT PRODUCTO.NOMBRE,PRODUCTO.PRECIO,COMPRA.FECHA_COMPRA,COMPRA.UNIDADES FROM PRODUCTO,COMPRA WHERE
        PRODUCTO.ID_PRODUCTO=COMPRA.ID_PRODUCTO AND COMPRA.NIF=:dni AND COMPRA.FECHA_COMPRA between :date1 AND :date2");

        $sql->bindParam('dni', $dni);
        $sql->bindParam('date1', $date1);
        $sql->bindParam('date2', $date2);

        $sql->execute();

        $sql->setFetchMode(PDO::FETCH_ASSOC);

        $result = $sql->fetchAll();

        $total = 0.0;

        foreach ($result as $key => $value) {
            $total += floatval($value['PRECIO']) * floatval($value['UNIDADES']);
        }

        $result['total'] = $total;

        return $result;
    } catch (PDOException $e) {
        $error = $e->getCode();
    }
}
function addPurchase($conn, $dni, $product, $quantity)
{
    try {
        test_input($quantity);

        $fecha = new DateTime();
        $stringFecha = $fecha->format("Y-m-d");

        $sql = $conn->prepare("UPDATE COMPRA SET compra.unidades = compra.unidades + :unidades WHERE compra.nif = :nif and compra.fecha_compra = :fecha and compra.id_producto = :id_producto");

        $sql->bindParam('nif', $dni);
        $sql->bindParam('id_producto', $product);
        $sql->bindParam('fecha', $stringFecha);
        $sql->bindParam('unidades', $quantity);

        $sql->execute();

        echo "</br>Se ha realizado su compra satisfactoriamente</br>";
    } catch (PDOException $e) {
        echo 'ERROR: ' . $e;
    }
}

// function checkCartCookie($product, $quantity, $startTable, $endTable, $tableTitle)
// {
//     if (!isset($_COOKIE['cart'])) {
//         $cart = shoppingCartCookie($product, $quantity, $startTable, $endTable, $tableTitle, ['']);
//         setcookie('cart', $cart, time() + (86400 * 30), "/");
//     } else {
//         $cart = unserialize($_COOKIE['cart']);
//         shoppingCartCookie($product, $quantity, $startTable, $endTable, $tableTitle, $cart);
//         checkIfProductExistsInShoppinCartCookie($cart, $product);
//     }
// }

// function shoppingCartCookie($product, $quantity, $startTable, $endTable, $tableTitle, $cart)
// {
//     $cart = deleteIndex0CartCookie($cart);

//     if ($startTable) {
//         $cart[] = '<br/><br/><h3>Cesta de la compra</h3><table>';
//     } else if ($endTable) {
//         $cart[] = '</table>';
//     }
//     if (!empty($tableTitle)) {
//         $cart[] = '<tr>';
//         foreach ($tableTitle as $title) {
//             $cart[] = '<th>' . $title . '</th>';
//         }
//     }
//     if ($product != '' && $quantity != '') {
//         $cart[] = '<tr>';
//         $cart[] = '<td>' . $product . '</td>';
//         $cart[] = '<td>' . $quantity . '</td>';
//         $cart[] = '</tr>';
//     }

//     $cart = serialize($cart);

//     printShoppingCart($cart);

//     return $cart;
// }

// function checkIfProductExistsInShoppinCartCookie($cart, $product_id)
// {
//     $cart = deleteIndex0CartCookie($cart);

//     echo '<pre>';
//     var_dump($cart);
//     echo '</pre>';

//     $increment = false;
//     $quantity = '';
//     $j = 0;

//     foreach ($cart as $product) {
//         $i = 0;
//         $product_number = '';

//         if (str_contains($product, $product_id)) {
//             $increment = true;
//         }
//         if ($increment && strlen($product) > 4) {
//             while ($i < strlen($product)) {
//                 if (is_numeric($product[$i]) || $product_id == 0 || $product[$i] == 'P') {
//                     $product_number = $product[$i];
//                 }
//                 echo $product_id;
//                 if ($product_id == $product_number && is_numeric($product[$i])) {
//                     $quantity = $product[$i++] + 1;
//                     $cart[$j] = '<td>' . $quantity . '</td>';
//                     echo '<pre>';
//                     var_dump($cart);
//                     echo '</pre>';
//                 }

//                 $i++;
//             }
//         }

//         $j++;
//     }

//     $cart = serialize($cart);

//     setcookie('cart', $cart, time() + (86400 * 30), "/");
// }

// // deletes the '' in the coockie
// function deleteIndex0CartCookie($cart)
// {
//     if ($cart[0] == '') {
//         array_splice($cart, 0, 1);
//     }

//     return $cart;
// }

function storePurchase($product, $quantity)
{
    $_SESSION['purchase'][$product] = $quantity;
}

// function to add products to existing cookie
function addPurchaseToCookie($product, $quantity)
{
    if (isset($_SESSION['purchase'][$product])) {
        $_SESSION['purchase'][$product] += $quantity;
    } else {
        $_SESSION['purchase'][$product] = $quantity;
    }
}

// function to realize purchase
function finalizePurchase()
{
    $conn = connection();

    session_start();

    if (!isset($_SESSION['purchase'])) {
        printThings('<br/>El carrito está vacío');
    } else {
        foreach ($_SESSION['purchase'] as $product_id => $quantity) {
            buyProduct($conn, $_SESSION['generic'], $product_id, $quantity);
        }

        echo "\nSe ha realizado su compra satisfactoriamente</br>";
    }
}
