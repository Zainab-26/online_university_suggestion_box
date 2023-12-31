<?php
require_once '../includes/header.php';
require_once '../includes/nav_staff.php';
$cat_view = view_category();
$dep_view = view_departments();

$stmt = $connection->prepare("SELECT TRIM(ACADEMIC_YEAR) FROM closure_dates");
$stmt->execute();
$stmt->bind_result($academicYearClosure);
$stmt->fetch();
$stmt->close();

//Check if academic year has ended
$currentDate = date("Y-m-d");

if ($currentDate > $academicYearClosure) {
    echo "Academic year closure: " . $academicYearClosure . ". Ideas cannot be added after this date.";
} else {
    ?>
    <h1 class="center-align">
        Submit idea
    </h1>

    <!-- Add idea form -->
    <form class="user" method="post" action="add_idea.php" enctype="multipart/form-data">
        <input type="hidden" name="deptName" value="<?php echo $_SESSION['department']; ?>">
        <div id="idea">
            <!-- add comments form-->
            <div class="form-group">
                <input type="text" class="form-control" placeholder="Enter title" name="idea_title" required>
            </div>
            <div class="form-group">
                <input type="text" class="form-control" placeholder="Description" name="idea_description" required>
            </div>
            <div class="form-group">
                <input type="file" class="form-control border" placeholder="Upload document" name="idea_doc"
                    title="Upload PDF">
            </div>
            <div class="form-group">
                <select name="category_name" class="form-select" required>
                    <option value="">Select Category</option>
                    <?php
                    while ($row = mysqli_fetch_assoc($cat_view)) {
                        ?>
                        <option value="<?php echo $row['CATEGORY_ID'] ?>"><?php echo $row['CATEGORY_NAME'] ?></option>
                        <?php
                    }
                    ?>
                </select>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="" id="terms" required>
                I agree to the Terms and Conditions
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="" id="anonymous" name="anonymous">
                Post anonymously
            </div>
            <div class="center-align">
                <input id="btn" type="submit" value="Submit idea" class="btn btn-dark center-align"
                    class="btn btn-dark btn-block" name="add_idea_btn">
            </div>
            <?php
            add_idea();
            ?>
    </form>
    <?php
}
require_once '../includes/footer.php';
?>