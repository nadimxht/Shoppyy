<?php

// Check if the 'add_to_wishlist' form has been submitted
if(isset($_POST['add_to_wishlist'])){

   // If user is not logged in, redirect to login page
   if($user_id == ''){
      header('location:user_login.php');
   }else{
      // Sanitize and store form data in variables
      $pid = $_POST['pid'];
      $pid = filter_var($pid, FILTER_SANITIZE_STRING);
      $name = $_POST['name'];
      $name = filter_var($name, FILTER_SANITIZE_STRING);
      $price = $_POST['price'];
      $price = filter_var($price, FILTER_SANITIZE_STRING);
      $image = $_POST['image'];
      $image = filter_var($image, FILTER_SANITIZE_STRING);

      // Check if the item already exists in the user's wishlist
      $check_wishlist_numbers = $conn->prepare("SELECT * FROM `wishlist` WHERE name =? AND user_id =?");
      $check_wishlist_numbers->execute([$name, $user_id]);

      // Check if the item already exists in the user's cart
      $check_cart_numbers = $conn->prepare("SELECT * FROM `cart` WHERE name =? AND user_id =?");
      $check_cart_numbers->execute([$name, $user_id]);

      // If the item already exists in the wishlist, display an error message
      if($check_wishlist_numbers->rowCount() > 0){
         $message[] = 'already added to wishlist!';
      }
      // If the item already exists in the cart, display an error message
      elseif($check_cart_numbers->rowCount() > 0){
         $message[] = 'already added to cart!';
      }
      // If the item does not exist in the wishlist or cart, add it to the wishlist
      else{
         $insert_wishlist = $conn->prepare("INSERT INTO `wishlist`(user_id, pid, name, price, image) VALUES(?,?,?,?,?)");
         $insert_wishlist->execute([$user_id, $pid, $name, $price, $image]);
         $message[] = 'added to wishlist!';
      }

   }

}

// Check if the 'add_to_cart' form has been submitted
if(isset($_POST['add_to_cart'])){

   // If user is not logged in, redirect to login page
   if($user_id == ''){
      header('location:user_login.php');
   }else{
      // Sanitize and store form data in variables
      $pid = $_POST['pid'];
      $pid = filter_var($pid, FILTER_SANITIZE_STRING);
      $name = $_POST['name'];
      $name = filter_var($name, FILTER_SANITIZE_STRING);
      $price = $_POST['price'];
      $price = filter_var($price, FILTER_SANITIZE_STRING);
      $image = $_POST['image'];
      $image = filter_var($image, FILTER_SANITIZE_STRING);
      $qty = $_POST['qty'];
      $qty = filter_var($qty, FILTER_SANITIZE_STRING);

      // Check if the item already exists in the user's cart
      $check_cart_numbers = $conn->prepare("SELECT * FROM `cart` WHERE name =? AND user_id =?");
      $check_cart_numbers->execute([$name, $user_id]);

      // If the item already exists in the cart, display an error message
      if($check_cart_numbers->rowCount() > 0){
         $message[] = 'already added to cart!';
      }
      // If the item does not exist in the cart, add it to the cart
      else{
         // Check if the item exists in the user's wishlist
         $check_wishlist_numbers = $conn->prepare("SELECT * FROM `wishlist` WHERE name =? AND user_id =?");
         $check_wishlist_numbers->execute([$name, $user_id]);

         // If the item exists in the wishlist, remove it from the wishlist
         if($check_wishlist_numbers->rowCount() > 0){
            $delete_wishlist = $conn->prepare("DELETE FROM `wishlist` WHERE name =? AND user_id =?");
            $delete_wishlist->execute([$name, $user_id]);
         }

         // Add the item to the cart
         $insert_cart = $conn->prepare("INSERT INTO `cart`(user_id, pid, name, price, quantity, image) VALUES(?,?,?,?,?,?)");
         $insert_cart->execute([$user_id, $pid, $name, $price, $qty, $image]);
         $message[] = 'added to cart!';
         
      }

   }

}

?>