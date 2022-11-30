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
        echo $category_code;

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
    $sql = $conn->prepare("SELECT ID_CATEGORIA FROM CATEGORIA ORDER BY ID_CATEGORIA LIMIT 1");
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
        echo $category;
        test_input($name);
        test_input($price);

        $category_string = $category;
        echo $category_string;
        $product_code = generateProductCod($conn);
        $sql = $conn->prepare("INSERT INTO PRODUCTO (ID_PRODUCTO,NOMBRE,PRECIO,ID_CATEGORIA) VALUES (:idproducto,:nombre,:precio,:idcategoria)");
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
    $sql = $conn->prepare("SELECT ID_PRODUCTO FROM PRODUCTO ORDER BY ID_PRODUCTO LIMIT 1");
    $sql->execute();
    $sql->setFetchMode(PDO::FETCH_ASSOC);
    $result = $sql->fetchAll();
    $string_result = "";

    foreach ($result as $key => $value) {
        $string_result = $value["ID_PRODUCTO"];
    }

    $int = (int) filter_var($string_result, FILTER_SANITIZE_NUMBER_INT);
    $int++;
    $product_code = "P" . str_pad($int, strlen($int) + 2, '0', STR_PAD_LEFT);;

    return $product_code;
}

//funcion para tratar los datos
function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);

    return $data;
}
//----------------------------------------------------------------Ejercicio 3
function addStorage($conn,$localidad){
    test_input($localidad);
    $sql = $conn->prepare("INSERT INTO ALMACEN (LOCALIDAD) VALUES (:localidad)");
    $sql->bindParam('localidad', $localidad);
    $sql->execute();
}
