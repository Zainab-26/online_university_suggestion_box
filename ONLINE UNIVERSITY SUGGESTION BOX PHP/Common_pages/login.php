<?php require_once '../includes/header.php' ?>

<!-- Login form -->
<div class="container-fluid">
    <section class="vh-100">
        <div class="container-fluid h-custom">
            <i class="fas fa-9x fa-arrow-right-to-bracket"></i>
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-md-9 col-lg-6 col-xl-5">
                    <img src="../media/rm-university_logo.png" alt="Univeristy Logo" class="img-fluid">
                </div>
                <div class="col-md-8 col-lg-6 col-xl-4 offset-xl-1">
                    <h3 class="item-heading">Login</h3>
                    <form method="POST" action="login.php">

                        <div class="form-outline mb-4">
                            <input type="email" class="form-control form-control-lg" name="email" id="email"
                                placeholder="Email Address" />
                        </div>

                        <div class="form-outline mb-4">
                            <input type="password" class="form-control form-control-lg" name="password" id="pass"
                                placeholder="Password" />
                        </div>

                        <div class="form-outline mb-4 form-button">
                            <input type="submit" name="signup" id="signup" class="form-submit" value="Login" />
                        </div>

                        <div class="text-center text-lg-start mt-4 pt-2">
                            <p class="small fw-bold mt-2 pt-1 mb-0">Don't have an account
                                <a href="register.php" class="link-danger">Register</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <?php
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        //Get data from form
        $email = $_POST['email'];
        $password = $_POST['password'];

        //SQL Query to check if user exists using prepared statements to avoid SQL Injections
        $statement = $connection->prepare("SELECT * FROM users WHERE EMAIL=?");
        $statement->bind_param("s", $email);

        $statement->execute();
        $statement->bind_result($user_id, $first_name, $last_name, $email, $secure_password, $user_type, $date, $department);
        $statement->store_result();

        //If the user is found compare form data to stored data    
        if ($statement->fetch()) {
            //Creating sessions to store user details
            $_SESSION['User_email'] = $email;
            $_SESSION['User_ID'] = $user_id;
            $_SESSION['First_name'] = $first_name;
            $_SESSION['Last_name'] = $last_name;
            $_SESSION['department'] = $department;

            //If the entered password and password stored in database match
            if (password_verify($password, $secure_password)) {
                $_SESSION['loggedin'] = TRUE;
                header("location: add_idea.php");

                //Check user roles in database and redirect to respective pages based on role
                if ($user_type == "Admin") {
                    header("location: ../Admin_pages/charts.php");
                    exit();
                } elseif ($user_type == "Staff") {
                    header("location: ../Staff_pages/view_ideas.php?filter=all");
                    exit();
                } elseif ($user_type == "Quality Assurance Manager") {
                    header("location: ../QA_Manager_pages/manage_category.php");
                    exit();
                } elseif ($user_type == "Quality Assurance Coordinator") {
                    header("location: ../QA_Coordinator_pages/manage_dept_ideas.php");
                    exit();
                }
            } else {
                //Display error message if incorrect credentials are entered
                echo "<p class='alert alert-danger'> The username or password is incorrect. Please try again.</p>";
            }
        } else {
            //Display message when email address provided does not exist in the database
            echo "<p class ='alert alert-danger'>The email address provided does not exist. Please try again.</p>";
        }
        $statement->close();
    }
    ?>

    <?php require_once '../includes/footer.php' ?>