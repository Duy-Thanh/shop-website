<?php
// Read the JSON file
$json_data = file_get_contents('data_product.json');
$products = json_decode($json_data, true);

foreach ($products as $product) {
    echo '<div class="item">';
    echo '<a href="" class="pic-product">';
    echo '<img src="' . $product['image'] . '" alt="" />';
    echo '</a>';
    echo '<a href="" class="name-product">' . $product['name'] . '</a>';
    echo '<div class="price">';
    echo '<span class="new-price" data-price="' . $product['price_value'] . '">' . $product['new_price'] . '</span>';
    echo '<span class="old-price">' . $product['old_price'] . '</span>';
    echo '</div>';
    echo '<a href="#" class="add-cart" data-name="' . $product['name'] . '" data-price="' . $product['price_value'] . '" data-image="' . $product['image'] . '">Thêm vào giỏ hàng</a>';
    echo '</div>';
}
?>
