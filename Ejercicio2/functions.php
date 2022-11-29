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
        $sql = $conn->prepare("INSERT INTO categoria (NOMBRE) VALUES (:nombre)");

        $sql->bindParam(':nombre', $name);
        $sql->execute();
    } catch (PDOException $e) {
        echo "<br>Error: " . $e->getMessage();
    }
}
function generateCategoryCod($conn){
    $sql = $conn->prepare("SELECT ID_CATEGORIA FROM CATEGORIA VALUES (:nombre)");
    $number=0;
    $number++;
    return $category_code="C"+$number++;
}
function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }
