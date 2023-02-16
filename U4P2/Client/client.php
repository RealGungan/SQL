<?php

// ------------------ LOGIN ------------------
function login($conn, $usrname, $password)
{
    try {
        $sql = $conn->prepare("SELECT customerNumber, contactLastName FROM customers where customerNumber = :usrname and contactLastName = :password");

        $usrname = strtolower($usrname);
        $password = strtolower($password);

        $sql->bindParam('usrname', $usrname);
        $sql->bindParam('password', $password);

        $sql->execute();

        if ($sql->rowCount() > 0) {
            setcookie('generic', $usrname, time() + (86400 * 30), "/");

            echo "<script>window.open('../php/pe_inicio.php', '_self')</script>";
        } else {
            return false;
        }
    } catch (PDOException $e) {
        echo "<br>Error: " . $e->getMessage();
    }
}
// ------------------------------------

//------------------ REGISTER ------------------
function addClient($conn, $nombre, $password)
{
    try {
        $sql = $conn->prepare("INSERT INTO CLIENTE (NIF,NOMBRE,APELLIDO,CP,DIRECCION,CIUDAD, USERNAME, PASSWORD) VALUES (:nif,:nombre,:apellido,:cp,
        :direccion,:ciudad, :usrname, :password)");

        $sql->bindParam('nombre', $nombre);
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
//------------------------------------

// ------------------ LOG OUT ------------------

function logOut()
{
    setcookie('generic', "", -1, "/");
}

// ------------------------------------