<?php require_once '../includes/header.php' ?>
<?php
    //Get Dept id from URL
     if(isset($_GET['id']))
     {
        //If an id is found, delete corresponding dept
         $id_to_delete = $_GET['id'];
         $statement = $connection->prepare("DELETE FROM department WHERE DEPT_ID= ?");
         $statement->bind_param('i',$id_to_delete);
         $statement->execute(); 
         $statement->close();
         header("location:manage_dept.php");
     }
?>
<?php require_once '../includes/footer.php' ?>