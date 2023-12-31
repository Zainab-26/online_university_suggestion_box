<?php
require_once '../includes/header.php';
require_once '../includes/nav_admin.php';
?>

<!-- Admin adding closure dates form -->
<div class="content" id="dates">
    <form class="user" enctype="multipart/form-data" method="POST" action="add_dates.php" name="closure_dates_form">
        <div class='form-group'>
            <input id="academicYearClosure" class="form-control" type="date" name="academic_year_closure"
                placeholder="dd-mm-yyyy" />
        </div>
        <div class='form-group'>
            <input id="finalClosureDate" class="form-control" type="date" name="final_closure"
                placeholder="dd-mm-yyyy" />
        </div>
        <div class="center-align">
            <input id="btn" type="submit" value="Submit dates" class="btn btn-dark center-align "
                class="btn btn-dark btn-block" name="add_dates_btn">
        </div>
    </form>

    <?php
    add_dates();
    ?>

</div>
<?php
require_once '../includes/footer.php'
    ?>