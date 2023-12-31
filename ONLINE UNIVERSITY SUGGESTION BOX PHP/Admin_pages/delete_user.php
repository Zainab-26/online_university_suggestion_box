<?php require_once '../includes/header.php' ?>
<?php
    //Get user id from URL
     if(isset($_GET['id']))
     {
        //If an id is found, delete corresponding user
         $id_to_delete = $_GET['id'];
         $statement = $connection->prepare("DELETE FROM users WHERE USER_ID= ?");
         $statement->bind_param('i',$id_to_delete);
         $statement->execute(); 
         $statement->close();
         header("location:manage_users.php");

     }

?>
<?php require_once '../includes/footer.php' ?>