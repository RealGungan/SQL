<?php
require_once("funciones.php");
$conn = connection();
function obtenerDptoActual($conn)
{
    try {

        $codigodni = $_POST["dnies"];
        $stmt = $conn->prepare("SELECT DPTO.NOMBRE FROM DPTO,EMPLE_DPTO WHERE 
        EMPLE_DPTO.ID_DPTO=DPTO.ID_DPTO AND EMPLE_DPTO.DNI_EMPLE=:codigodni");
        $stmt->bindParam("codigodni", $codigodni);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $resultado = $stmt->fetchAll();
        //var_dump($resultado);
        $stringResultado = "";
        foreach ($resultado as $actual => $value) {
            $stringResultado .= $value["NOMBRE"];
            echo $value["COD_DPTO"];
        }
        return $stringResultado;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
function actualizarDptoAnterior($conn)
{
    try {
        $codigodni = $_POST["dnies"];
        $fecha = $_POST["fecha"];
        $stmt = $conn->prepare("UPDATE EMPLE_DPTO  SET FECHA_FIN=:fechanueva WHERE DNI_EMPLE=:codigodni");
        $stmt->bindParam("codigodni", $codigodni);
        $stmt->bindParam("fechanueva", $fecha);
        $stmt->execute();
        echo "Actualizado registro del trabajador " . $codigodni;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
function crearCambioDepartamento($conn)
{
    try {
        $nombredpto = $_POST["departamentos"];
        $dni = $_POST["dnies"];
        $fechaInicio = $_POST["fecha"];
        $stmt3 = $conn->prepare("SELECT ID_DPTO FROM DPTO WHERE NOMBRE=:nombreCodigodpto");
        $stmt3->bindParam("nombreCodigodpto", $nombredpto);
        $stmt3->execute();
        $codigodpto = "";
        $resultadoCodigo = $stmt3->fetchAll();
        foreach ($resultadoCodigo as $actual => $value) {
            $codigodpto = $value["ID_DPTO"];
            // var_dump($value);
        }

        $stmt = $conn->prepare("INSERT INTO EMPLE_DPTO (DNI_EMPLE,ID_DPTO,FECHA_INICIO,FECHA_FIN) 
         VALUES (:dni,:codigodpto,:fechainicio,null)");
        $stmt->bindParam("dni", $dni);
        $stmt->bindParam("codigodpto", $codigodpto);
        $stmt->bindParam("fechainicio", $fechaInicio);
        $stmt->execute();
        echo "Nuevo registro dado de alta  ";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
