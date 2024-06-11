<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

$cart = $_SESSION['user']['cart'];
$totalPrice = 0;

// Example prices (in practice, you would get these from a database or similar)
$itemPrices = [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart Details</title>
</head>
<body>
    <h2>Your Cart</h2>
    <ul>
        <?php foreach ($cart as $item): ?>
            <li>
                <?php echo htmlspecialchars($item['item_name']); ?> - 
                <?php echo number_format($itemPrices[$item['item_name']]); ?>đ
                <?php $totalPrice += $itemPrices[$item['item_name']]; ?>
            </li>
        <?php endforeach; ?>
    </ul>
    <p>Total Price: <?php echo number_format($totalPrice); ?>đ</p>
    <button onclick="alert('Payment functionality is not implemented yet')">Payment</button>
</body>
</html>
