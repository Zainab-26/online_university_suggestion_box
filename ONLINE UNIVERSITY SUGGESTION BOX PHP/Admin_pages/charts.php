<?php
require_once '../includes/header.php';
require_once '../includes/nav_admin.php';
?>

<h1 class="center-align margin-bottom">Statistical Reports</h1>

<h1>Number and percentage of ideas posted per Department</h1>

<!-- Bar chart with number and percentage of ideas per department -->
<div id="chart-container" >
    <canvas id="graphCanvas"></canvas>
</div>

<h1 class="margin-top">Number of contributors per Department</h1>

<!-- Doughnut chart with number of contributors per dept -->
<div id="doughnut-chart-container">
    <canvas id="doughnutGraphCanvas" height="300" width="500"></canvas>
</div>

<script>
    $(document).ready(function () {
        showGraph();
        showNumberOfContributors();
    });

    //Function to view the number and percentage of ideas submitted dept
    function showGraph() {
        $.post("num_of_ideas.php",
            function (data) {
                console.log(data);
                var dep_name = [];
                var ideas = [];
                var percent = [];

                for (var i in data) {
                    dep_name.push(data[i].DEPARTMENT_NAME);
                    ideas.push(data[i].num_ideas);
                    percent.push(data[i].percent_ideas);
                }

                var chartdata = {
                    labels: dep_name,
                    datasets: [
                        {
                            label: 'Number of Ideas',
                            backgroundColor: '#49e2ff',
                            borderColor: '#46d5f1',
                            hoverBackgroundColor: '#CCCCCC',
                            hoverBorderColor: '#666666',
                            data: ideas
                        },
                        {
                            label: 'Percentage of Ideas',
                            backgroundColor: '#ff6384',
                            borderColor: '#ff6384',
                            hoverBackgroundColor: '#CCCCCC',
                            hoverBorderColor: '#666666',
                            data: percent
                        }
                    ]
                };

                var graphTarget = $("#graphCanvas");

                var barGraph = new Chart(graphTarget, {
                    type: 'bar',
                    data: chartdata
                });
            });
    }

    //Function to view the number of contributors per dept
    function showNumberOfContributors() {
        $.post("num_of_contributors.php",
            function (data) {
                console.log(data);
                var dep_name = [];
                var contributor_count = [];

                for (var i in data) {
                    dep_name.push(data[i].DEPARTMENT_NAME);
                    contributor_count.push(data[i].contributor_count);
                }

                var numOfContributors = {
                    labels: dep_name,
                    datasets: [
                        {
                            data: contributor_count,
                            backgroundColor: [
                                '#FFCE56',
                                '#00CC99',
                                '#FF9900'
                            ]
                        }
                    ]
                };

                var graphTarget = $("#doughnutGraphCanvas");

                var doughnutChart = new Chart(graphTarget, {
                    type: 'doughnut',
                    data: numOfContributors
                });
            }
        );
    }
</script>