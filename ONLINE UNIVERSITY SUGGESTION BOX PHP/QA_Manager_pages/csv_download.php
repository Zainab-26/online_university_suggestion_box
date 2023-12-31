<?php
require_once '../Common_pages/setup.php';

//Data to be downloaded in CSV format
$value = mysqli_query($connection, "SELECT * FROM idea");

//Pointer to file including filename
$file = fopen('ideas.csv', 'w');

//Headers in CSV file
$headers = array(
    'Idea_ID',
    'User_ID',
    'Category_ID',
    'Department_ID',
    'Idea_Title',
    'Idea_Description',
    'File',
    'Is_anonymous',
    'Date_posted',
    'Vote_count',
    'Average_rating',
    'View_count'
);
fputcsv($file, $headers);

//Data to put in CSV file
while ($row = mysqli_fetch_assoc($value)) {
    fputcsv($file, $row);
}

fclose($file);

header('Content-Type: application/csv');
header('Content-Disposition: attachment; filename=ideas.csv');

readfile('ideas.csv');
exit;
?>