<?php
// Include the database connection file and functions
include("connection.php");
include("functions.php");

// Start a new session
session_start();

// Call the check_permission_and_message function to ensure user permissions
// This function checks if the user has permission to access this page
check_permission_and_message($connection, __FILE__);

// Retrieve user data and check login status
// This function verifies if the user is logged in and has the required permissions
$user_data = check_login($connection, get_allowed_permissions(__FILE__));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Index page</title>
    <!-- Include a custom CSS file (Index.css) for styling -->
    <link rel="stylesheet" href="Index.css">
    
    <!-- Link to Bootstrap 5.3.1 CSS library -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Charts -->
    <!-- Load Google Charts library -->
    <script src="https://www.gstatic.com/charts/loader.js"></script>
    <script>
        // Load Google Charts and draw a bar chart
        google.charts.load("current", { packages: ["corechart"] });
        google.charts.setOnLoadCallback(drawChart);

        // Function to draw a bar chart
        function drawChart() {
            // Create a DataTable for chart data
            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Class');
            data.addColumn('number', 'Number of Students');

            <?php
            // Define specific class names
            $classNames = ['Reception Year', 'Year 1', 'Year 2', 'Year 3', 'Year 4', 'Year 5', 'Year 6'];

            // Fetch data from the database
            $sql = "SELECT ClassID, COUNT(*) AS NumberOfStudents FROM pupil GROUP BY ClassID";
            $result = $connection->query($sql);

            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    $classID = $row['ClassID'];
                    $className = isset($classNames[$classID - 1]) ? $classNames[$classID - 1] : "Unknown";
                    // Add data rows for the chart
                    echo "data.addRow(['" . $className . "', " . $row["NumberOfStudents"] . "]);";
                }
            }
            ?>

            // Define chart options
            var options = {
                title: "Number of Students in Each Class",
                width: 600,
                height: 400,
                legend: { position: "none" },
                hAxis: {
                    title: 'Number of Students'
                },
                vAxis: {
                    title: 'Class'
                }
            };

            // Create a bar chart and display it in the specified HTML element
            var chart = new google.visualization.BarChart(document.getElementById("classBarChart"));
            chart.draw(data, options);
        }
    </script>
</head>
<body>


<!-- Header section -->
<header>
        <!-- Navigation bar with a dark theme -->
        <nav class="navbar navbar-expand-sm navbar-dark bg-dark">
            <div class="container-fluid">
                <!-- Navbar toggler for small screens -->
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#collapsibleNavbar">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <!-- Navbar items -->
                <div class="collapse navbar-collapse" id="collapsibleNavbar">
                    <ul class="navbar-nav ms-auto">
                        <!-- Contact link -->
                        <li class="nav-item">
                            <a class="nav-link underline-on-hover" href="index.php">Home Page</a>
                        </li>
                        <!-- Home link -->
                        <li class="nav-item">
                            <a class="nav-link underline-on-hover" href="Class.php">Class</a>
                        </li>
                        <!-- Home link -->
                        <li class="nav-item">
                            <a class="nav-link underline-on-hover" href="ClassSubject.php">Class Subject</a>
                        </li>
                        <!-- About link -->
                        <li class="nav-item">
                            <a class="nav-link underline-on-hover" href="Pupil.php">Pupil</a>
                        </li>
                        <!-- Menu link (fix the typo here) -->
                        <li class="nav-item">
                            <a class="nav-link underline-on-hover" href="Teacher.php">Teachers</a>
                        </li>
                        <!-- Menu link (fix the typo here) -->
                        <li class="nav-item">
                            <a class="nav-link underline-on-hover" href="UserSessions.php">User Sessions</a>
                        </li>
                        <!-- Reservation link -->
                        <li class="nav-item">
                            <a class="nav-link underline-on-hover" href="Parents.php">Parents</a>
                        </li>
                        <!-- Contact link -->
                        <li class="nav-item">
                            <a class="nav-link underline-on-hover" href="Subject.php">Subject</a>
                        </li>
                        <!-- Contact link -->
                        <li class="nav-item">
                            <a class="nav-link underline-on-hover" href="Attendance.php">Attendance</a>
                        </li>
                    </ul>
                </div>
                <!-- /Navbar items -->
            </div>
        </nav>
        <!-- /Navigation bar with a dark theme -->
    </header>
    <!--/Header section-->


	<a href="logout.php">Logout</a>
    <h1>This is the index page</h1>

    <br>
    Hello, <?php echo isset($user_data['User_Name']) ? $user_data['User_Name'] : ''; ?>

    <!-- Add a container for the chart -->
    <div id="classBarChart" style="width: 900px; height: 500px;"></div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>