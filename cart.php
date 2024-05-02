<?php

// Include the database connection file
include 'components/connect.php';

// Start the session
session_start();

// Check if the user's ID is set in the session
if(isset($_SESSION['user_id'])){
   // If it is, set the user's ID to a variable
   $user_id = $_SESSION['user_id'];
}else{
   // If it's not, set the user's ID to an empty string and redirect to the login page
   $user_id = '';
   header('location:user_login.php');
}

// Check if the 'delete' button has been clicked
if(isset($_POST['delete'])){
   // If it has, get the ID of the cart item to be deleted
   $cart_id = $_POST['cart_id'];
   // Prepare a DELETE statement to delete the cart item with the given ID
   $delete_cart_item = $conn->prepare("DELETE FROM `cart` WHERE id = ?");
   // Execute the statement with the given ID
   $delete_cart_item->execute([$cart_id]);
}

// Check if the 'delete_all' query parameter is set
if(isset($_GET['delete_all'])){
   // If it is, prepare a DELETE statement to delete all cart items for the current user
   $delete_cart_item = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
   // Execute the statement with the current user's ID
   $delete_cart_item->execute([$user_id]);
   // Redirect back to the cart page
   header('location:cart.php');
}

// Check if the 'update_qty' button has been clicked
if(isset($_POST['update_qty'])){
   // If it has, get the ID of the cart item and the new quantity
   $cart_id = $_POST['cart_id'];
   $qty = $_POST['qty'];
   // Sanitize the quantity input
   $qty = filter_var($qty, FILTER_SANITIZE_STRING);
   // Prepare an UPDATE statement to update the quantity of the cart item with the given ID
   $update_qty = $conn->prepare("UPDATE `cart` SET quantity = ? WHERE id = ?");
   // Execute the statement with the new quantity and the ID
   $update_qty->execute([$qty, $cart_id]);
   // Set a success message
   $message[] = 'cart quantity updated';
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Shopping Cart</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>

   <!-- include user header -->
   <?php include 'components/user_header.php'; ?>

   <section class="products shopping-cart">

      <h3 class="heading">Shopping cart</h3>

      <div class="box-container">

         <!-- loop through the cart items and display each one -->
         <?php
            $grand_total = 0;
            $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
            $select_cart->execute([$user_id]);
            if($select_cart->rowCount() > 0){
               while($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)){
         ?>
         <form action="" method="post" class="box">
            <input type="hidden" name="cart_id" value="<?= $fetch_cart['id']; ?>">
            <!-- quick view button -->
            <a href="quick_view.php?pid=<?= $fetch_cart['pid']; ?>" class="fas fa-eye"></a>
            <!-- product image -->
            <img src="uploaded_img/<?= $fetch_cart['image']; ?>" alt="">
            <!-- product name -->
            <div class="name"><?= $fetch_cart['name']; ?></div>
            <!-- flex container for price and quantity inputs -->
            <div class="flex">
               <!-- price -->
               <div class="price">DT.<?= $fetch_cart['price']; ?>/-</div>
               <!-- quantity inputs -->
               <input type="number" name="qty" class="qty" min="1" max="99" onkeypress="if(this.value.length == 2) return false;" value="<?= $fetch_cart['quantity']; ?>">
               <!-- update quantity button -->
               <button type="submit" class="fas fa-edit" name="update_qty"></button>
            </div>
            <!-- subtotal -->
            <div class="sub-total"> Sub Total : <span>$<?= $sub_total = ($fetch_cart['price'] * $fetch_cart['quantity']); ?>/-</span> </div>
            <!-- delete item button -->
            <input type="submit" value="delete item" onclick="return confirm('delete this from cart?');" class="delete-btn" name="delete">
         </form>
         <?php
         $grand_total += $sub_total;
               }
            }else{
               echo '<p class="empty">your cart is empty</p>';
            }
         ?>

      </div>

      <!-- cart total container -->
      <div class="cart-total">
         <!-- grand total -->
         <p>Grand Total : <span>DT.<?= $grand_total; ?>/-</span></p>
         <!-- continue shopping button -->
         <a href="shop.php" class="option-btn">Continue Shopping.</a>
         <!-- delete all items button -->
         <a href="cart.php?delete_all" class="delete-btn <?= ($grand_total > 1)?'':'disabled'; ?>" onclick="return confirm('delete all from cart?');">Delete All Items ?</a>
         <!-- proceed to checkout button -->
         <a href="checkout.php" class="btn <?= ($grand_total > 1)?'':'disabled'; ?>">Proceed to Checkout.</a>
      </div>

   </section>

   <!-- include footer -->
   <?php include 'components/footer.php'; ?>

   <!-- custom js file link -->
   <script src="js/script.js"></script>

</body>
</html>