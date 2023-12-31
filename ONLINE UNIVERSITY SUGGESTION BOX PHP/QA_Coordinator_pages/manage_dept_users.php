<?php
require_once '../includes/header.php';
require_once '../includes/nav_qa_coordinator.php';

$value = view_dept_users($_SESSION['department']);
?>

<h1 class="heading_padding">Department Users</h1>
<br>
<div class="card shadow mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <!-- Table to display user per department -->
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <!-- Table headings -->
                        <th>User_ID</th>
                        <th>First_name</th>
                        <th>Last_name</th>
                        <th>Email Address</th>
                        <th>Encrypted_password</th>
                        <th>Role</th>
                        <th>Date_registered</th>
                        <th>Department</th>
                        <th class="text-center">Operations</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <?php
                        //Users per department from database query
                        while ($row = mysqli_fetch_assoc($value)) {
                            ?>
                            <td>
                                <?php echo $row['USER_ID']; ?>
                            </td>
                            <td>
                                <?php echo $row['FIRST_NAME']; ?>
                            </td>
                            <td>
                                <?php echo $row['LAST_NAME']; ?>
                            </td>
                            <td>
                                <?php echo $row['EMAIL']; ?>
                            </td>
                            <td>
                                <?php echo $row['PASSWORD']; ?>
                            </td>
                            <td>
                                <?php echo $row['ROLE_NAME']; ?>
                            </td>
                            <td>
                                <?php echo $row['DATE_REGISTERED']; ?>
                            </td>
                            <td>
                                <?php echo $row['DEPT_ID']; ?>
                            </td>
                            <td class="text-center">

                                <a href="delete_dept_user.php?id=<?php echo $row['USER_ID'] ?>" class="btn btn-danger"
                                    name="delete_dept_users">Delete</a>
                            </td>
                        </tr>
                    <?php
                        }
                        ?>

                </tbody>

            </table>
        </div>
    </div>


    <?php
    require_once '../includes/footer.php'
        ?>