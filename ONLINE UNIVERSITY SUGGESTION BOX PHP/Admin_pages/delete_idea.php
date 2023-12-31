<?php require_once '../includes/header.php' ?>
<?php
    //Get Idea id from URL
     if(isset($_GET['id']))
     {
        //If an id is found, delete corresponding idea
         $id_to_delete = $_GET['id'];
         $statement = $connection->prepare("DELETE FROM idea WHERE IDEA_ID= ?");
         $statement->bind_param('i',$id_to_delete);
         $statement->execute(); 
         $statement->close();
         header("location:manage_ideas.php");
     }

?>
<?php require_once '../includes/footer.php' ?>