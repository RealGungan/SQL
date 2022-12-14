<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <h2>Compra de Productos</h2>
    <form method='post' action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <?php
        include("functions.php");
        $conn = connection();
        $products = getNamesOfProduct($conn);
        $productos = getNamesOfProduct($conn);
        ?>
        <label for="name">DNI Clientes</label>
        <input type="text" name="dni">
        <br /><br />
        <label for="name">Producto</label>
        <select name="products">
            <?php foreach ($products as $product => $value) : ?>
                <option value="<?php echo $value['ID_PRODUCTO'] ?>"> <?php echo $value['NOMBRE'] ?> </option>
            <?php endforeach; ?>
        </select>
        <br /><br />
        <label for="name">Cantidad</label>
        <input type="text" name="cantidad" id="cantidad" value="">
        <br /><br />
        <input type="submit" name="submit" id="submit" value="Comprar">
    </form>
</body>

</html>
<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //$conn = connection();
    if (isset($_POST["submit"])) {
        // $nif = $_POST['dnies'];
        $dni=$_POST['dni'];
        $producto = $_POST['products'];
        $cantidad = $_POST['cantidad'];
        if(!isDniClient($conn,$dni)){
         echo "No existe registro del dni Introducido";
        }else{
            if(!isAvailable($conn, $producto, $cantidad)){
                echo "No es posible realizar la compra </br>";
            }else{
                if(buyProduct($conn, $dni, $producto, $cantidad)){
                    updateTableAlmacena($conn,$producto, $cantidad);
                }
            }
        }

        // $valido = isAvailable($conn, $producto, $cantidad);
        // if ($valido == false) {
        //     echo "No es posible realizar la compra </br>";
        // } else {
        //     $valid=buyProduct($conn, $nif, $producto, $cantidad);
        //     if($valid){
        //       updateTableAlmacena($conn,$producto, $cantidad);  
        //     }  
        // }
    } else {
        echo "Por favor, introduzca y elija valores correcto </br>";
    }
}
?>