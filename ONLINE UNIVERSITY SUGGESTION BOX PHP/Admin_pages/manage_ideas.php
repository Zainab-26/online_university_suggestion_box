<?php
require_once '../includes/header.php';
require_once '../includes/nav_admin.php';
$value = view_all_ideas();
?>

<h1 class="heading_padding">Category</h1>
<br>
<div class="card shadow mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <!-- Table to manage ideas -->
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <!-- Table headings -->
                        <th>Idea Title</th>
                        <th>Idea Description</th>
                        <th class="text-center">Operations</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <?php
                        while ($row = mysqli_fetch_assoc($value)) {
                            ?>
                            <td>
                                <?php echo $row['IDEA_TITLE']; ?>
                            </td>
                            <td>
                                <?php echo $row['IDEA_DESCRIPTION']; ?>
                            </td>
                            <td class="text-center">
                                <a href="delete_idea.php?id=<?php echo $row['IDEA_ID'] ?>" class="btn btn-danger"
                                    name="delete_idea">Delete</a>
                            </td>
                        </tr>
                    <?php
                        }
                        ?>

                </tbody>

            </table>
        </div>
    </div>

    <?php require_once '../includes/footer.php' ?>