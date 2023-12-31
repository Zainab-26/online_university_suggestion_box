<?php
    require_once '../includes/header.php';
    require_once '../includes/nav_admin.php';
    $values = ideasWithoutComments(); 
?>

<h1 class="heading_padding">Ideas without comments</h1>
<br>
<div class="card shadow mb-4">
<div class="card-body">
    <div class="table-responsive">
        <!-- Table to view ideas without comments -->
         <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
             <thead>
                 <tr>
                    <!-- Table headings -->
                    <th>Idea Title</th>
                    <th class="text-center">Idea Description</th>
                    <th class="text-center">Operations</th>

                 </tr>
            </thead>
            <tbody>
            <tr>
                 <?php
                 //Ideas without comments from the database and display in table cells
                     while($row=mysqli_fetch_assoc($values))
                     { 
                         ?>
                         <td> <?php echo $row['IDEA_TITLE']; ?></td>
                        <td> <?php echo $row['IDEA_DESCRIPTION']; ?></td>
                        </tr>
                     <?php   
                     }
                     ?>

            </tbody>

        </table>
<?php
    require_once '../includes/footer.php';
?>