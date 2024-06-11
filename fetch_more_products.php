<?php
$data = file_get_contents('data_product.json');
$products = json_decode($data, true);

// Send back the entire array as JSON for simplicity
echo json_encode($products);
?>