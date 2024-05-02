<?php

// Include the database connection file
include '../components/connect.php';

// Start the session
session_start();

// Check if the 'submit' button has been clicked
if(isset($_POST['submit'])){

   // Sanitize and store the username and password inputs
   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $pass = sha1($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);

   // Prepare a SELECT statement to check if the username and password match a record in the 'admins' table
   $select_admin = $conn->prepare("SELECT * FROM `admins` WHERE name = ? AND password = ?");
   $select_admin->execute([$name, $pass]);

   // Fetch the matching record as an associative array
   $row = $select_admin->fetch(PDO::FETCH_ASSOC);

   // Check if a record was found
   if($select_admin->rowCount() > 0){
      // If a record was found, set the 'admin_id' session variable and redirect to the dashboard page
      $_SESSION['admin_id'] = $row['id'];
      header('location:dashboard.php');
   }else{
      // If no record was found, set an error message
      $message[] = 'incorrect username or password!';
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Login</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php
   // Check if the 'message' array is set and loop through it to display any error messages
   if(isset($message)){
      foreach($message as $message){
         echo '
         <div class="message">
            <span>'.$message.'</span>
            <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
         </div>
         ';
      }
   }
?>

<section class="form-container">

   <form action="" method="post">
      <h3>Login now</h3>
      <p>Default username = <span>admin</span> & password = <span>111</span></p>
      <input type="text" name="name" required placeholder="enter your username" maxlength="20"  class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="pass" required placeholder="enter your password" maxlength="20"  class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="submit" value="login now" class="btn" name="submit">
   </form>

</section>
   
</body>
</html>