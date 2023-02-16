<?php
function getWarehouse($conn)
{
    try {
        $stmt = $conn->prepare("SELECT NUM_ALMACEN,LOCALIDAD FROM ALMACEN");
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $resultado = $stmt->fetchAll();

        return $resultado;
    } catch (PDOException $e) {
        return [];
    }
}
function getWarehouseInfo($conn, $warehouse)
{
    try {
        $stmt = $conn->prepare("SELECT PRODUCTO.NOMBRE, CANTIDAD, ALMACEN.NUM_ALMACEN, ALMACEN.LOCALIDAD
                                FROM PRODUCTO, ALMACENA, ALMACEN 
                                WHERE PRODUCTO.ID_PRODUCTO = ALMACENA.ID_PRODUCTO 
                                AND ALMACENA.NUM_ALMACEN = ALMACEN.NUM_ALMACEN AND ALMACEN.NUM_ALMACEN=:warehouse");

        $stmt->bindparam('warehouse', $warehouse);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $res = $stmt->fetchAll();

        return $res;
    } catch (PDOException $e) {
        echo "<br>Error: " . $e->getMessage();
    }
}

function printWarehouseInfo($conn, $warehouse)
{
    $res = getWarehouseInfo($conn, $warehouse);

    if (count($res) != 0) {
        echo '<br/>';
        echo 'En el almacen localizado en ' . ucfirst(strtolower($res[0]['LOCALIDAD']))  . ' contiene los siguientes productos:';
        echo '<ul>';

        foreach ($res as $product => $value) {
            if ($value['NUM_ALMACEN'] == $warehouse) {
                echo '<li>';
                echo $value['NOMBRE'] . ' -> CANTIDAD: ' . $value['CANTIDAD'];
                echo '</li>';
            }
        }

        echo '</ul>';
    } else {
        echo "En esta localidad no hay productos dados de alta";
    }
}

function isAvailable($conn, $product, $cantidad)
{
    test_input($cantidad);

    $valid = true;
    $result = getTotalProducts($conn, $product);
    $total = getWarehousesTotalQuantity($conn, $product, true);

    if ($total <= 0 || !is_numeric($cantidad)) {
        $valid = false;
        echo "</br>Por favor introduzca una cantidad correcta</br>";
    } else if ($cantidad > $total) {
        $valid = false;
        echo "</br>No hay existencias suficientes</br>";
    }

    return $valid;
}

function getWarehousesTotalQuantity($conn, $product, $getTotal)
{
    try {
        $sql = $conn->prepare("SELECT * FROM almacena where almacena.id_producto = :id_producto");

        $sql->bindParam('id_producto', $product);

        $sql->execute();
        $sql->setFetchMode(PDO::FETCH_ASSOC);

        $res = $sql->fetchAll();

        $total = 0;

        foreach ($res as $key => $value) {
            $total += $value['CANTIDAD'];
        }

        return $total;
    } catch (PDOException $e) {
        $error = $e->getCode();
        echo 'ERROR: ' . $error;
    }
}

function checkWarehouseQuantity($conn, $product, $quantity, $index)
{
    try {
        $sql = $conn->prepare("SELECT * FROM almacena where almacena.ID_PRODUCTO = :id_producto ORDER BY cantidad DESC");
        $sql->bindParam('id_producto', $product);
        $sql->execute();
        $sql->setFetchMode(PDO::FETCH_ASSOC);

        $warehouse = $sql->fetchAll();
    } catch (PDOException $e) {
        $error = $e->getCode();
        echo '</br>ERROR: ' . $error;
    }

    $i = 0;

    while ($quantity - $warehouse[$i]['CANTIDAD'] > 0) {
        updateTableAlmacena($conn, $product, 0, $warehouse[$i]['ID_PRODUCTO'], $warehouse[$i]['NUM_ALMACEN']);

        $quantity = $quantity - $warehouse[$i]['CANTIDAD'];

        $i++;
    }

    $quantity = $warehouse[$i]['CANTIDAD'] - $quantity;
    updateTableAlmacena($conn, $product, $quantity, $warehouse[$i]['ID_PRODUCTO'], $warehouse[$i]['NUM_ALMACEN']);
}
