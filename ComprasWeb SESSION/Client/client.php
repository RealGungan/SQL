<?php

// ------------------ LOGIN ------------------
function login($conn, $password, $usrname)
{
    try {
        $sql = $conn->prepare("SELECT NIF FROM cliente where cliente.username = :usrname and cliente.password = :password");

        $usrname = strtolower($usrname);
        $password = strtolower($password);

        $sql->bindParam('usrname', $usrname);
        $sql->bindParam('password', $password);

        $sql->execute();

        $sql->setFetchMode(PDO::FETCH_NUM);
        $dni = $sql->fetchAll();

        session_start();

        $_SESSION['generic'] = $dni[0][0];

        $link = "<script>window.open('../html/index_cliente.html', '_self')</script>";
        echo $link;
    } catch (PDOException $e) {
        echo "<br>Error: " . $e->getMessage();
    }
}
// ------------------------------------

// ------------------ LOG OUT ------------------
function logOut()
{
    if (isset($_SESSION)) {
        $_SESSION = array();

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }

        session_destroy();
    }

    echo "<script>window.open('../html/index.html', '_self')</script>";
}
// ------------------------------------

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

// ------------------ MANAGE USER ------------------
function getNameViaDNI($conn, $dni)
{
    try {
        $sql = $conn->prepare("SELECT NOMBRE FROM cliente where cliente.NIF = :key");

        $sql->bindParam('key', $dni);

        $sql->execute();

        $sql->setFetchMode(PDO::FETCH_NUM);
        $name = $sql->fetchAll();

        printThings($name[0][0]);
    } catch (PDOException $e) {
        echo "<br>Error: " . $e->getMessage();

        return 'NULL';
    }
}

function getDNIViaName($conn, $name)
{

    try {
        $sql = $conn->prepare("SELECT NOMBRE FROM cliente where cliente.NIF = :key");

        $sql->bindParam('key', $name);

        $sql->execute();

        $sql->setFetchMode(PDO::FETCH_NUM);
        $name = $sql->fetchAll();

        printThings($name[0][0]);
    } catch (PDOException $e) {
        echo "<br>Error: " . $e->getMessage();

        return 'NULL';
    }
}

// ------------------------------------