<?php
// ------------------ ORDERS ------------------
function addOrder($conn, $customer_number)
{
    try {
        $sql = $conn->prepare("SELECT MAX(orderNumber) + 1 FROM orders");

        $sql->execute();

        $max = $sql->fetchAll();
        $max = $max[0][0];

        $date = new DateTime();
        $string_date = $date->format("Y-m-d");

        $sql = $conn->prepare("INSERT INTO orders VALUES($max, :date, :date, null, 'Shipped', null, :customer_number)");

        $sql->bindParam('customer_number', $customer_number);
        $sql->bindParam('date', $string_date);


        $sql->execute();
    } catch (PDOException $e) {
        echo "<br>Error: " . $e->getMessage();
    }

    addOrderDetails($conn);
}

function addOrderDetails($conn)
{
    try {
        $sql = $conn->prepare("SELECT MAX(orderNumber) + 1 FROM orderdetails");

        $sql->execute();

        $max = $sql->fetchAll();
        $max = $max[0][0];

        $cart = unserialize($_COOKIE['cart']);
        $order_line = 1;

        foreach ($cart as $product_code => $quantity) {
            $sql = $conn->prepare("INSERT INTO orderdetails VALUES(:order_number, :product_code, :quantity, (SELECT buyPrice from products WHERE productCode = :product_code), :order_line)");

            $sql->bindParam('order_number', $max);
            $sql->bindParam('product_code', $product_code);
            $sql->bindParam('quantity', $quantity);
            $sql->bindParam('order_line', $order_line);

            $sql->execute();

            $order_line++;
        }
    } catch (PDOException $e) {
        echo "<br>Error: " . $e->getMessage();
    }
}

// ------------------------------------

// ------------------ PURCHASE ------------------
function purchase($conn)
{
    $cart = unserialize($_COOKIE['cart']);

    foreach ($cart as $product_code => $quantity) {
        try {
            $sql = $conn->prepare("SELECT quantityInStock FROM products WHERE productCode=:product_id");

            $sql->bindParam('product_id', $product_code);

            $sql->execute();

            $sql->setFetchMode(PDO::FETCH_NUM);

            $product_quantity = $sql->fetchAll();
            $product_quantity = $product_quantity[0][0];

            $sql = $conn->prepare("UPDATE products SET quantityInStock=:updated_quantity WHERE productCode = :product_id");

            $quantity_rest = $product_quantity - $quantity;

            $sql->bindParam('updated_quantity', $quantity_rest);
            $sql->bindParam('product_id', $product_code);

            $sql->execute();
        } catch (PDOException $e) {
            echo "<br>Error: " . $e->getMessage();
        }
    }
}

//------------------------------------