<?php
/*SELECTs - mysql PDO*/

$servername = "localhost";
$username = "root";
$password = "rootroot";
$dbname = "empleados1n";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $conn->prepare("SELECT emple.nombre FROM emple,dpto 
                    WHERE emple.cod_dpto=dpto.cod_dpto and dpto.nombre=:nombredpto");
    $valor = $_POST['value'];
    $stmt->bindParam('nombredpto', $valor);
    $stmt->execute();

    // set the resulting array to associative
    $stmt->setFetchMode(PDO::FETCH_NUM);
    $resultado = $stmt->fetchAll();
    echo '<pre>';
    var_dump($resultado);
    echo '</pre>';
    //  foreach($resultado as $row) {
    //     echo "Codigo dpto: " . $row["cod_dpto"]. " - Nombre: " . $row["nombre"]. "<br>";
    //  }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
$conn = null;
