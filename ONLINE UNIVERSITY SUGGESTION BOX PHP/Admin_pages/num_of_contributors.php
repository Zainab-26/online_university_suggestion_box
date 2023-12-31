<?php

header('Content-Type: application/json');

require_once '../Common_pages/setup.php';

//Calculate the number of contributors per dept
$query = "SELECT department.DEPT_ID, department.DEPARTMENT_NAME, COUNT(DISTINCT idea.USER_ID) AS contributor_count
FROM idea
JOIN users ON idea.USER_ID = users.USER_ID
JOIN department ON idea.DEPT_ID = department.DEPT_ID
GROUP BY department.DEPT_ID, department.DEPARTMENT_NAME;
";
$result = $connection->query($query);


$data = array();
foreach ($result as $row) {
	$data[] = $row;
}

echo json_encode($data);
?>