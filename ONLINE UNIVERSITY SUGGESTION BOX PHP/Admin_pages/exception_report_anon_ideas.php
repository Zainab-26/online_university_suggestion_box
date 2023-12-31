<?php
require_once '../includes/header.php';
require_once '../includes/nav_admin.php';
$values = anonymousIdeas();
?>

<h1 class="heading_padding">Anonymous ideas</h1>
<br>
<div class="card shadow mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <!-- Table to view anonymous ideas -->
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <!-- Table headings -->
                        <th>Idea Title</th>
                        <th class="text-center">Idea Description</th>

                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <?php
                        //Anonymous ideas from the database and display in table cells
                        while ($row = mysqli_fetch_assoc($values)) {
                            ?>
                            <td>
                                <?php echo $row['IDEA_TITLE']; ?>
                            </td>
                            <td>
                                <?php echo $row['IDEA_DESCRIPTION']; ?>
                            </td>
                        </tr>
                    <?php
                        }
                        ?>

                </tbody>

            </table>
            <?php
            require_once '../includes/footer.php';
            ?>