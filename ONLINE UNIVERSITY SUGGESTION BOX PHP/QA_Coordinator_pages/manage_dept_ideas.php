<?php
require_once '../includes/header.php';
require_once '../includes/nav_qa_coordinator.php';

$value = view_dept_ideas($_SESSION['department']);
?>

<h1 class="heading_padding">Department Ideas</h1>
<br>
<div class="card shadow mb-4">
  <div class="card-body">
    <div class="table-responsive">
      <!-- Table to display ideas submitted per department -->
      <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
        <thead>
          <tr>
            <!-- Table headings -->
            <th>User_ID</th>
            <th>Idea Title</th>
            <th>Idea Description</th>
            <th class="text-center">Operations</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <?php
            //Ideas submitted per department from database query
            while ($row = mysqli_fetch_assoc($value)) {
              ?>
              <td>
                <?php echo $row['USER_ID']; ?>
              </td>
              <td>
                <?php echo $row['IDEA_TITLE']; ?>
              </td>
              <td>
                <?php echo $row['IDEA_DESCRIPTION']; ?>
              </td>
              <td class="text-center">
                <a href="delete_dept_idea.php?id=<?php echo $row['IDEA_ID'] ?>" class="btn btn-danger"
                  name="delete_dept_idea">Delete</a>
              </td>
            </tr>
          <?php
            }
            ?>
        </tbody>
      </table>
    </div>

  </div>
</div>


<?php
require_once '../includes/footer.php'
  ?>