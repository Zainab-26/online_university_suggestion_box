<?php
require_once '../includes/header.php';
require_once '../includes/nav_staff.php';
?>

<h1>My Ideas </h1>
<?php

if (isset($_GET['user_id'])) {
  $userId = $_GET['user_id'];

  $sql = "SELECT * FROM idea WHERE USER_ID = ?";
  $stmt = mysqli_prepare($connection, $sql);

  mysqli_stmt_bind_param($stmt, "i", $userId);

  mysqli_stmt_execute($stmt);

  $result = mysqli_stmt_get_result($stmt);
  $ideas = mysqli_fetch_all($result, MYSQLI_ASSOC);

  foreach ($ideas as $idea) {

    $idea_id = $idea['IDEA_ID'];
    $idea_Title = $idea['IDEA_TITLE'];
    $idea_Description = $idea['IDEA_DESCRIPTION']; ?>
    <div class="card text-center w-50" id="view_idea">

      <div class="card">
        <div class="card-body">
          <h5 class="card-title">
            <?php echo $idea_Title ?>
          </h5>
          <p class="card-text">
            <?php echo $idea_Description ?>
          </p>


          <?php
          $user_id = $idea['USER_ID'];
          $is_anonymous = $idea['IS_ANONYMOUS'];
          if ($is_anonymous == "No") {
            $query = "SELECT FIRST_NAME, LAST_NAME FROM users WHERE USER_ID = ?";
            $stmt = mysqli_prepare($connection, $query);
            mysqli_stmt_bind_param($stmt, "i", $user_id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $row = mysqli_fetch_assoc($result);
            $user_name = $row['FIRST_NAME'] . " " . $row['LAST_NAME'];
            echo "<p class='card-text'>$user_name</p>";
          } else {
            echo "<p class='card-text'>Anonymous</p>";
          }

          ?>
          <?php $counts = numberOfLikes($idea_id); ?>
          <form method="post" action="my_ideas.php?user_id=<?php echo $_SESSION['User_ID'] ?>">
            <input type="hidden" name="idea_id" value="<?php echo $idea_id; ?>">
            <button class="btn" name="vote" value="up"><i class="fa fa-thumbs-up"></i>
              <?php echo $counts['numOfLikes'] ?>
            </button>
            <button class="btn" name="vote" value="down"><i class="fa fa-thumbs-down"></i>
              <?php echo $counts['numOfDislikes'] ?>
            </button>
            <button type="button" class="btn" data-bs-toggle="modal" data-bs-target="#commentModal<?php echo $idea_id; ?>">
              <i class="fa fa-comment"></i> Comments </button>
          </form>
        </div>
      </div>
    </div>



    <!-- The Modal for comments -->
    <div class="modal" id="commentModal<?php echo $idea_id ?>">
      <div class="modal-dialog">
        <div class="modal-content">
          <!-- Modal Header -->
          <div class="modal-header">
            <h4 class="modal-title">Comments</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <!-- Modal Body -->
          <div class="modal-body">
            <?php
            $sql = "SELECT * FROM comments where IDEA_ID = '$idea_id'";
            $result = mysqli_query($connection, $sql);
            $comments = mysqli_fetch_all($result, MYSQLI_ASSOC);
            foreach ($comments as $comment): ?>
              <div class="card w-100">
                <div class="card-body">
                  <h5 class="card-title">
                    <?php echo $comment['COMMENT']; ?>
                  </h5>
                  <?php

                  $user_id = $comment['USER_ID'];
                  $is_anonymous = $comment['IS_ANONYMOUS'];

                  if ($is_anonymous == "No") {
                    $query = "SELECT FIRST_NAME, LAST_NAME FROM users WHERE USER_ID = ?";
                    $stmt = mysqli_prepare($connection, $query);
                    mysqli_stmt_bind_param($stmt, "i", $user_id);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);
                    $row = mysqli_fetch_assoc($result);
                    $user_name = $row['FIRST_NAME'] . " " . $row['LAST_NAME'];
                    echo "<p class='card-text'>$user_name</p>";
                  } else {
                    echo "<p class='card-text'>Anonymous</p>";
                  }

                  ?>
                </div>
              </div>
            <?php
            endforeach;
            ?>

          </div>


          <!-- Modal footer -->
          <?php

          $stmt = $connection->prepare("SELECT TRIM(CLOSURE_DATE) FROM closure_dates");
          $stmt->execute();
          $stmt->bind_result($finalClosure);
          $stmt->fetch();
          $stmt->close();

          $currentDate = date("Y-m-d");

          if ($currentDate > $finalClosure) {
            echo "Academic year closure: " . $finalClosure . ". Ideas cannot be added at this time.";
          } else {
            ?>
            <div class="modal-footer">
              <form class="user" method="post" action="my_ideas.php?user_id=<?php echo $_SESSION['User_ID'] ?>"
                enctype="multipart/form-data">
                <input type="hidden" name="idea_id" value="<?php echo $idea_id ?>">
                <div class="form-group w-100">
                  <textarea class="form-control" name="comment"></textarea>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" value="" id="anonymousComment" name="anonymousComment">
                  Post anonymously
                </div>
                <button type="submit" class="btn btn-primary" name="add_comment">Submit</button>
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
              </form>
              <br>
            </div>
          <?php } ?>

          <?php
          if (isset($_POST['add_comment']) && isset($_SESSION['User_ID'])) {

            $user_id = $_SESSION['User_ID'];
            $idea_ID = $_POST['idea_id'];
            $anonymous = isset($_POST['anonymousComment']) ? 'Yes' : 'No';
            $date = date("Y-m-d h:i:s");

            $sql = "INSERT INTO comments (IDEA_ID, USER_ID, COMMENT, IS_ANONYMOUS, DATE_POSTED) VALUES (?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($connection, $sql);

            mysqli_stmt_bind_param($stmt, "iisss", $idea_ID, $user_id, $comment_text, $anonymous, $date);

            $comment_text = $_POST['comment'];

            if (mysqli_stmt_execute($stmt)) {

              require_once '../vendor/autoload.php';

              $transport = new Swift_SmtpTransport('smtp.gmail.com', 465, 'ssl');
              $transport->setUsername('zainabp269@gmail.com');
              $transport->setPassword('djrbabdcqxvifanv');


              $mailer = new Swift_Mailer($transport);

              $message = new Swift_Message('New idea submission');

              $email = $_SESSION['User_email'];
              $first_name = $_SESSION['First_name'];

              $message->setFrom([$email => $first_name]);

              $message->setTo([$_SESSION['User_email'] => 'QA Coordinator']);
              $message->setBody('A user has submitted an idea.');

              $result = $mailer->send($message);

              header("Location: ../Staff_pages/my_ideas.php?user_id=" . $_SESSION['User_ID']);
              exit();
            } else {
            }
            mysqli_stmt_close($stmt);

          }
          ?>
        </div>
      </div>
    </div>


    <?php
  }
}
?>


<?php
require_once '../includes/footer.php';
?>