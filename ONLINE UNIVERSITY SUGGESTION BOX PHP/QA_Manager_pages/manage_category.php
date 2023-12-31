<?php
require_once '../includes/header.php';
require_once '../includes/nav_qa_manager.php';
$value = view_category();
?>

<h1 class="heading_padding">Category</h1>
<br>
<div class="card shadow mb-4">
  <div class="card-body">
    <div class="table-responsive">
      <!-- Table to manage categories -->
      <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
        <thead>
          <tr>
            <!-- Table headings -->
            <th>Category_ID</th>
            <th>Category_Name</th>
            <th class="text-center">Operations</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <?php
            while ($row = mysqli_fetch_assoc($value)) {
              ?>
              <td>
                <?php echo $row['CATEGORY_ID']; ?>
              </td>
              <td>
                <?php echo $row['CATEGORY_NAME']; ?>
              </td>
              <td class="text-center">

                <a href="delete_category.php?id=<?php echo $row['CATEGORY_ID'] ?>" class="btn btn-danger"
                  name="delete_category">Delete</a>
              </td>
            </tr>
          <?php
            }
            ?>

        </tbody>

      </table>
      <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#myModal">
        Add new category
      </button>
    </div>

    <!-- The Modal to add category -->
    <div class="modal" id="myModal">
      <div class="modal-dialog">
        <div class="modal-content">

          <!-- Modal Header -->
          <div class="modal-header">
            <h4 class="modal-title">Add category</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>

          <!-- Modal body -->
          <div class="modal-body">
            <form action="manage_category.php" method="post">
              <div class="mb-3">
                <input type="text" class="form-control" id="add_cat" placeholder="Category name" name="addCat">
              </div>
              <input id="btn" type="submit" value="Add category" class="btn btn-primary" name="addNewCategory">
              <?php if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['addNewCategory'])) {
                global $connection;
                $category_name = $_POST['addCat'];
                $statement = $connection->prepare("select * from idea_category where CATEGORY_ID = ?");
                $statement->bind_param("s", $category_name);
                $statement->execute();
                $statement->store_result();

                if ($statement->num_rows == 0) {
                  $statement2 = $connection->prepare("insert into idea_category(CATEGORY_NAME) values (?)");
                  $statement2->bind_param('s', $category_name);
                  $statement2->execute();
                  header("Location: manage_category.php");
                  $statement2->close();
                } else {

                }
                $statement->free_result();
                $statement->close();
              } ?>

            </form>
          </div>


        </div>
      </div>
    </div>

  </div>
</div>

<?php require_once '../includes/footer.php' ?>