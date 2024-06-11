<?php
// Read JSON file
$jsonData = file_get_contents('data_product.json');

// Decode JSON data into an associative array
$products = json_decode($jsonData, true);

// Limit the number of products to 10
$limitedProducts = array_slice($products, 0, 8);

// Output the product list
foreach ($limitedProducts as $product) {
    echo '<li class="item">';
    echo '<a href="" class="pic-product">';
    echo '<img src="' . $product['image'] . '" alt="" />';
    echo '</a>';
    echo '<a href="" class="name-product">' . $product['name'] . '</a>';
    echo '<div class="price">';
    echo '<span class="new-price">' . $product['new_price'] . '</span>';
    echo '<span class="old-price">' . $product['old_price'] . '</span>';
    echo '</div>';
    echo '<a href="" class="add-cart" data-name="' . $product['name'] . '" data-price="' . $product['price_value'] . '" data-image="' . $product['image'] . '">Thêm vào giỏ hàng</a>';
    echo '</li>';
}
?>
