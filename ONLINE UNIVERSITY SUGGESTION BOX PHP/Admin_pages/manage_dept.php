<?php
require_once '../includes/header.php';
require_once '../includes/nav_admin.php';
$value = view_departments();
?>

<h1 class="heading_padding">Departments</h1>
<br>
<div class="card shadow mb-4">
  <div class="card-body">
    <div class="table-responsive">
      <!-- Table to manage depts -->

      <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
        <thead>
          <tr>
            <!-- Table headings -->
            <th>Department_ID</th>
            <th>Department_Name</th>
            <th class="text-center">Operations</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <?php
            while ($row = mysqli_fetch_assoc($value)) {
              ?>
              <td>
                <?php echo $row['DEPT_ID']; ?>
              </td>
              <td>
                <?php echo $row['DEPARTMENT_NAME']; ?>
              </td>
              <td class="text-center">

                <!-- <a href="Edit_category.php?id=<?php //echo $row['Category_ID']?>" class="btn btn-dark">Edit</a> -->
                <a href="delete_dept.php?id=<?php echo $row['DEPT_ID'] ?>" class="btn btn-danger"
                  name="delete_dept">Delete</a>
              </td>
            </tr>
          <?php
            }
            ?>

        </tbody>

      </table>
      <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#deptModal">
        Add new department
      </button>

      <!-- The Modal to add departments -->
      <div class="modal" id="deptModal">
        <div class="modal-dialog">
          <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
              <h4 class="modal-title">Add department</h4>
              <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
              <form action="manage_dept.php" method="post">
                <div class="mb-3">
                  <input type="text" class="form-control" id="add_dept" placeholder="Department name" name="addDept">
                </div>
                <input id="btn" type="submit" value="Add department" class="btn btn-primary" name="addNewDept">
                <?php if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['addNewDept'])) {
                  global $connection;
                  $dept_name = $_POST['addDept'];
                  $statement = $connection->prepare("select * from department where DEPT_ID = ?");
                  $statement->bind_param("s", $dept_name);
                  $statement->execute();
                  $statement->store_result();

                  if ($statement->num_rows == 0) {
                    $statement2 = $connection->prepare("insert into department(DEPARTMENT_NAME) values (?)");
                    $statement2->bind_param('s', $dept_name);
                    $statement2->execute();
                    header("Location: manage_dept.php");
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