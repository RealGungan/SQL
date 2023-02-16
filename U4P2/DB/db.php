<?php
// ------------------ PURCHASE ------------------
function checkQuantity($conn, $product_code, $quantity)
{
    try {
        $sql = $conn->prepare("SELECT quantityInStock, ProductName FROM products WHERE productCode=:product_id");

        $sql->bindParam('product_id', $product_code);

        $sql->execute();

        $sql->setFetchMode(PDO::FETCH_NUM);

        $product = $sql->fetchAll();
        $product_quantity = $product[0][0];
        $product_name = $product[0][1];

        if (intval($product_quantity) - intval($quantity) >= 0) {
            return true;
        } else {
            echo 'Actualmente solo hay ' . intval($product_quantity) . ' unidades del producto ' . $product_name;

            return false;
        }
    } catch (PDOException $e) {
        echo '<br /> ERROR: ' . $e->getMessage();
    }
}
// ------------------------------------

// ------------------ CUSTOMERS ------------------

function getCustomers($conn)
{
    try {
        $sql = $conn->prepare("SELECT customerNumber FROM customers");

        $sql->execute();

        $sql->setFetchMode(PDO::FETCH_NUM);

        return $customers = $sql->fetchAll();
    } catch (PDOException $e) {
        echo '<br /> ERROR: ' . $e->getMessage();
    }
}

function getOrders($conn, $customer_code)
{
    try {
        $sql = $conn->prepare("SELECT orderNumber, orderDate, status FROM orders WHERE customerNumber = :customer_code");

        $sql->bindParam('customer_code', $customer_code);

        $sql->execute();

        $sql->setFetchMode(PDO::FETCH_NUM);

        return $orders = $sql->fetchAll();
    } catch (PDOException $e) {
        echo '<br /> ERROR: ' . $e->getMessage();
    }
}

function getOrderDetails($conn, $order_number)
{
    try {
        $sql = $conn->prepare("SELECT productName, orderLineNumber, quantityOrdered, priceEach FROM orderdetails, products WHERE orderNumber = :order_number AND products.productCode = orderdetails.productCode ORDER BY orderLineNumber");

        $sql->bindParam('order_number', $order_number);

        $sql->execute();

        $sql->setFetchMode(PDO::FETCH_NUM);

        return $order_details = $sql->fetchAll();
    } catch (PDOException $e) {
        echo '<br /> ERROR: ' . $e->getMessage();
    }
}
// ------------------------------------

// ------------------ PRODUCTS ------------------

function getProducts($conn)
{
    try {
        $sql = $conn->prepare("SELECT productCode,productName FROM products WHERE quantityInStock > 0");
        $sql->execute();
        $sql->setFetchMode(PDO::FETCH_ASSOC);

        return $products = $sql->fetchAll();
    } catch (PDOException $e) {
        echo '<br /> ERROR: ' . $e->getMessage();
    }
}

function getProductLines($conn)
{
    try {
        $sql = $conn->prepare("SELECT productLine FROM products GROUP BY productLine");
        $sql->execute();
        $sql->setFetchMode(PDO::FETCH_ASSOC);

        return $products_line = $sql->fetchAll();
    } catch (PDOException $e) {
        echo '<br /> ERROR: ' . $e->getMessage();
    }
}

function getStock($conn, $product_name)
{
    try {
        $sql = $conn->prepare("SELECT quantityInStock FROM products WHERE productName = :product_name");


        $sql->bindParam('product_name', $product_name);

        $sql->execute();
        $sql->setFetchMode(PDO::FETCH_NUM);

        return $quantity = $sql->fetchAll();
    } catch (PDOException $e) {
        echo '<br /> ERROR: ' . $e->getMessage();
    }
}

function getStockProductLine($conn, $product_line)
{
    try {
        $sql = $conn->prepare("SELECT productName, quantityInStock FROM products WHERE productLine = :product_line ORDER BY quantityInStock DESC");


        $sql->bindParam('product_line', $product_line);

        $sql->execute();
        $sql->setFetchMode(PDO::FETCH_NUM);

        return $quantity = $sql->fetchAll();
    } catch (PDOException $e) {
        echo '<br /> ERROR: ' . $e->getMessage();
    }
}

// ------------------------------------

// ------------------ SALES ------------------

function getSalesInfoTwoDates($conn, $in_date, $end_date)
{
    try {
        $sql = $conn->prepare("SELECT orderNumber, orderDate FROM orders where orderDate BETWEEN :in_date AND :end_date");

        $sql->bindParam('in_date', $in_date);
        $sql->bindParam('end_date', $end_date);

        $sql->execute();
        $sql->setFetchMode(PDO::FETCH_NUM);

        return $sales = $sql->fetchAll();
    } catch (PDOException $e) {
        echo '<br /> ERROR: ' . $e->getMessage();
    }
}

function getProductTwoDates($conn, $in_date, $end_date)
{
    try {
        $sql = $conn->prepare("SELECT orderdetails.productCode, productName, quantityOrdered FROM orders, orderdetails, products
            WHERE orders.orderNumber=orderdetails.orderNumber=orderdetails.productCode=products.productCode
            AND orderDate >= :date1 AND  orderDate <= :date2 order by productName");

        $sql->bindParam('date1', $in_date);
        $sql->bindParam('date2', $end_date);

        $sql->execute();
        $sql->setFetchMode(PDO::FETCH_NUM);

        $sales = $sql->fetchAll();

        $total_sales = [];

        foreach ($sales as $sale) {
            if (array_key_exists($sale[0], $total_sales)) {
                $total_sales[$sale[0]] += intval($sale[2]);
            } else {
                $total_sales[$sale[0]] = $sale[2];
            }
        }
    } catch (PDOException $e) {
        echo '<br /> ERROR: ' . $e->getMessage();
    }

    return $total_sales;
}

function getSalesTwoDates($conn, $customer_code, $in_date, $end_date)
{
    if ($in_date != '' && $end_date != '') {
        try {
            $sql = $conn->prepare("SELECT paymentDate, amount FROM payments WHERE customerNumber = :customer_code AND paymentDate between :in_date AND :end_date order by paymentDate");

            $sql->bindParam('customer_code', $customer_code);
            $sql->bindParam('in_date', $in_date);
            $sql->bindParam('end_date', $end_date);

            $sql->execute();
            $sql->setFetchMode(PDO::FETCH_NUM);

            $order = $sql->fetchAll();

            $total = [];
            $total[0][0] = '';
            $total[0][1] = 0;

            foreach ($order as $values) {
                $total[0][1] += (float)$values[1];
            }

            return [$order, $total];
        } catch (PDOException $e) {
            echo '<br /> ERROR: ' . $e->getMessage();
        }
    } else {
        try {
            $sql = $conn->prepare("SELECT sum(amount) FROM payments where customerNumber = :customer_code");

            $sql->bindParam('customer_code', $customer_code);

            $sql->execute();
            $sql->setFetchMode(PDO::FETCH_NUM);

            return $total = $sql->fetchAll();
        } catch (PDOException $e) {
            echo '<br /> ERROR: ' . $e->getMessage();
        }
    }
}

// ------------------------------------