<?php
require_once '../includes/header.php';
$dep_view = view_departments(); ?>
<div class="main">

    <!-- Register form -->
    <div class="container-fluid">
        <section class="vh-100">
            <div class="container-fluid h-custom">
                <i class="fas fa-9x fa-arrow-right-to-bracket"></i>
                <div class="row d-flex justify-content-center align-items-center h-100">
                    <div class="col-md-9 col-lg-6 col-xl-5">
                        <img src="../media/rm-university_logo.png" alt="Univeristy Logo" class="img-fluid">
                    </div>
                    <div class="col-md-8 col-lg-6 col-xl-4 offset-xl-1">
                        <h3 class="item-heading">SIGN UP</h3>
                        <form method="POST" action="register.php">
                            <div class="form-outline mb-4">
                                <input type="text" class="form-control form-control-lg" name="fName" id="name"
                                    placeholder="First Name" />
                            </div>
                            <div class="form-outline mb-4">
                                <input type="text" class="form-control form-control-lg" name="lName" id="name"
                                    placeholder="Last Name" />
                            </div>
                            <div class="form-outline mb-4">
                                <input type="email" class="form-control form-control-lg" name="email" id="email"
                                    placeholder="Email Address" />
                            </div>

                            <div class="form-outline mb-4">
                                <input type="password" class="form-control form-control-lg" name="password" id="pass"
                                    placeholder="Password" />
                            </div>

                            <div class="form-outline mb-4">
                                <select name="department_name" class="form-control form-control-lg" required>
                                    <option class="form-control form-control-lg" value="">Select Department</option>
                                    <?php
                                    while ($row = mysqli_fetch_assoc($dep_view)) {
                                        ?>
                                        <option value="<?php echo $row['DEPT_ID'] ?>"><?php echo $row['DEPARTMENT_NAME'] ?>
                                        </option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>


                            <div class="form-outline mb-4 form-button">
                                <input type="submit" name="signup" id="signup" class="form-submit" value="Register" />
                            </div>

                            <div class="text-center text-lg-start mt-4 pt-2">
                                <p class="small fw-bold mt-2 pt-1 mb-0">Have an account already?
                                    <a href="login.php" class="link-danger">Login</a>
                                </p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
    </div>


    </section>

    <?php

    //If user clicks on Register button
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        //Get data from form or automatically generate
        $user_id = random_num(10);
        $first_name = $_POST['fName'];
        $last_name = $_POST['lName'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $secure_password = password_hash($password, PASSWORD_DEFAULT);
        $user_type = "";
        $department = $_POST['department_name'];
        $date = date("Y-m-d h:i:s");

        //Check if user already exists
        $stmt = $connection->prepare("SELECT * FROM users WHERE EMAIL = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        //If no users found, create account
        if ($stmt->num_rows == 0) {

            $stmt1 = $connection->prepare("insert into users (USER_ID, FIRST_NAME, LAST_NAME, EMAIL, PASSWORD, DATE_REGISTERED, DEPT_ID) values(?, ?, ?, ?, ?, ?, ?)");
            $stmt1->bind_param('ssssssi', $user_id, $first_name, $last_name, $email, $secure_password, $date, $department);
            $stmt1->execute();

            header("Location: login.php");

            $stmt1->close();

        } else {
            echo "The account already exists.";
        }
        $stmt->free_result();
        $stmt->close();
    }

    ?>

    <?php require_once '../includes/footer.php' ?>