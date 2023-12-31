<?php
require_once '../includes/header.php';
require_once '../includes/nav_qa_manager.php';
$value = view_all_ideas();
?>

<h1 class="heading_padding">Ideas</h1>
<br>

<div class="card shadow mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <!-- Table to manage ideas -->
            <table class="table table-bordered table-responsive-xl" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <!-- Table headings -->
                        <th>Idea_ID</th>
                        <th>User_ID</th>
                        <th>Category_ID</th>
                        <th>Department_ID</th>
                        <th>Idea Title</th>
                        <th>Idea Description</th>
                        <th>File</th>
                        <th>Is_anonymous</th>
                        <th>Date_posted</th>
                        <th>Vote_count</th>
                        <th>Average_rating</th>
                        <th>View_count</th>
                        <th class="text-center">Operations</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($value)) { ?>
                        <tr>
                            <td id="rows"><?php echo $row['IDEA_ID']; ?></td>
                            <td id="rows"><?php echo $row['USER_ID']; ?></td>
                            <td id="rows"><?php echo $row['CATEGORY_ID']; ?></td>
                            <td id="rows"><?php echo $row['DEPT_ID']; ?></td>
                            <td id="rows"><?php echo $row['IDEA_TITLE']; ?></td>
                            <td id="rows"><?php echo $row['IDEA_DESCRIPTION']; ?></td>
                            <td id="rows"><?php echo $row['FILE']; ?></td>
                            <td id="rows"><?php echo $row['IS_ANONYMOUS']; ?></td>
                            <td id="rows"><?php echo $row['DATE_POSTED']; ?></td>
                            <td id="rows"><?php echo $row['VOTE_COUNT']; ?></td>
                            <td id="rows"><?php echo $row['AVERAGE_RATING']; ?></td>
                            <td id="rows"><?php echo $row['VIEW_COUNT']; ?></td>
                            <td class="text-center">
                                <a href="delete_idea_qa_manager.php?id=<?php echo $row['IDEA_ID']; ?>" class="btn btn-danger" name="delete_idea">Delete</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>

            <a href="csv_download.php" class="btn btn-primary" id="downloadBtn">Download as CSV</a>
            <a href="zip_folder.php" class="btn btn-primary">Download Zip File</a>

        </div>
    </div>
</div>

<?php
require_once '../includes/footer.php';
?>
