<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['index'])) {
        $index = (int) $_POST['index'];

        if (isset($_SESSION['cart'][$index])) {
            // Remove item from the cart
            array_splice($_SESSION['cart'], $index, 1);

            // Return updated cart details
            echo json_encode([
                'cart_count' => count($_SESSION['cart']),
                'cart_items' => $_SESSION['cart']
            ]);
            exit;
        }
    }
}

// Return an error response if deletion fails
http_response_code(400);
echo json_encode(['error' => 'Invalid request']);
exit;
?>
