<?php

header('Content-Type: application/json');

require_once '../Common_pages/setup.php';

//Calculate number and percentage of ideas submitted per dept
$query = "SELECT department.DEPARTMENT_NAME, COUNT(idea.IDEA_ID) as num_ideas, COUNT(idea.IDEA_ID) * 100 / SUM(COUNT(idea.IDEA_ID)) OVER() as percent_ideas
          FROM department
          LEFT JOIN idea ON department.DEPT_ID = idea.DEPT_ID
          GROUP BY department.DEPT_ID";
$result = $connection->query($query);


$data = array();
foreach ($result as $row) {
    $data[] = $row;
}

echo json_encode($data);
?>