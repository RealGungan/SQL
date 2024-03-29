<?php

//funcion de conexion
function connection()
{
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "COMPRASWEB";

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        echo "<br>Error: " . $e->getMessage();
    }

    return $conn;
}

// ------------------ EJERCICIO 1 ------------------

//funcion para agregar la categoria a la base de datos
function addCategory($name, $conn)
{
    try {
        test_input($name);
        $category_code = generateCategoryId($conn);

        $sql = $conn->prepare("INSERT INTO categoria (ID_CATEGORIA,NOMBRE) VALUES (:idcategoria,:nombre)");
        $sql->bindParam(':idcategoria', $category_code);
        $sql->bindParam(':nombre', $name);
        $sql->execute();
    } catch (PDOException $e) {
        echo "<br>Error: " . $e->getMessage();
    }
}

//funcion para generar el codigo de la categoria
function generateCategoryId($conn)
{
    try {
        $sql = $conn->prepare("SELECT MAX(ID_CATEGORIA) FROM CATEGORIA");
        $sql->execute();
        $sql->setFetchMode(PDO::FETCH_ASSOC);
        $res = $sql->fetchAll();

        $id = (float) substr($res[0]['MAX(ID_CATEGORIA)'], 2) + 1;

        $category_code = "C-" . str_pad($id, 3, '0', STR_PAD_LEFT);

        return $category_code;
    } catch (PDOException $e) {
        echo "<br>Error: " . $e->getMessage();
    }
}

// ------------------ EJERCICIO 2 ------------------

//funcion para agregar el producto a la base de datos
function addProduct($conn, $name, $category, $price)
{
    try {
        test_input($name);
        test_input($price);

        $category_string = $category;

        $product_code = generateProductCod($conn);
        $sql = $conn->prepare("INSERT INTO PRODUCTO (ID_PRODUCTO, NOMBRE, PRECIO, ID_CATEGORIA) VALUES (:idproducto,:nombre,:precio,:idcategoria)");
        $sql->bindParam('idproducto', $product_code);
        $sql->bindParam('nombre', $name);
        $sql->bindParam('precio', $price);
        $sql->bindParam('idcategoria', $category_string);
        $sql->execute();
    } catch (PDOException $e) {
        echo "<br>Error: " . $e->getMessage();
    }
}

//funcion para generar desplegable de categorias
function getNamesOfCategories($conn)
{
    try {
        $stmt = $conn->prepare("SELECT ID_CATEGORIA,NOMBRE FROM CATEGORIA");
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $resultado = $stmt->fetchAll();

        return $resultado;
    } catch (PDOException $e) {
        return [];
    }
}

//funcion para generar el codigo del producto
function generateProductCod($conn)
{
    try {
        $sql = $conn->prepare("SELECT MAX(ID_PRODUCTO) FROM PRODUCTO");
        $sql->execute();
        $sql->setFetchMode(PDO::FETCH_ASSOC);
        $res = $sql->fetchAll();

        $id = (float) substr($res[0]['MAX(ID_PRODUCTO)'], 2) + 1;

        $product_code = "P" . str_pad($id, 4, '0', STR_PAD_LEFT);

        return $product_code;
    } catch (PDOException $e) {
        echo "<br>Error: " . $e->getMessage();
    }
}

//------------ EJERCICIO 3 ------------

function addStorage($conn, $localidad)
{
    try {
        test_input($localidad);
        $sql = $conn->prepare("INSERT INTO ALMACEN (LOCALIDAD) VALUES (:localidad)");
        $sql->bindParam('localidad', $localidad);
        $sql->execute();
    } catch (PDOException $e) {
        echo "<br>Error: " . $e->getMessage();
    }
}

// ------------ EJERCICIO 4 ------------
function isProduct($conn, $warehouse, $product)
{
    try {
        $valid = true;

        $sql = $conn->prepare("SELECT COUNT(ID_PRODUCTO) FROM ALMACENA WHERE ALMACENA.ID_PRODUCTO=:product AND
        ALMACENA.NUM_ALMACEN=:warehouse");
        $sql->bindParam('product', $product);
        $sql->bindParam('warehouse', $warehouse);
        $sql->execute();
        $sql->setFetchMode(PDO::FETCH_NUM);
        $resultado = $sql->fetchAll();
        $resultado = $resultado[0][0];
        if ($resultado <= 0) {
            $valid = false;
        }
        return $valid;
    } catch (PDOException $e) {
        echo "<br>Error: " . $e->getMessage();
    }
}
function addProductsStorage($conn, $warehouse, $product_id, $quantity)
{
    try {
        test_input($quantity);
        //he usado ignore para que si existe problemas de clave primarias, vaya a la siguiente peticion y busque los valores para aprovisionar
        $sql = $conn->prepare(
            "INSERT INTO ALMACENA (NUM_ALMACEN, ID_PRODUCTO, CANTIDAD) VALUES (:num_warehouse, :product_id, :quaintity)"
        );
        $sql->bindParam('num_warehouse', $warehouse);
        $sql->bindParam('product_id', $product_id);
        $sql->bindParam('quaintity', $quantity);
        $sql->execute();
    } catch (PDOException $e) {
        echo "<br>Error: " . $e->getMessage();
    }
}
function updateProduct($conn, $warehouse, $product_id, $quantity)
{
    try {
        $sql1 = $conn->prepare("SELECT CANTIDAD FROM ALMACENA WHERE ALMACENA.ID_PRODUCTO=:product_id AND
        ALMACENA.NUM_ALMACEN=:num_warehouse");
        $sql1->bindParam('num_warehouse', $warehouse);
        $sql1->bindParam('product_id', $product_id);
        $sql1->execute();
        $sql1->setFetchMode(PDO::FETCH_NUM);
        $resultado = $sql1->fetchAll();
        $resultado = $resultado[0][0];
        $resultado = intval($resultado) + intval($quantity);

        $sql = $conn->prepare("UPDATE ALMACENA SET CANTIDAD=:resultado WHERE ALMACENA.ID_PRODUCTO=:product_id AND
        ALMACENA.NUM_ALMACEN=:num_warehouse");
        $sql->bindParam('num_warehouse', $warehouse);
        $sql->bindParam('product_id', $product_id);
        $sql->bindParam('resultado', $resultado);
        $sql->execute();
    } catch (PDOException $e) {
        echo "<br>Error: " . $e->getMessage();
    }
}

//funcion para generar desplegable de los almacenes
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

//funcion para generar desplegable de los productos
function getProducts($conn)
{
    try {
        $stmt = $conn->prepare("SELECT ID_PRODUCTO,NOMBRE FROM PRODUCTO");
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $resultado = $stmt->fetchAll();

        return $resultado;
    } catch (PDOException $e) {
        return [];
    }
}

//------------ EJERCICIO 5 ------------

function getNamesOfProduct($conn)
{
    try {
        $sql = $conn->prepare("SELECT ID_PRODUCTO,NOMBRE FROM PRODUCTO");
        $sql->execute();
        $sql->setFetchMode(PDO::FETCH_ASSOC);
        $resultado = $sql->fetchAll();

        return $resultado;
    } catch (PDOException $e) {
        return [];
    }
}

// se mostrará la cantidad disponible del producto seleccionado en cada uno de los almacenes.
function getTotalProducts($conn, $product)
{
    try {
        test_input($product);
        $sql = $conn->prepare("SELECT CANTIDAD,LOCALIDAD 
                            FROM PRODUCTO,ALMACENA,ALMACEN 
                            WHERE ALMACENA.ID_PRODUCTO=PRODUCTO.ID_PRODUCTO 
                            AND ALMACENA.NUM_ALMACEN=ALMACEN.NUM_ALMACEN 
                            AND PRODUCTO.ID_PRODUCTO=:producto");
        $sql->bindParam('producto', $product);
        $sql->execute();
        $sql->setFetchMode(PDO::FETCH_ASSOC);
        $resultado = $sql->fetchAll();

        return $resultado;
    } catch (PDOException $e) {
        echo "<br>Error: " . $e->getMessage();
    }
}

// ------------ EJERCICIO 6 ------------

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

// ------------ EJERCICIO 7 ------------

// POR TERMINAR POR FALTA DE EXPLICACION DE COOKIES

function getDnies($conn)
{
    try {
        $sql = $conn->prepare("SELECT NIF FROM CLIENTE");
        $sql->execute();
        $sql->setFetchMode(PDO::FETCH_ASSOC);
        $resultado = $sql->fetchAll();

        return $resultado;
    } catch (PDOException $e) {
        $error = $e->getCode();
    }

    return $resultado;
}
function getPurchaseInfo($conn, $dni, $date1, $date2)
{
    try {
        $sql = $conn->prepare("SELECT PRODUCTO.NOMBRE,PRODUCTO.PRECIO,PRODUCTO.PRECIO,COMPRA.FECHA_COMPRA,COMPRA.UNIDADES FROM PRODUCTO,COMPRA WHERE
        PRODUCTO.ID_PRODUCTO=COMPRA.ID_PRODUCTO AND COMPRA.NIF=:dni and (COMPRA.FECHA_COMPRA >=:date1 and COMPRA.FECHA_COMPRA <:date2)");

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
function printPurchaseInfo($result)
{
    foreach ($result as $key => $value) {
        if (is_numeric($key)) {
            echo "Fecha de Compra: " . $value['FECHA_COMPRA'] . "</br>";
            echo "Nombre del producto: " . $value['NOMBRE'] . "</br>";
            echo "Precio del producto: " . (string)$value['PRECIO'] . "</br></br>";
        }
    }
    echo "</br>El total de sus compras asciende a: " . $result['total'] . " euros";
}
// ------------ EJERCICIO 8 ------------

function isValidDni($nif)
{
    test_input($nif);
    $valido = true;
    $letra = substr($nif, 8);
    $numeros = substr($nif, 0, 7);
    if (strlen($nif) != 9) {
        echo "</br>Error. La longitud no es la correcta. No es posible dar de alta</br>";
        $valido = false;
    } else if (!ctype_alpha($letra)) {
        echo "</br>Error, el último carácter debe de ser una letra</br>";
        $valido = false;
    } else if (!is_numeric($numeros)) {
        echo "</br>Error, debe de ser 8 digitos.</br>";
        $valido = false;
    }
    return $valido;
}

function addClient($conn, $nif, $nombre, $apellido, $cp, $direc, $ciu, $usrname, $password)
{
    try {
        test_input($nif);
        test_input($nombre);
        test_input($apellido);
        test_input($cp);
        test_input($direc);
        test_input($ciu);

        $sql = $conn->prepare("INSERT INTO CLIENTE (NIF,NOMBRE,APELLIDO,CP,DIRECCION,CIUDAD, USER_NAME, PASSWORD) VALUES (:nif,:nombre,:apellido,:cp,
        :direccion,:ciudad, :usrname, :password)");

        $sql->bindParam('nif', $nif);
        $sql->bindParam('nombre', $nombre);
        $sql->bindParam('apellido', $apellido);
        $sql->bindParam('cp', $cp);
        $sql->bindParam('direccion', $direc);
        $sql->bindParam('ciudad', $ciu);
        $sql->bindParam('usrname', $usrname);
        $sql->bindParam('password', $password);

        $sql->execute();

        echo "</br>Se ha dado de alta al cliente</br>";
    } catch (PDOException $e) {
        $error = $e->getCode();

        if ($error = '2300') {
            echo "</br>DNI EXISTENTE. NO SE PUEDE DAR DE ALTA <BR>";
        }
    }
}

// ------------ EJERCICIO 9 ------------
//Compra de Productos (compro.php): el cliente podrá realizar la compra de un solo producto
// siempre que haya disponibilidad del mismo.

function isDniClient($conn, $dni)
{
    $valid = true;
    test_input($dni);
    $sql = $conn->prepare("SELECT COUNT(NIF) FROM CLIENTE WHERE CLIENTE.NIF=:dni");
    $sql->bindParam('dni', $dni);
    $sql->execute();
    $sql->setFetchMode(PDO::FETCH_NUM);
    $resultado = $sql->fetchAll();
    $resultado = $resultado[0][0];
    if ($resultado <= 0) {
        $valid = false;
    }
    return $valid;
}

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

        echo $exists;
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

            echo "Se ha realizado su compra satisfactoriamente</br>";
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

function addPurchase($conn, $dni, $product, $quantity)
{
    try {
        echo 'hoy';
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

        if ($getTotal) {
            $total = 0;

            foreach ($res as $key => $value) {
                $total += $value['CANTIDAD'];
            }

            return $total;
        } else {
            return $res;
        }
    } catch (PDOException $e) {
        $error = $e->getCode();
        echo 'ERROR: ' . $error;
    }
}

// function checkWarehouseQuantity($conn, $product, $quantity, $index)
// {
//     $warehouses = getWarehousesTotalQuantity($conn, $product, false);

//     $warehouse_quantity = $warehouses[$index]['CANTIDAD'];
//     $warehouse = $warehouses[$index];

//     if ($quantity - $warehouse_quantity > 0) {
//         $quantity = $quantity - $warehouse_quantity;

//         if ($quantity > 0) {
//             updateTableAlmacena($conn, $product, 0, $warehouse['ID_PRODUCTO'], $warehouse['NUM_ALMACEN']);
//         }

//         checkWarehouseQuantity($conn, $product, $quantity, $index + 1);
//     } else {
//         $quantity = $warehouse_quantity - $quantity;
//         updateTableAlmacena($conn, $product, $quantity, $warehouse['ID_PRODUCTO'], $warehouse['NUM_ALMACEN']);
//     }
// }

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

// function addPurchase($conn, $product, $quantity, $date)
// {
//     try {
//         $sql = $conn->prepare("SELECT * FROM compra");
//         $sql->execute();
//         $sql->setFetchMode(PDO::FETCH_ASSOC);

//         $warehouse = $sql->fetchAll();
//     } catch (PDOException $e) {
//         $error = $e->getCode();
//         echo 'ERROR: ' . $error;
//     }

//     // if($warehouse)
// }

function updateTableAlmacena($conn, $product, $quantity, $id, $warehouse_num)
{
    try {
        test_input($quantity);

        $stmt = $conn->prepare("UPDATE ALMACENA SET CANTIDAD=:new_quantity WHERE ALMACENA.ID_PRODUCTO=:producto AND ALMACENA.NUM_ALMACEN = :warehouse_num");
        $stmt->bindparam('new_quantity', $quantity);
        $stmt->bindparam('producto', $product);
        $stmt->bindparam('warehouse_num', $warehouse_num);
        $stmt->execute();
    } catch (PDOException $e) {
        echo "<br>Error: " . $e->getMessage();
    }
}

//funcion para tratar los datos
function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);

    return $data;
}
