<?php
require_once '../includes/header.php';
require_once '../includes/nav_staff.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>

<!-- Filter ideas dropdown -->
<div class="dropdown center-align">
  <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown"
    aria-haspopup="true" aria-expanded="false">
    Filter ideas
  </button>
  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
    <a class="dropdown-item" href="?filter=all">All ideas</a>
    <a class="dropdown-item" href="?filter=popular">Most Popular</a>
    <a class="dropdown-item" href="?filter=viewed">Most Viewed</a>
    <a class="dropdown-item" href="?filter=latest">Latest ideas</a>
    <a class="dropdown-item" href="?filter=comments">Latest comments</a>
  </div>
</div>


<?php
if (isset($_GET['filter'])) {
  $selectedFilter = $_GET['filter'];

  //Check filter value in URL
  if ($selectedFilter == 'popular') { ?>
    <br>
    <h1 class="center-align">Most Popular Ideas</h1>

    <?php
    $page = isset($_GET['page']) ? $_GET['page'] : 1;
    $offset = ($page - 1) * $ideasPerPage;

    //Get most popular ideas based on user likes and dislikes
    $stmt = mysqli_prepare($connection, "SELECT i.USER_ID, i.IDEA_ID, i.IDEA_TITLE, i.IDEA_DESCRIPTION, i.IS_ANONYMOUS, (SELECT COUNT(*) FROM reactions WHERE IDEA_ID = i.IDEA_ID AND RATING_ACTION = 'up') - (SELECT COUNT(*) FROM reactions WHERE IDEA_ID = i.IDEA_ID AND RATING_ACTION = 'down') AS popularity_score 
FROM idea i
LEFT JOIN reactions r ON i.IDEA_ID = r.IDEA_ID
GROUP BY i.IDEA_ID
ORDER BY popularity_score DESC LIMIT ?, ?");
    mysqli_stmt_bind_param($stmt, "ii", $offset, $ideasPerPage);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    mysqli_stmt_bind_result($stmt, $user_Id, $idea_id, $ideaTitle, $ideaDesc, $anon, $popularity_score);

    while (mysqli_stmt_fetch($stmt)) {
      ?>
      <div class="card text-center w-50" id="view_idea">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">
              <?php echo $ideaTitle ?>
            </h5>
            <p class="card-text">
              <?php echo $ideaDesc ?>
            </p>
            <p class="card-text">Popularity Score:
              <?php echo $popularity_score; ?>
            </p>
            <?php
            if ($anon == "No") {
              $query = "SELECT FIRST_NAME, LAST_NAME FROM users WHERE USER_ID = ?";
              $stmt2 = mysqli_prepare($connection, $query);
              mysqli_stmt_bind_param($stmt2, "i", $user_Id);
              mysqli_stmt_execute($stmt2);
              mysqli_stmt_bind_result($stmt2, $firstName, $lastName);
              mysqli_stmt_fetch($stmt2);
              $user_name = $firstName . " " . $lastName;
              echo "<p class='card-text'>$user_name</p>";
              mysqli_stmt_close($stmt2);
            } else {
              echo "<p class='card-text'>Anonymous</p>";
            }
            ?>
            <?php $counts = numberOfLikes($idea_id); ?>
            <form method="post" action="view_ideas.php?filter=popular">
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

      <div class="modal" id="commentModal<?php echo $idea_id ?>">
        <div class="modal-dialog">
          <div class="modal-content">
            <!-- Modal Header for comments -->
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
                      $stmt_comments = mysqli_prepare($connection, $query);
                      mysqli_stmt_bind_param($stmt_comments, "i", $user_id);
                      mysqli_stmt_execute($stmt_comments);
                      $result = mysqli_stmt_get_result($stmt_comments);
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
            <?php

            $stmt_date = $connection->prepare("SELECT TRIM(CLOSURE_DATE) FROM closure_dates");
            $stmt_date->execute();
            $stmt_date->bind_result($finalClosure);
            $stmt_date->fetch();
            $stmt_date->close();

            $currentDate = date("Y-m-d");

            if ($currentDate > $finalClosure) {
              echo "Academic year closure: " . $finalClosure . ". Ideas cannot be added at this time.";
            } else {
              ?>
              <div class="modal-footer">
                <form class="user" method="post" action="view_ideas.php?filter=popular" enctype="multipart/form-data">
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
                <?php
                add_comment("view_ideas.php?filter=popular");
                ?>
                <br>
              </div>
            <?php } ?>
          </div>
        </div>
      </div>

      <?php
    }

    mysqli_stmt_close($stmt); ?>
    <br>
    <br>

    <!-- Pagination, 5 ideas per page -->
    <div class="pagination">
      <ul class="pagination">
        <li class="page-item">
          <?php
          $totalIdeasQuery = "SELECT COUNT(*) as TOTAL FROM idea";
          $result = mysqli_query($connection, $totalIdeasQuery);
          $row = mysqli_fetch_assoc($result);
          $totalIdeas = $row['TOTAL'];

          $totalPages = ceil($totalIdeas / $ideasPerPage);

          for ($i = 1; $i <= $totalPages; $i++) {
            echo "<a href='view_ideas.php?filter=popular&page=$i'>$i</a> ";
          }
          ?>
        </li>
      </ul>
    </div>
    <?php

    //Get filter value from URL
  } elseif ($selectedFilter == 'viewed') { ?>
    <br>
    <h1 class="center-align">Most Viewed Ideas</h1>

    <?php
    $page = isset($_GET['page']) ? $_GET['page'] : 1;
    $offset = ($page - 1) * $ideasPerPage;

    $sql = "SELECT * FROM idea ORDER BY VIEW_COUNT DESC LIMIT ?, ?";

    $stmt = mysqli_prepare($connection, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $offset, $ideasPerPage);

    $ideasPerPage = 5;
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    $ideas = mysqli_fetch_all($result, MYSQLI_ASSOC);

    foreach ($ideas as $idea) {
      $idea_id = $idea['IDEA_ID'];
      $idea_Title = $idea['IDEA_TITLE'];
      $idea_Description = $idea['IDEA_DESCRIPTION'];
      ?>
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
              $stmt2 = mysqli_prepare($connection, $query);
              mysqli_stmt_bind_param($stmt2, "i", $user_id);
              mysqli_stmt_execute($stmt2);
              mysqli_stmt_bind_result($stmt2, $firstName, $lastName);
              mysqli_stmt_fetch($stmt2);
              $user_name = $firstName . " " . $lastName;
              echo "<p class='card-text'>$user_name</p>";
              mysqli_stmt_close($stmt2);
            } else {
              echo "<p class='card-text'>Anonymous</p>";
            }
            ?>

            <?php $counts = numberOfLikes($idea_id); ?>
            <form method="post" action="view_ideas.php?filter=viewed">
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
                      $stmt_comments = mysqli_prepare($connection, $query);
                      mysqli_stmt_bind_param($stmt_comments, "i", $user_id);
                      mysqli_stmt_execute($stmt_comments);
                      $result = mysqli_stmt_get_result($stmt_comments);
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
            <?php

            $stmt_date = $connection->prepare("SELECT TRIM(CLOSURE_DATE) FROM closure_dates");
            $stmt_date->execute();
            $stmt_date->bind_result($finalClosure);
            $stmt_date->fetch();
            $stmt_date->close();

            $currentDate = date("Y-m-d");

            if ($currentDate > $finalClosure) {
              echo "Academic year closure: " . $finalClosure . ". Ideas cannot be added at this time.";
            } else {
              ?>
              <div class="modal-footer">
                <form class="user" method="post" action="view_ideas.php?filter=viewed" enctype="multipart/form-data">
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
                <?php
                add_comment("view_ideas.php?filter=viewed");
                ?>
                <br>
              </div>
            <?php } ?>
          </div>
        </div>
      </div>

      <?php
    }

    mysqli_stmt_close($stmt);
    ?>
    <!-- Pagination for 5 ideas per page -->
    <div class="pagination">
      <ul class="pagination">
        <li class="page-item">
          <?php
          $totalIdeasQuery = "SELECT COUNT(*) as TOTAL FROM idea";
          $result = mysqli_query($connection, $totalIdeasQuery);
          $row = mysqli_fetch_assoc($result);
          $totalIdeas = $row['TOTAL'];

          $totalPages = ceil($totalIdeas / $ideasPerPage);

          for ($i = 1; $i <= $totalPages; $i++) {
            echo "<a href='view_ideas.php?filter=viewed&page=$i'>$i</a> ";
          }
          ?>
        </li>
      </ul>
    </div>
    <?php
  } elseif ($selectedFilter == 'latest') { ?>
    <br>
    <h1 class="center-align">Latest Ideas</h1>

    <?php
    $page = isset($_GET['page']) ? $_GET['page'] : 1;
    $offset = ($page - 1) * $ideasPerPage;

    $sql = "SELECT * FROM idea ORDER BY DATE_POSTED DESC LIMIT ?, ?";
    $stmt = mysqli_prepare($connection, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $offset, $ideasPerPage);


    $ideasPerPage = 5;
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    $ideas = mysqli_fetch_all($result, MYSQLI_ASSOC);

    foreach ($ideas as $idea) {
      $idea_id = $idea['IDEA_ID'];
      $idea_Title = $idea['IDEA_TITLE'];
      $idea_Description = $idea['IDEA_DESCRIPTION'];
      $date_Posted = $idea['DATE_POSTED'];
      ?>
      <div class="card text-center w-50" id="view_idea">

        <div class="card">
          <div class="card-body">
            <h5 class="card-title">
              <?php echo $idea_Title ?>
            </h5>
            <p class="card-text">
              <?php echo $idea_Description ?>
            </p>
            <p class="card-text">
              <?php echo $date_Posted ?>
            </p>

            <?php
            $user_id = $idea['USER_ID'];
            $is_anonymous = $idea['IS_ANONYMOUS'];
            if ($is_anonymous == "No") {
              $query = "SELECT FIRST_NAME, LAST_NAME FROM users WHERE USER_ID = ?";
              $stmt2 = mysqli_prepare($connection, $query);
              mysqli_stmt_bind_param($stmt2, "i", $user_id);
              mysqli_stmt_execute($stmt2);
              mysqli_stmt_bind_result($stmt2, $firstName, $lastName);
              mysqli_stmt_fetch($stmt2);
              $user_name = $firstName . " " . $lastName;
              echo "<p class='card-text'>$user_name</p>";
              mysqli_stmt_close($stmt2);
            } else {
              echo "<p class='card-text'>Anonymous</p>";
            }
            ?>

            <?php $counts = numberOfLikes($idea_id); ?>
            <form method="post" action="view_ideas.php?filter=latest">
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
                      $stmt_comments = mysqli_prepare($connection, $query);
                      mysqli_stmt_bind_param($stmt_comments, "i", $user_id);
                      mysqli_stmt_execute($stmt_comments);
                      $result = mysqli_stmt_get_result($stmt_comments);
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
            <?php

            $stmt_date = $connection->prepare("SELECT TRIM(CLOSURE_DATE) FROM closure_dates");
            $stmt_date->execute();
            $stmt_date->bind_result($finalClosure);
            $stmt_date->fetch();
            $stmt_date->close();

            $currentDate = date("Y-m-d");

            if ($currentDate > $finalClosure) {
              echo "Academic year closure: " . $finalClosure . ". Ideas cannot be added at this time.";
            } else {
              ?>
              <div class="modal-footer">
                <form class="user" method="post" action="view_ideas.php?filter=latest" enctype="multipart/form-data">
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
                <?php
                add_comment("view_ideas.php?filter=latest");
                ?>
                <br>
              </div>
            <?php } ?>
          </div>
        </div>
      </div>

      <?php
    }

    mysqli_stmt_close($stmt); ?>
    <!-- Pagination for 5 ideas per page -->
    <div class="pagination">
      <ul class="pagination">
        <li class="page-item">
          <?php
          $totalIdeasQuery = "SELECT COUNT(*) as TOTAL FROM idea";
          $result = mysqli_query($connection, $totalIdeasQuery);
          $row = mysqli_fetch_assoc($result);
          $totalIdeas = $row['TOTAL'];

          $totalPages = ceil($totalIdeas / $ideasPerPage);

          for ($i = 1; $i <= $totalPages; $i++) {
            echo "<a href='view_ideas.php?filter=latest&page=$i'>$i</a> ";
          }
          ?>
        </li>
      </ul>
    </div>
    <?php
  } elseif ($selectedFilter == 'comments') { ?>
    <br>
    <h1 class="center-align">Latest Comments</h1>

    <?php
    $sql = "SELECT * FROM comments ORDER BY DATE_POSTED DESC LIMIT 10";

    $result = mysqli_query($connection, $sql);

    if ($result) {
      while ($row = mysqli_fetch_assoc($result)) {
        $commentId = $row['COMMENT_ID'];
        $commentText = $row['COMMENT'];
        $datePosted = $row['DATE_POSTED'];
        ?>

        <div class="card text-center w-50" id="view_idea">

          <div class="card">
            <div class="card-body">
              <h5 class="card-title">
                <?php echo $commentText ?>
              </h5>
              <p class="card-text">
                <?php echo $datePosted ?>
              </p>

            </div>
          </div>
        </div>
        <?php
      }
    } else {
      echo "Error executing the query: " . mysqli_error($connection);
    }
  } elseif ($selectedFilter == 'all') {
    ?>

    <?php
    if (isset($_POST['idea_id'])) {
      $_SESSION['Idea_id'] = $_POST['idea_id'];

      $ideaId = $_POST['idea_id'];

      $sql = "UPDATE idea SET VIEW_COUNT = VIEW_COUNT + 1 WHERE IDEA_ID = ?";
      $stmt = mysqli_prepare($connection, $sql);

      mysqli_stmt_bind_param($stmt, "i", $ideaId);

      mysqli_stmt_execute($stmt);

      mysqli_stmt_close($stmt);
    }
    ?>
    <?php foreach ($posts as $post): ?>
      <?php $idea_id = $post['IDEA_ID'];
      ?>
      <div class="card text-center w-50" id="view_idea">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">
              <?php echo $post['IDEA_TITLE']; ?>
            </h5>
            <p class="card-text">
              <?php echo $post['IDEA_DESCRIPTION']; ?>
            </p>
            <?php
            $user_id = $post['USER_ID'];
            $is_anonymous = $post['IS_ANONYMOUS'];
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
            <form method="post" action="view_ideas.php?filter=all">
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

      <!-- The Modal for comments-->
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
                <form class="user" method="post" action="view_ideas.php?filter=all" enctype="multipart/form-data">
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
                <?php
                add_comment("view_ideas.php?filter=all");
                ?>
                <br>
              </div>
            <?php } ?>

          </div>
        </div>
      </div>
    <?php endforeach; ?>

    <!-- Pagination for 5 ideas per page -->
    <div class="pagination">
      <ul class="pagination">
        <li class="page-item">
          <?php
          $totalIdeasQuery = "SELECT COUNT(*) as TOTAL FROM idea";
          $result = mysqli_query($connection, $totalIdeasQuery);
          $row = mysqli_fetch_assoc($result);
          $totalIdeas = $row['TOTAL'];

          $totalPages = ceil($totalIdeas / $ideasPerPage);

          for ($i = 1; $i <= $totalPages; $i++) {
            echo "<a href='view_ideas.php?filter=all&page=$i'>$i</a> ";
          }
          ?>
        </li>
      </ul>
    </div>
    <?php

  }
}
?>


<?php require_once '../includes/footer.php' ?>