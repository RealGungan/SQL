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
    $sql = $conn->prepare("SELECT ID_CATEGORIA FROM CATEGORIA ORDER BY ID_CATEGORIA");
    $sql->execute();
    $sql->setFetchMode(PDO::FETCH_ASSOC);
    $result = $sql->fetchAll();

    $string_result = '';

    foreach ($result as $key => $value) {
        $string_result = $value["ID_CATEGORIA"];
    }

    $int = (int) filter_var($string_result, FILTER_SANITIZE_NUMBER_INT);
    $int++;

    $category_code = "C" . str_pad($int, strlen($int) + 3 - strlen($int), '0', STR_PAD_LEFT);

    return $category_code;
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
    $sql = $conn->prepare("SELECT ID_PRODUCTO FROM PRODUCTO ORDER BY ID_PRODUCTO");
    $sql->execute();
    $sql->setFetchMode(PDO::FETCH_ASSOC);
    $result = $sql->fetchAll();
    $string_result = "";

    foreach ($result as $key => $value) {
        $string_result = $value["ID_PRODUCTO"];
    }

    $int = (int) filter_var($string_result, FILTER_SANITIZE_NUMBER_INT);
    $int++;

    $product_code = "P" . str_pad($int, strlen($int) + 4 - strlen($int), '0', STR_PAD_LEFT);

    return $product_code;
}

//------------ EJERCICIO 3 ------------

function addStorage($conn, $localidad)
{
    test_input($localidad);
    $sql = $conn->prepare("INSERT INTO ALMACEN (LOCALIDAD) VALUES (:localidad)");
    $sql->bindParam('localidad', $localidad);
    $sql->execute();
}

// ------------ EJERCICIO 4 ------------

function addProductsStorage($conn, $warehouse, $product_id, $quantity)
{
    test_input($quantity);

    $sql = $conn->prepare(
        "INSERT INTO ALMACENA (NUM_ALMACEN, ID_PRODUCTO, CANTIDAD) VALUES (:num_warehouse, :product_id, :quaintity)"
    );
    $sql->bindParam('num_warehouse', $warehouse);
    $sql->bindParam('product_id', $product_id);
    $sql->bindParam('quaintity', $quantity);

    $sql->execute();
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

// ------------ EJERCICIO 6 ------------

function getWarehouseInfo($conn, $warehouse)
{
    try {
        $stmt = $conn->prepare("SELECT PRODUCTO.NOMBRE, ALMACEN.NUM_ALMACEN, ALMACEN.LOCALIDAD
                                FROM PRODUCTO, ALMACENA, ALMACEN 
                                WHERE PRODUCTO.ID_PRODUCTO = ALMACENA.ID_PRODUCTO 
                                AND ALMACENA.NUM_ALMACEN = ALMACEN.NUM_ALMACEN");
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $res = $stmt->fetchAll();

        return $res;
    } catch (PDOException $e) {
        return [];
    }
}

function printWarehouseInfo($conn, $warehouse)
{
    $res = getWarehouseInfo($conn, $warehouse);

    echo '<br/>';
    echo 'En el almacen localizado en ' . ucfirst(strtolower($res[$warehouse]['LOCALIDAD']))  . ' contiene los siguientes productos:';
    echo '<ul>';

    foreach ($res as $product => $value) {
        if ($value['NUM_ALMACEN'] == $warehouse) {
            echo '<li>';
            echo $value['NOMBRE'];
            echo '</li>';
        }
    }

    echo '</ul>';
}

//funcion para tratar los datos
function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);

    return $data;
}

//------------------------ejercicio 5
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
function getTotalProducts($conn, $localidad)
{
    $sqL = $conn->prepare("SELECT COUNT(*) FROM PRODUCTO,ALMACENA,ALMACEN WHERE PRODUCTO.ID_PRODUCTO=ALMACENA.ID_PRODUCTO AND ALMACENA.NUM_ALMACEN=ALMACEN.NUM_ALMACEN GROUP BY ALMACEN.LOCALIDAD");
}
