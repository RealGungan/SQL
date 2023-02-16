<?php

// ------------------ CART COOKIE ------------------
function generateCartCookie($product, $quantity)
{
    $shopping_cart[$product] = $quantity;

    $shopping_cart = serialize($shopping_cart);

    setcookie('cart', $shopping_cart, time() + (86400 * 30), "/");
}

function addProductCookie($product, $quantity)
{
    $shopping_cart = unserialize($_COOKIE['cart']);

    if (array_key_exists($product, $shopping_cart)) {
        $shopping_cart[$product] += $quantity;
    } else {
        $shopping_cart[$product] = $quantity;
    }

    $shopping_cart = serialize($shopping_cart);

    setcookie('cart', $shopping_cart, time() + (86400 * 30), "/");
}

function deleteCartCookie()
{
    if (isset($_COOKIE['cart']))
        setcookie('cart', "", -1, "/");
}
//------------------------------------