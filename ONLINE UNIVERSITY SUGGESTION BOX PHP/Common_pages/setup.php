<?php  
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

//Database connection
$connection = mysqli_connect("localhost:3306", "root", "zainab", "project_db");

if(!$connection)
{
    echo "The connection has failed.";
    exit();
}
?>