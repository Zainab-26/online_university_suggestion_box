<?php require_once '../includes/header.php' ?>
<?php

//Get category id from URL
if (isset($_GET['id'])) {
    $id_to_delete = $_GET['id'];

    // Check if ideas exist for the category
    $statement = $connection->prepare("SELECT COUNT(*) FROM idea WHERE CATEGORY_ID = ?");
    $statement->bind_param('i', $id_to_delete);
    $statement->execute();
    $result = $statement->get_result();
    $row = $result->fetch_assoc();
    $idea_count = $row['COUNT(*)'];

    $statement->close();

    if ($idea_count == 0) {
        // If no ideas exist, delete the category
        $delete_statement = $connection->prepare("DELETE FROM idea_category WHERE CATEGORY_ID = ?");
        $delete_statement->bind_param('i', $id_to_delete);
        $delete_statement->execute();
        $delete_statement->close();
        header("location: manage_category_admin.php");
        exit;
    } else {

        // If ideas exist, do not delete and display message to user
        echo "Cannot delete the category as ideas exist.";
    }
}

?>
<?php require_once '../includes/footer.php' ?>