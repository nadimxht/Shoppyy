<?php

// Include the database connection file
include '../components/connect.php';

// Start the session
session_start();

// Check if the admin's ID is set in the session
$admin_id = $_SESSION['admin_id'];

// If the admin's ID is not set, redirect to the admin login page
if(!isset($admin_id)){
   header('location:admin_login.php');
}

// Check if the 'delete' query parameter is set
if(isset($_GET['delete'])){
   // If it is, get the ID of the admin to be deleted
   $delete_id = $_GET['delete'];
   // Prepare a DELETE statement to delete the admin with the given ID
   $delete_admins = $conn->prepare("DELETE FROM `admins` WHERE id =?");
   // Execute the statement with the given ID
   $delete_admins->execute([$delete_id]);
   // Redirect back to the admin accounts page
   header('location:admin_accounts.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Admin Accounts</title>

   <!-- Font Awesome CDN link -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- Custom CSS file link -->
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<!-- Include the admin header file -->
<?php include '../components/admin_header.php';?>

<section class="accounts">

   <h1 class="heading">Admin Accounts</h1>

   <div class="box-container">

   <!-- Loop through the admin accounts and display each one -->
   <?php
      $select_accounts = $conn->prepare("SELECT * FROM `admins`");
      $select_accounts->execute();
      if($select_accounts->rowCount() > 0){
         while($fetch_accounts = $select_accounts->fetch(PDO::FETCH_ASSOC)){   
  ?>
   <div class="box">
      <p> Admin Id : <span><?= $fetch_accounts['id'];?></span> </p>
      <p> Admin name : <span><?= $fetch_accounts['name'];?></span> </p>
      <div class="flex-btn">
         <!-- Link to delete the admin account -->
         <a href="admin_accounts.php?delete=<?= $fetch_accounts['id'];?>" onclick="return confirm('delete this account?')" class="delete-btn">delete</a>
         <!-- Link to update the admin's profile -->
         <?php
            if($fetch_accounts['id'] == $admin_id){
               echo '<a href="update_profile.php" class="option-btn">update</a>';
            }
        ?>
      </div>
   </div>
   <?php
         }
      }else{
         echo '<p class="empty">no accounts available!</p>';
      }
  ?>

   </div>

</section>

<!-- Include the footer file -->
<?php include '../components/footer.php';?>

<!-- Custom JS file link -->
<script src="../js/admin_script.js"></script>

</body>
</html>