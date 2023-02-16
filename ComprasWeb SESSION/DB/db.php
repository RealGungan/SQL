<?php
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

function addClient($conn, $nif, $nombre, $apellido, $cp, $direc, $ciu, $usrname)
{
    try {
        $sql = $conn->prepare("INSERT INTO CLIENTE (NIF,NOMBRE,APELLIDO,CP,DIRECCION,CIUDAD, USERNAME, PASSWORD) VALUES (:nif,:nombre,:apellido,:cp,
        :direccion,:ciudad, :usrname, :password)");

        $password = strrev(strtolower($apellido));

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
function userExists($conn, $usrname, $password)
{
    try {
        $sql = $conn->prepare("SELECT * FROM cliente where cliente.username = :usrname and cliente.password = :password");

        $usrname = strtolower($usrname);
        $password = strtolower($password);

        $sql->bindParam('usrname', $usrname);
        $sql->bindParam('password', $password);

        $sql->execute();

        $exists = $sql->rowCount();
    } catch (PDOException $e) {
        echo "<br>Error: " . $e->getMessage();
    }

    if ($exists = 0) {
        echo "<br/> ESTE USUARIO NO EXISTE";
    } else {
        login($conn, $password, $usrname);
    }
}
