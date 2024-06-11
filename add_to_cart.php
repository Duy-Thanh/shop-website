<?php
session_start();

// Initialize cart if not already initialized
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Get the product details from the request
if (isset($_POST['item_name']) && isset($_POST['price']) && isset($_POST['image'])) {
    $item = [
        'item_name' => $_POST['item_name'],
        'price' => $_POST['price'],
        'image' => $_POST['image'],
    ];

    // Add the item to the cart
    $_SESSION['cart'][] = $item;

    $cartData = [
        'cart_count' => count($_SESSION['cart']),
        'cart_items' => $_SESSION['cart']
    ];

    // Encode the cart data as JSON and return it
    echo json_encode($cartData);
}
?>