<?php require_once '../includes/header.php' ?>
<?php
//Get ID from URL
     if(isset($_GET['id']))
     {
        //Delete record based on ID
         $id_to_delete = $_GET['id'];
         $statement = $connection->prepare("DELETE FROM idea WHERE IDEA_ID= ?");
         $statement->bind_param('i',$id_to_delete);
         $statement->execute(); 
         $statement->close();
         header("location:manage_ideas_qa_manager.php");
     }
?>
<?php require_once '../includes/footer.php' ?>