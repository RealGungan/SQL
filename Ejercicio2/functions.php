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

function addCategory($name, $conn)
{
    try {
        test_input($name);
        $category_code=generateCategoryCod($conn);
        $sql = $conn->prepare("INSERT INTO categoria (ID_CATEGORIA,NOMBRE) VALUES (:idcategoria,:nombre)");
        $sql->bindParam(':idcategoria', $category_code);
        $sql->bindParam(':nombre', $name);
        $sql->execute();
    } catch (PDOException $e) {
        echo "<br>Error: " . $e->getMessage();
    }
}
function generateCategoryCod($conn){
    $sql = $conn->prepare("SELECT ID_CATEGORIA FROM CATEGORIA ORDER BY ID_CATEGORIA DESC");
    $sql->execute();
    $sql->setFetchMode(PDO::FETCH_ASSOC);
    $result = $sql->fetchAll();
    $string_result="";
    foreach ($result as $key => $value) {
        $string_result = $value["ID_CAGTEGORIA"];
    }
    $int = (int) filter_var($string_result, FILTER_SANITIZE_NUMBER_INT);
    $int++;
    $category_code = "P".$int;
    return $category_code;
}
function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }
