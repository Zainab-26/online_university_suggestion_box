<?php
function set_message($message)
{
    if (!empty($message)) {
        $_SESSION['Message'] = $message;
    } else {
        $message = "";
    }
}

function display_message()
{
    if (isset($_SESSION['Message'])) {
        echo $_SESSION['Message'];
        unset($_SESSION['Message']);
    }
}


function display_error($err)
{
    echo "<p class='alert alert-danger'>$err</p>";
}

set_message("<p class='alert alert-danger'> The username or password is incorrect. Please try again.</p>");

//Generate random user id
function random_num($length)
{
    $text = "";
    if ($length < 5) {
        $length = 5;
    }

    $len = rand(4, $length);

    for ($i = 0; $i < $len; $i++) {
        $text .= rand(0, 9);
    }
    return $text;
}

//View all users in a dpertament
function view_dept_users($departmentId)
{
    global $connection;

    $stmt = $connection->prepare('SELECT *
                                 FROM users u
                                 JOIN department d ON u.DEPT_ID = d.DEPT_ID
                                 WHERE d.DEPT_ID = ?');

    $stmt->bind_param('i', $departmentId);

    $stmt->execute();

    $result = $stmt->get_result();

    $stmt->close();

    return $result;
}

//View all ideas submitted per department
function view_dept_ideas($departmentId)
{
    global $connection;

    $stmt = $connection->prepare('SELECT *
                                 FROM idea i
                                 JOIN department d ON i.DEPT_ID = d.DEPT_ID
                                 WHERE d.DEPT_ID = ?');

    $stmt->bind_param('i', $departmentId);

    $stmt->execute();

    $result = $stmt->get_result();

    $stmt->close();

    return $result;
}

//View all categories
function view_category()
{
    global $connection;
    $sql = "select * from idea_category";
    return mysqli_query($connection, $sql);
}

//View all departments
function view_departments()
{
    global $connection;
    $sql = "select * from department";
    return mysqli_query($connection, $sql);
}

//View all users
function view_users()
{
    global $connection;
    $sql = "select * from users";
    return mysqli_query($connection, $sql);
}


//View all users
function view_all_ideas()
{
    global $connection;
    $sql = "select * from idea";
    return mysqli_query($connection, $sql);
}

//Set or update academic year and final closure dates
function add_dates()
{
    global $connection;
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_dates_btn'])) {

        $academicYearClosure = $_POST['academic_year_closure'];
        $finalClosure = $_POST['final_closure'];

        $formattedAcademicYearClosure = date('Y-m-d', strtotime($academicYearClosure));
        $formattedFinalClosure = date('Y-m-d', strtotime($finalClosure));

        $stmt = $connection->prepare("SELECT COUNT(*) FROM closure_dates");
        $stmt->execute();
        $stmt->bind_result($rowCount);
        $stmt->fetch();
        $stmt->close();

        if ($rowCount === 0) {
            $stmt = $connection->prepare("INSERT INTO closure_dates (ACADEMIC_YEAR, CLOSURE_DATE) VALUES (?, ?)");
            $stmt->bind_param("ss", $formattedAcademicYearClosure, $formattedFinalClosure);
            $stmt->execute();
        } else {
            $stmt = $connection->prepare("UPDATE closure_dates SET ACADEMIC_YEAR = ?, CLOSURE_DATE = ?");
            $stmt->bind_param("ss", $formattedAcademicYearClosure, $formattedFinalClosure);
            $stmt->execute();
        }

        if ($stmt->affected_rows > 0) {
            echo "Closure dates saved successfully.";
        } else {
            echo "Error: Unable to save closure dates.";
        }

        $stmt->close();
    }

}

//Add idea
function add_idea()
{
    global $connection;
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_idea_btn'])) {
        $idea_title = $_POST['idea_title'];
        $idea_description = $_POST['idea_description'];
        //$idea_doc = $_POST['idea_doc'];
        $cat_name = $_POST['category_name'];
        $dep_name = $_POST['deptName'];
        $date = date("Y-m-d h:i:s");
        $_SESSION['User_email'];

        $anonymous = isset($_POST['anonymous']) ? 'Yes' : 'No';

        $idea_doc = $_FILES['idea_doc']['name']; 

        $targetDir = "../PDF_Uploads/";
        $targetFilePath = $targetDir . basename($idea_doc);

        if (move_uploaded_file($_FILES["idea_doc"]["tmp_name"], $targetFilePath)) {
        } else {
            echo "Error uploading file.";
        }


        $stmt = $connection->prepare("SELECT * FROM idea WHERE IDEA_DESCRIPTION = ?");
        $stmt->bind_param("s", $idea_description);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 0) {

            if ($cat_name == 0 || $dep_name == 0) {
                echo "Please enter a valid input.";
            } else {
                $sql =
                    "insert into idea(USER_ID, CATEGORY_ID, DEPT_ID, IDEA_TITLE, IDEA_DESCRIPTION, FILE, IS_ANONYMOUS, DATE_POSTED) values (?,?,?,?,?,?,?,?)";
                $statement = $connection->prepare($sql);
                $statement->bind_param('iiisssss', $_SESSION['User_ID'], $cat_name, $dep_name, $idea_title, $idea_description, $targetFilePath, $anonymous, $date);
                $statement->execute();
                $statement->close();
            }

        } else {
            echo "The idea already exists.";
        }

        header("location:view_ideas.php?filter=all");

        require_once '../vendor/autoload.php';

        //Send email to QA Coordinator
        $transport = new Swift_SmtpTransport('smtp.gmail.com', 465, 'ssl');
        $transport->setUsername('zainabp269@gmail.com');
        $transport->setPassword('djrbabdcqxvifanv');

        $mailer = new Swift_Mailer($transport);

        $message = new Swift_Message('New idea submission');

        $email = $_SESSION['User_email'];
        $first_name = $_SESSION['First_name'];

        $message->setFrom([$email => $first_name]);

        $message->setTo(['qa.coordinator.comp1640@gmail.com' => 'QA Coordinator']);
        $message->setBody('A user has submitted an idea.');

        $result = $mailer->send($message);
    }
}

//Allow users to like or dislike an idea only once
if (isset($_POST['vote'])) {
    $idea_id = $_POST['idea_id'];
    $user_id = $_SESSION['User_ID'];
    $vote = $_POST['vote'];

    // Check if the user has already reacted to the idea
    $stmt = mysqli_prepare($connection, "SELECT COUNT(*), RATING_ACTION FROM reactions WHERE IDEA_ID=? AND USER_ID=? GROUP BY RATING_ACTION");
    mysqli_stmt_bind_param($stmt, 'ii', $idea_id, $user_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $count, $old_vote);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    if ($count > 0) {
        //If the user has already reacted, update the database
        if ($old_vote !== $vote) {
            $stmt = mysqli_prepare($connection, "UPDATE reactions SET RATING_ACTION=? WHERE IDEA_ID=? AND USER_ID=?");
            mysqli_stmt_bind_param($stmt, 'sii', $vote, $idea_id, $user_id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
    } else {
        //If the user has not yet reacted, add new reaction
        $stmt = mysqli_prepare($connection, "INSERT INTO reactions (IDEA_ID, USER_ID, RATING_ACTION) VALUES (?, ?, ?)");
        mysqli_stmt_bind_param($stmt, 'iis', $idea_id, $user_id, $vote);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }

    //Update the vote count and average rating for the idea
    $stmt = mysqli_prepare($connection, "SELECT COUNT(*) FROM reactions WHERE IDEA_ID=?");
    mysqli_stmt_bind_param($stmt, 'i', $idea_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $total_votes);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    $stmt = mysqli_prepare($connection, "SELECT COUNT(*) FROM reactions WHERE IDEA_ID=? AND RATING_ACTION='up'");
    mysqli_stmt_bind_param($stmt, 'i', $idea_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $up_votes);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    $down_votes = $total_votes - $up_votes;
    $average_rating = ($up_votes - $down_votes) / $total_votes;

    $stmt = mysqli_prepare($connection, "UPDATE idea SET VOTE_COUNT=?, AVERAGE_RATING=? WHERE IDEA_ID=?");
    mysqli_stmt_bind_param($stmt, 'idi', $total_votes, $average_rating, $idea_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

//View most popular ideas
function getMostPopularIdeas()
{
    global $connection;

    $stmt = mysqli_prepare($connection, "SELECT IDEA_ID, (SELECT COUNT(*) FROM reactions WHERE IDEA_ID = r.IDEA_ID AND RATING_ACTION = 'up') - (SELECT COUNT(*) FROM reactions WHERE IDEA_ID = r.IDEA_ID AND RATING_ACTION = 'down') AS popularity_score 
    FROM reactions r
    GROUP BY IDEA_ID
    ORDER BY popularity_score DESC");
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $idea_id, $popularity_score);

    while (mysqli_stmt_fetch($stmt)) {
        echo "Idea ID: $idea_id<br>";
        echo "Popularity Score: $popularity_score<br><br>";
    }

    mysqli_stmt_close($stmt);
}

//Add comments
function add_comment($redirectionUrl)
{
    global $connection;
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

            //Send email to author of idea
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

            header("location: $redirectionUrl");
            exit();
        } else {
        }
        mysqli_stmt_close($stmt);

    }
}

//Calculate the number of like per idea
function numberOfLikes($idea_id)
{
    global $connection;
    $result = $connection->query("SELECT COUNT(CASE WHEN reactions.RATING_ACTION = 'up' THEN 1 END) AS numOfLikes,
    COUNT(CASE WHEN reactions.RATING_ACTION = 'down' THEN 1 END) AS numOfDislikes FROM reactions WHERE IDEA_ID = '$idea_id'");
    $row = $result->fetch_assoc();
    $numOfLikes = $row['numOfLikes'];
    $numOfDislikes = $row['numOfDislikes'];
    $result->free();

    return ['numOfLikes' => $numOfLikes, 'numOfDislikes' => $numOfDislikes];
}

//Add category
function add_Category()
{
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['addCategory'])) {
        global $connection;
        $category_name = $_POST['category'];
        $statement = $connection->prepare("select * from idea_category where CATEGORY_ID = ?");
        $statement->bind_param("s", $category_name);
        $statement->execute();
        $statement->store_result();

        if ($statement->num_rows == 0) {
            $statement2 = $connection->prepare("insert into idea_category(CATEGORY_NAME) values (?)");
            $statement2->bind_param('s', $category_name);
            $statement2->execute();
            header("Location: manage_category.php");
            $statement2->close();
        } else {

        }
        $statement->free_result();
        $statement->close();
    }
}

//Add dept
function add_dept()
{
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_dept'])) {
        global $connection;
        $dept_name = $_POST['dept'];
        $statement = $connection->prepare("select * from department where DEPT_ID = ?");
        $statement->bind_param("s", $dept_name);
        $statement->execute();
        $statement->store_result();

        if ($statement->num_rows == 0) {
            $statement2 = $connection->prepare("insert into department(DEPARTMENT_NAME) values (?)");
            $statement2->bind_param('s', $dept_name);
            $statement2->execute();
            header("Location: manage_dept.php");
            $statement2->close();
        } else {

        }
        $statement->free_result();
        $statement->close();
    }
}

//Find ideas without comments
function ideasWithoutComments()
{
    global $connection;

    $sql = "SELECT idea.IDEA_ID, idea.IDEA_DESCRIPTION, idea.IDEA_TITLE
    FROM idea
    LEFT JOIN `comments` ON idea.IDEA_ID = `comments`.IDEA_ID
    WHERE `comments`.IDEA_ID IS NULL";
    $result = mysqli_query($connection, $sql);

    return $result;
}

//Find anonymous ideas
function anonymousIdeas()
{
    global $connection;

    $sql = "SELECT IDEA_ID, IDEA_DESCRIPTION, IDEA_TITLE
    FROM idea
    WHERE IS_ANONYMOUS = 'Yes'";
    $result = mysqli_query($connection, $sql);

    return $result;
}

//Find anonymous comments
function anonymousComments()
{
    global $connection;

    $sql = "SELECT idea.IDEA_ID, idea.IDEA_TITLE, `comments`.COMMENT_ID, `comments`.`COMMENT`
    FROM idea
    JOIN `comments` ON idea.IDEA_ID = `comments`.IDEA_ID
    WHERE `comments`.IS_ANONYMOUS = 'Yes'";
    $result = mysqli_query($connection, $sql);

    return $result;
}

//Paginate idea to 5 per page when getting all ideas from database
global $connection;
$ideasPerPage = 5;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$startingIdea = ($page - 1) * $ideasPerPage;
$sql = "SELECT * FROM idea ORDER BY IDEA_ID DESC LIMIT $startingIdea, $ideasPerPage";
$result = mysqli_query($connection, $sql);

$posts = mysqli_fetch_all($result, MYSQLI_ASSOC);
error_reporting(E_ALL);
ini_set('display_errors', 1);

?>