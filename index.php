<?php

session_start();

if (!isset($_SESSION['username']) && !isset($_SESSION['fullName']) && !isset($_SESSION['cart'])) {
    header('Location: login.php');
    exit(0);
}

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:ital,wght@0,300;0,400;0,700;1,300;1,400;1,700&family=Roboto:ital,wght@0,400;0,500;0,700;0,900;1,400&display=swap"
      rel="stylesheet"
    />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css"
      integrity="sha512-1PKOgIY59xJ8Co8+NE6FZ+LOAZKjy+KY8iq0G4B3CyeY6wYHN3yt9PW0XpSriVlkMXe40PTKnXrLnZ9+fkDaog=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer"
    />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="./public/css/reset.css" />
    <link
      rel="stylesheet"
      href="./public/owlcarousel/assets/owl.carousel.min.css"
    />
    <link
      rel="stylesheet"
      href="./public/owlcarousel/assets/owl.theme.default.min.css"
    />
    <link rel="stylesheet" href="./public/css/global.css" />
    <link rel="stylesheet" href="./public/css/header.css" />
    <link rel="stylesheet" href="./public/css/main.css" />
    <link rel="stylesheet" href="./public/css/category-news.css" />
    <link rel="stylesheet" href="./public/css/footer.css" />
    <title>Vietsoz Clothes</title>
    <script
      src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"
      integrity="sha512-aVKKRRi/Q/YV+4mjoKBsE4x3H+BkegoM/em46NNlCqNTmUYADjBbeNefNxYV7giUp0VxICtqdrbqU7iVaeZNXA=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer"
    ></script>
    <script src="./public/owlcarousel/owl.carousel.min.js"></script>
    <script src="./public/js/main.js"></script>
    <script>
      $(document).ready(function(){
          $(document).on('click', '.delete-cart-item', function(event){
              event.preventDefault();
              var itemIndex = $(this).data('index');

              $.ajax({
                  url: 'delete_from_cart.php',
                  method: 'POST',
                  data: { index: itemIndex },
                  dataType: 'json',
                  success: function(response){
                      // Update cart count
                      $('.num-cart').text(response.cart_count);

                      // Update cart view
                      updateCartDisplay(response.cart_items);
                  },
                  error: function(){
                      alert('Error deleting from cart');
                  }
              });
          });
          $('#see-all').click(function(event){
              event.preventDefault();
              $.ajax({
                  url: 'fetch_more_products.php',
                  method: 'GET',
                  dataType: 'json',
                  success: function(response){
                      // Loop through the response and append each product to the list
                      response.forEach(function(product) {
                          $('#product-list').append(
                              `<li class="item">
                                  <a href="" class="pic-product">
                                      <img src="${product.image}" alt="${product.name}" />
                                  </a>
                                  <a href="" class="name-product">${product.name}</a>
                                  <div class="price">
                                      <span class="new-price">${product.new_price}</span>
                                      <span class="old-price">${product.old_price}</span>
                                  </div>
                                  <a href="" class="add-cart" data-name="${product.name}" data-price="${product.price_value}" data-image="${product.image}">Thêm vào giỏ hàng</a>
                              </li>`
                          );
                      });

                      // Hide the "Xem tất cả" button after loading all products
                      $('#see-all').hide();
                  },
                  error: function(){
                      alert('Error loading more products');
                  }
              });
          });

          // Toggle cart details
          $('#cart-button').click(function(){
              $('.cart-details').toggle();
          });

          // Hide cart details when clicking outside
          $(document).click(function(event) { 
              if(!$(event.target).closest('.cart').length) {
                  if($('.cart-details').is(":visible")) {
                      $('.cart-details').hide();
                  }
              }        
          });

          // Handle adding items to the cart
          $(document).on('click', '.add-cart', function(event){
              event.preventDefault();
              var itemName = $(this).data('name');
              var itemPrice = $(this).data('price');
              var itemImage = $(this).data('image');

              $.ajax({
                  url: 'add_to_cart.php',
                  method: 'POST',
                  data: { item_name: itemName, price: itemPrice, image: itemImage },
                  dataType: 'json', // Update to expect JSON response
                  success: function(response){
                      // Update cart count
                      $('.num-cart').text(response.cart_count);

                      // Update cart view
                      updateCartDisplay(response.cart_items);
                  },
                  error: function(){
                      alert('Error adding to cart');
                  }
              });
          });

          function updateCartDisplay(cartItems) {
              var $cartDetails = $('.cart-details ul');
              $cartDetails.empty();

              cartItems.forEach(function(item, index) {
                  var listItem = $('<li>').addClass('cart-item');
                  
                  var img = $('<img>').attr('src', item.image).attr('alt', item.item_name).addClass('cart-item-img');
                  var itemName = $('<span>').addClass('cart-item-name').text(item.item_name);
                  var itemPriceText = isNaN(item.price) ? 'N/A' : Number(item.price).toLocaleString() + 'đ';
                  var itemPrice = $('<span>').addClass('cart-item-price').text(itemPriceText);
                  var deleteButton = $('<button>').css({'margin-left': '10px'}).addClass('btn btn-danger btn-sm delete-cart-item').attr('data-index', index).text('Xóa');

                  listItem.append(img, itemName, itemPrice, deleteButton);
                  $cartDetails.append(listItem);
              });

              var $cartFooter = $('.cart-footer .cart-total');
              $cartFooter.text('Thêm ' + cartItems.length + ' Hàng Vào Giỏ');
          }
      });
    </script>

    <style>
        .cart-details {
            display: none;
            position: absolute;
            background-color: #1a1a1a; /* Dark background */
            color: #ffffff; /* White text */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 10px;
            border-radius: 5px;
            width: 350px; /* Adjust as needed */
            top: 100px; /* Adjust this value to move the cart down */
            right: 10px;
            z-index: 10; /* Ensure it appears on top */
            font-family: Arial, sans-serif; /* Add a clean font */
        }

        .cart-details .cart-header {
            background-color: #ff4500; /* Orange header */
            padding: 10px;
            font-weight: bold;
            font-size: 16px;
            border-bottom: 1px solid #888;
            border-radius: 5px 5px 0 0; /* Rounded corners for the header */
        }

        .cart-details ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
            max-height: 300px; /* Adjust as needed */
            overflow-y: auto; /* Scroll if too many items */
        }

        .cart-details li.cart-item {
            display: flex;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #333;
        }

        .cart-details li.cart-item:last-child {
            border-bottom: none;
        }

        .cart-details .cart-item-img {
            width: 50px; /* Adjust as needed */
            height: 50px; /* Adjust as needed */
            margin-right: 10px;
            border-radius: 3px;
            object-fit: cover; /* Ensures image fits within the dimensions */
            background-color: #eee; /* Light background for missing image */
        }

        .cart-details .cart-item-name {
            flex: 1;
            font-size: 14px;
            color: #ffffff; /* White text for item name */
        }

        .cart-details .cart-item-price {
            font-size: 14px;
            color: #ff4500; /* Orange color for price */
        }

        .cart-details .cart-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 10px;
        }

        .cart-details .cart-total {
            font-size: 14px;
        }

        .cart-details .cart-view-btn {
            background-color: #ff4500; /* Orange button */
            border: none;
            padding: 5px 10px;
            color: #fff;
            font-size: 14px;
            border-radius: 3px;
            cursor: pointer;
        }

        .cart-details .cart-view-btn:hover {
            background-color: #e63e00; /* Darker orange on hover */
        }
    </style>

  </head>
  <body>
    <header class="">
      <div class="container">
        <div class="logo">
          <a href="index.php" class="logo-header"
            ><img src="./public/images/logo_header.png" alt="" />
          </a>
        </div>
        <nav>
          <ul id="main-menu">
            <li><a href="">Trang chủ</a></li>
            <li><a href="">Về chúng tôi</a></li>
            <li>
              <a href="">Sản phẩm <i class="fas fa-chevron-down fa-xs"></i></a>
              <ul class="sub-menu">
                <li><a href="">Quần jean</a></li>
                <li><a href="">Áo sơ mi</a></li>
                <li><a href="">Váy ngắn</a></li>
                <li><a href="">Váy thun</a></li>
              </ul>
            </li>
            <li><a href="">Xu hướng</a></li>
            <li><a href="">Thanh toán</a></li>
            <li><a href="">Liên hệ</a></li>
          </ul>
        </nav>
        <form action="#" id="search">
          <input type="search" name="search" id="search-home" />
        </form>
        <div class="cart">
          <button id="cart-button" type="button">
            <i class="fas fa-shopping-cart"></i>
            <span class="num-cart"><?php echo count($_SESSION['cart']); ?></span>
          </button>
        </div>
        <div class="cart-details">
          <div class="cart-header">Sản Phẩm Mới Thêm</div>
          <ul>
              <?php foreach ($_SESSION['cart'] as $index => $item): ?>
                  <li class="cart-item">
                      <img src="<?php echo htmlspecialchars($item['image'] ?? './public/images/cart.png'); ?>" alt="<?php echo htmlspecialchars($item['item_name'] ?? ''); ?>" class="cart-item-img">
                      <span class="cart-item-name"><?php echo htmlspecialchars($item['item_name'] ?? 'N/A'); ?></span>
                      <span class="cart-item-price">
                          <?php
                          if (isset($item['price']) && is_numeric($item['price'])) {
                              echo number_format($item['price']) . 'đ';
                          } else {
                              echo 'N/A';
                          }
                          ?>
                      </span>
                      <button class="btn btn-danger btn-sm delete-cart-item" style="margin-left: 10px;" data-index="<?php echo $index; ?>">Xóa</button>
                  </li>
              <?php endforeach; ?>
          </ul>
          <div class="cart-footer">
              <span class="cart-total">Thêm <?php echo count($_SESSION['cart']); ?> Hàng Vào Giỏ</span>
              <button class="btn btn-primary cart-view-btn"><i class="fas fa-arrow-left"></i> Trở về</button>
          </div>
      </div>
      </div>
    </header>
    <div id="banner"></div>
    <div id="introduce">
      <div class="container">
        <ul class="list-introduce">
          <li>
            <a href=""
              >Freeship nội thành<span class="icon"
                ><i class="fas fa-shopping-cart"></i></span
            ></a>
          </li>
          <li>
            <a href=""
              >Tặng thẻ thành viên<span class="icon"
                ><i class="fas fa-gift"></i></span
            ></a>
          </li>
          <li>
            <a href=""
              >Giảm giá 25% cuối tuần<span class="icon"
                ><i class="fas fa-paper-plane"></i></span
            ></a>
          </li>
        </ul>
      </div>
    </div>
    <div id="featured-product">
      <div class="container">
        <div class="box-head">
          <h3>Sản phẩm nội bật</h3>
        </div>
        <div class="box-body">
          <div id="slide-top" class="owl-carousel owl-theme">
            <?php include_once 'populate_slider.php'; ?>
          </div>
        </div>
      </div>
    </div>
    <div id="autumn-spring-fashion">
      <div class="container">
        <div class="box-head">
          <h3>Thời trang thu đông</h3>
        </div>
        <div class="box-body">
          <ul class="list-product" id="product-list">
            <?php include_once 'populate_product.php'; ?>
          </ul>
        </div>
        <button id="see-all" class="see-all btn btn-primary mt-2">Xem tất cả</button>
      </div>
    </div>
    <div id="trend">
      <div class="container">
        <div class="box-head">
          <h3>Xu hướng</h3>
        </div>
        <div class="box-body">
          <div
            class="owl-carousel owl-theme owl-loaded owl-drag"
            id="list-post"
          >
            <div class="item">
              <a href="" class="post-thumb">
                <img src="./public/images/pic_1.png" alt="" />
              </a>
              <a href="" class="post-title"
                >Phong cách thời trang cơ bản mà bạn nên biết?</a
              >
              <p class="post-desc">
                Lorem Ipsum chỉ đơn giản là một đoạn văn bản giả, được dùng vào
                việc trình bày và dàn trang phục vụ cho in ấn. Lorem Ipsum đã
                được sử dụng như một văn bản chuẩn cho ngành công nghiệp in ấn
                từ những năm 1500...
              </p>
            </div>
            <div class="item">
              <a href="" class="post-thumb">
                <img src="./public/images/pic_2.png" alt="" />
              </a>
              <a href="" class="post-title"
                >Phong cách thời trang cơ bản mà bạn nên biết?</a
              >
              <p class="post-desc">
                Lorem Ipsum chỉ đơn giản là một đoạn văn bản giả, được dùng vào
                việc trình bày và dàn trang phục vụ cho in ấn. Lorem Ipsum đã
                được sử dụng như một văn bản chuẩn cho ngành công nghiệp in ấn
                từ những năm 1500...
              </p>
            </div>
            <div class="item">
              <a href="" class="post-thumb">
                <img src="./public/images/pic_3.png" alt="" />
              </a>
              <a href="" class="post-title"
                >Phong cách thời trang cơ bản mà bạn nên biết?</a
              >
              <p class="post-desc">
                Lorem Ipsum chỉ đơn giản là một đoạn văn bản giả, được dùng vào
                việc trình bày và dàn trang phục vụ cho in ấn. Lorem Ipsum đã
                được sử dụng như một văn bản chuẩn cho ngành công nghiệp in ấn
                từ những năm 1500...
              </p>
            </div>
            <div class="item">
              <a href="" class="post-thumb">
                <img src="./public/images/pic_1.png" alt="" />
              </a>
              <a href="" class="post-title"
                >Phong cách thời trang cơ bản mà bạn nên biết?</a
              >
              <p class="post-desc">
                Lorem Ipsum chỉ đơn giản là một đoạn văn bản giả, được dùng vào
                việc trình bày và dàn trang phục vụ cho in ấn. Lorem Ipsum đã
                được sử dụng như một văn bản chuẩn cho ngành công nghiệp in ấn
                từ những năm 1500...
              </p>
            </div>
            <div class="item">
              <a href="" class="post-thumb">
                <img src="./public/images/pic_2.png" alt="" />
              </a>
              <a href="" class="post-title"
                >Phong cách thời trang cơ bản mà bạn nên biết?</a
              >
              <p class="post-desc">
                Lorem Ipsum chỉ đơn giản là một đoạn văn bản giả, được dùng vào
                việc trình bày và dàn trang phục vụ cho in ấn. Lorem Ipsum đã
                được sử dụng như một văn bản chuẩn cho ngành công nghiệp in ấn
                từ những năm 1500...
              </p>
            </div>
            <div class="item">
              <a href="" class="post-thumb">
                <img src="./public/images/pic_3.png" alt="" />
              </a>
              <a href="" class="post-title"
                >Phong cách thời trang cơ bản mà bạn nên biết?</a
              >
              <p class="post-desc">
                Lorem Ipsum chỉ đơn giản là một đoạn văn bản giả, được dùng vào
                việc trình bày và dàn trang phục vụ cho in ấn. Lorem Ipsum đã
                được sử dụng như một văn bản chuẩn cho ngành công nghiệp in ấn
                từ những năm 1500...
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div id="register">
      <div class="container">
        <p>ĐĂNG KÍ ĐỂ NHẬN KHUYẾN MÃI</p>
        <p>Đăng kí để nhận thông tin khuyến mãi mới nhất</p>
        <form action="" class="enter-email">
          <input
            type="email"
            name="email"
            id="email"
            placeholder="Nhập email của bạn"
            autocomplete="off"
          />
          <button type="submit">ĐĂNG KÝ</button>
        </form>
      </div>
    </div>
    <footer>
      <div class="container">
        <div class="widget logo">
          <div class="widget-head">
            <a href=""><img src="./public/images/logo_footer.png" alt="" /></a>
          </div>
          <div class="widget-body">
            <p>
              Chúng ta vẫn biết rằng, làm việc với một đoạn văn bản dễ đọc và rõ
              nghĩa dễ gây rối trí và cản trở việc tập trung vào yếu tố trình
              bày văn bản. Lorem Ipsum có ưu điểm hơn so với đoạn văn bản chỉ
              gồm nội dung kiểu "Nội dung, nội dung, nội dung" là nó khiến văn
              bản giống thật hơn, bình thường hơn.
            </p>
          </div>
        </div>
        <div class="widget category">
          <div class="widget-head">
            <h3>DANH MỤC</h3>
          </div>
          <div class="widget-body">
            <ul class="list-category-fashion">
              <li><a href="">Thời trang Châu Á</a></li>
              <li><a href="">Thời trang Châu Âu</a></li>
              <li><a href="">Thời trang Châu Mĩ </a></li>
              <li><a href="">Thời trang Châu Úc</a></li>
              <li><a href="">Thời trang Châu Phi</a></li>
            </ul>
          </div>
        </div>
        <div class="widget selling">
          <div class="widget-head">
            <h3>BÁN CHẠY</h3>
          </div>
          <div class="widget-body">
            <ul class="list-category-fashion">
              <li><a href="">Đồ bơi mùa hè</a></li>
              <li><a href="">Áo khoác nam</a></li>
              <li><a href="">Tất lưới nữ</a></li>
              <li><a href="">Áo sơ mi trẻ em</a></li>
              <li><a href="">Quần jean nữ</a></li>
            </ul>
          </div>
        </div>
        <div class="widget fanpage">
          <div class="widget-head">
            <h3>FAN PAGE</h3>
          </div>
          <div class="widget-body">
            <p>Fanpage</p>
          </div>
        </div>
      </div>
    </footer>
    <div id="copy-right">
      <p>© 2017 All rights reserved - Bản quyền thuộc về Vietsoz.com</p>
    </div>
  </body>
</html>

