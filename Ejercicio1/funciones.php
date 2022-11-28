<?php
//funcion de conexion
function connection()
{
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "empleadosnn";

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
    return $conn;
}


//FUNCIONES PARA CREAR DESPLEGABLE 
function obtenerDnis($conn)
{
    try {
        $stmt = $conn->prepare("SELECT DNI FROM emple");
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $resultado = $stmt->fetchAll();
        // var_dump($resultado);
        return $resultado;
    } catch (PDOException $e) {

        return [];
    }
}
function obtenerDepartamentos($conn)
{
    try {

        $stmt = $conn->prepare("SELECT NOMBRE FROM DPTO");
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $resultado = $stmt->fetchAll();
        return $resultado;
    } catch (PDOException $e) {
        return [];
    }
}
// función para añadir departamento
function altaDpto($name, $conn)
{
    try {
        $sql = $conn->prepare("INSERT INTO DPTO (NOMBRE) VALUES (:nombre)");

        $sql->bindParam(':nombre', $name);
        $sql->execute();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
//funcion para añadir empleado
function darAltaEmpleado($conn)
{
    $nombre = $_POST["nombre"];
    $dni = $_POST["dni"];
    $salario = $_POST["salario"];
    $fecha_nac = $_POST["fecha_nac"];
    try {
        //insertamos en tabla emple
        $stmt = $conn->prepare("INSERT INTO EMPLE (DNI,NOMBRE,SALARIO,FECHA_NAC) 
        VALUES (:dni,:nombre,:salario,:fecha_nac)");
        $stmt->bindParam("dni", $dni);
        $stmt->bindParam("nombre", $nombre);
        $stmt->bindParam("salario", $salario);
        $stmt->bindParam("fecha_nac", $fecha_nac);
        $stmt->execute();
        echo "Dado de alta el empleado/a";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
function incluirEmpleadoEnTablaNN($conn)
{
    $dni = $_POST["dni"];
    $nombre_departamento = $_POST["departamentos"];
    try {
        //para insertar en la tabla emple_dpto
        //buscamos el id_codigo del departamento correspondido haciendo un select
        $stmt2 = $conn->prepare("SELECT ID_DPTO FROM DPTO WHERE DPTO.NOMBRE=:nombre_dpto");
        $stmt2->bindParam("nombre_dpto", $nombre_departamento);
        $stmt2->execute();
        $stmt2->setFetchMode(PDO::FETCH_ASSOC);
        $resultado = $stmt2->fetchAll();
        //var_dump($resultado);
        $stringResultado = "";
        foreach ($resultado as $key => $value) {
            $stringResultado = $value["ID_DPTO"];
        }
        //objeto de datetime, lo guardamos en variable string para poder usarla
        $fecha = new DateTime();
        $stringFecha = $fecha->format("Y-m-d");
        $stmt3 = $conn->prepare("INSERT INTO EMPLE_DPTO (DNI_EMPLE,ID_DPTO,FECHA_INICIO,FECHA_FIN) VALUES (:dni,:id,:fecha_inicio,null)");
        $stmt3->bindParam("dni", $dni);
        $stmt3->bindParam("id", $stringResultado);
        $stmt3->bindParam("fecha_inicio", $stringFecha);
        $stmt3->execute();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
//-------------------------------------funciones para cambiar de departamento----------------------------------------------
function obtenerDptoActual($conn)
{
    try {
        $codigodni = $_POST["dnies"];
        $stmt = $conn->prepare("SELECT DPTO.NOMBRE FROM DPTO,EMPLE_DPTO WHERE 
        EMPLE_DPTO.ID_DPTO=DPTO.ID_DPTO AND EMPLE_DPTO.DNI_EMPLE=:codigodni AND EMPLE_DPTO.FECHA_FIN IS null");
        $stmt->bindParam("codigodni", $codigodni);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $resultado = $stmt->fetchAll();
        //var_dump($resultado);
        $stringResultado = "";
        foreach ($resultado as $actual => $value) {
            $stringResultado = $value["NOMBRE"];
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
//------------------------------funciones para modificar el salario
function obtenerSalarioActual($conn)
{
    try {

        $codigodni = $_POST["dnies"];
        $stmt = $conn->prepare("SELECT EMPLE.SALARIO FROM EMPLE WHERE 
        EMPLE.DNI=:codigodni");
        $stmt->bindParam("codigodni", $codigodni);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $resultado = $stmt->fetchAll();
        //var_dump($resultado);
        $stringResultado = "";
        foreach ($resultado as $actual => $value) {
            $stringResultado = $value["SALARIO"];
            //echo $value["COD_DPTO"];
        }
        return $stringResultado;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
function modificarSalario($conn)
{
    try {
        $codigodni = $_POST["dnies"];
        $salarioNuevo = floatval($_POST["salario"]);
        $stmt = $conn->prepare("UPDATE EMPLE  SET SALARIO=:salarioNuevo WHERE DNI=:codigodni");
        $stmt->bindParam("codigodni", $codigodni);
        $stmt->bindParam("salarioNuevo", $salarioNuevo);
        $stmt->execute();
        echo "Actualizado salario del trabajador " . $codigodni . "con salario nuevo" . $salarioNuevo;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
//---------------------------------funciones para listar empleados---------------------------------
function mostrarEmpleadoPorDepartamento($conn)
{
    try {
        $nombredpto = $_POST["departamentos"];
        $stmt = $conn->prepare("SELECT EMPLE.NOMBRE FROM EMPLE,EMPLE_DPTO,DPTO WHERE 
        DPTO.ID_DPTO=EMPLE_DPTO.ID_DPTO AND EMPLE_DPTO.DNI_EMPLE=EMPLE.DNI AND DPTO.NOMBRE=:nombredpto AND EMPLE_DPTO.FECHA_FIN IS null");
        $stmt->bindParam("nombredpto", $nombredpto);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $resultado = $stmt->fetchAll();
        //var_dump($resultado);
        $stringResultado = "";
        foreach ($resultado as $actual => $value) {
            $stringResultado = $value["NOMBRE"];
            echo $stringResultado . "<br>";
            //echo $value["COD_DPTO"];
        }
        return $stringResultado;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
