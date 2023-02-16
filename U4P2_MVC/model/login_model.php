<?php
function check_login($usrname, $password)
{
    global $conn;

    try {

        $sql = $conn->prepare("SELECT customerNumber, contactLastName FROM customers where customerNumber = :usrname and contactLastName = :password;");

        $usrname = strtolower($usrname);
        $password = strtolower($password);

        $sql->bindParam('usrname', $usrname);
        $sql->bindParam('password', $password);

        $sql->execute();

        if ($sql->rowCount() > 0) {
            setcookie('generic', $usrname, time() + (86400 * 30), "/");

            echo "<script>window.open('view/index_view.phtml', '_self')</script>";
        } else {
            echo '<br>Los datos introducidos no son válidos';
        }
    } catch (PDOException $e) {
        echo "<br>Error: " . $e->getMessage();
    }
}
