<?php
include("connection.php");
include("functions.php");

session_start();

// Moved the check_permission_and_message function call after including the required files
check_permission_and_message($connection, __FILE__);

// Retrieve user data
$user_data = check_login($connection, get_allowed_permissions(__FILE__));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Class</title>
    <!-- Link to Bootstrap 5.3.1 CSS library -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
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
                            <!-- Home page link -->
                            <li class="nav-item">
                                <a class="nav-link underline-on-hover" href="index.php">Home Page</a>
                            </li>
                            <!-- Class link -->
                            <li class="nav-item">
                                <a class="nav-link underline-on-hover" href="Class.php">Class</a>
                            </li>
                            <!-- Class Subject link -->
                            <li class="nav-item">
                                <a class="nav-link underline-on-hover" href="ClassSubject.php">Class Subject</a>
                            </li>
                            <!-- Pupil link -->
                            <li class="nav-item">
                                <a class="nav-link underline-on-hover" href="Pupil.php">Pupil</a>
                            </li>
                            <!-- Teachers link -->
                            <li class="nav-item">
                                <a class="nav-link underline-on-hover" href="Teacher.php">Teachers</a>
                            </li>
                            <!-- User Sessions link -->
                            <li class="nav-item">
                                <a class="nav-link underline-on-hover" href="UserSessions.php">User Sessions</a>
                            </li>
                            <!-- Parents link -->
                            <li class="nav-item">
                                <a class="nav-link underline-on-hover" href="Parents.php">Parents</a>
                            </li>
                            <!-- Subject link -->
                            <li class="nav-item">
                                <a class="nav-link underline-on-hover" href="Subject.php">Subject</a>
                            </li>
                            <!-- Attendance link -->
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
    <h1>Class</h1>
    <br>
    <a class="btn btn-primary" href="AddNewClass.php" role="button">Add New Class</a>
    <table class="table">
        <thead>
            <tr>
                <th>ClassID</th>
                <th>Class_Name</th>
                <th>Class_Capacity</th>
                <th>Class_Room</th>
                <th>Functions</th>
            </tr>
        </thead>
       
        <tbody>
        <?php
            $sql = "SELECT * FROM class";
            $result = $connection -> query($sql);
 
            if (!$result) {
                die("Invalid query: " . $connection -> error);
            }
 
            while ($row = $result -> fetch_assoc()) {
                echo "<tr>
                    <td>" . $row["ClassID"] . "</td>
                    <td>" . $row["Class_Name"] . "</td>
                    <td>" . $row["Class_Capacity"] . "</td>
                    <td>" . $row["Class_Room"] . "</td>
                    <td>
                    <button onclick=\"location.href='EditClass.php?action=EditClass&ClassID={$row['ClassID']}'\">Edit</button>
                    <button onclick=\"location.href='DeleteClass.php?action=DeleteClass&ClassID={$row['ClassID']}'\">Delete</button>
                    </td>
                </tr>";
            }
            ?>
        </tbody>
    </table>
   
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>