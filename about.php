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
   // If it's not, set the user's ID to an empty string
   $user_id = '';
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>About</title>

   <!-- Swiper CSS link -->
   <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />

   <!-- Font Awesome CDN link -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- Custom CSS file link -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>

   <!-- Include the user header file -->
   <?php include 'components/user_header.php'; ?>

   <!-- About section -->
   <section class="about">

      <div class="row">

         <!-- Image column -->
         <div class="image">
            <img src="images/23.png" alt="">
         </div>

         <!-- Content column -->
         <div class="content">
            <h3>Developer's Message:</h3>
            <p>Hey There ! We are Youssef gaddes, Nadim chihaoui and Youssef chaouch. Students of ISI in Computer science Department from tunis . we love designing websites and exploring new things</p>

            <p>We would like to thank <a href="https://www.facebook.com/er.ashokbasnet" target="_blank">mr.moezbenreguauia</a>  </p>
            <a href="contact.php" class="btn">Contact Us</a>
         </div>

      </div>

   </section>

   <!-- Include the footer file -->
   <?php include 'components/footer.php'; ?>

   <!-- Swiper JS link -->
   <script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>

   <!-- Custom JS file link -->
   <script src="js/script.js"></script>

   <!-- Swiper JS code -->
   <script>
      var swiper = new Swiper(".reviews-slider", {
         loop:true,
         spaceBetween: 20,
         pagination: {
            el: ".swiper-pagination",
            clickable:true,
         },
         breakpoints: {
            // When the screen width is less than or equal to 0 pixels
            0: {
               slidesPerView:1,
            },
            // When the screen width is less than or equal to 768 pixels
            768: {
               slidesPerView: 2,
            },
            // When the screen width is less than or equal to 991 pixels
            991: {
               slidesPerView: 3,
            },
         }
      });
   </script>

</body>
</html>