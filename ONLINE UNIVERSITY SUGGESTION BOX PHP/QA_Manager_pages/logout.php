<?php 
//Logout from session
   session_start();
   session_unset();
   session_destroy();
   header("Location: ../Common_pages/login.php");
   echo "Success";
   exit();
?>