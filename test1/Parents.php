<?php
    include("connection.php");
    include("functions.php");
    //
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
    <title>Parents</title>
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
    <!-- Logout button -->
    <a href="logout.php">Logout</a>
    <!-- Title -->
    <h1>Parents</h1>
    <br>
    <!-- Add new Parent button -->
    <a class="btn btn-primary" href="AddNewParent.php" role="button">Add New Parent</a>
    <table class="table">
        <!-- Table Head -->
        <thead>
            <tr>
                <!-- Table Attributes -->
                <th>ParentID</th>
                <th>Parent_Name</th>
                <th>Parent_Address</th>
                <th>Parent_Email</th>
                <th>Parent_PhoneNumber</th>
                <th>Parent_DateOfBirth</th>
                <th>Parent_Gender</th>
                <th>PupilID</th>
                <th>Functions</th>
            </tr>
        </thead>
        <!-- /Table Head -->
       <!-- Table body -->
        <tbody>
        <?php
            // Connect parent table
            $sql = "SELECT * FROM parents";
            $result = $connection -> query($sql);
    
            if (!$result) {
                die("Invalid query: " . $connection -> error);
            }
            // Get the Attributes from the table for specific ID
            while ($row = $result -> fetch_assoc()) {
                echo "<tr>
                    <td>" . $row["ParentID"] . "</td>
                    <td>" . $row["Parent_Name"] . "</td>
                    <td>" . $row["Parent_Address"] . "</td>
                    <td>" . $row["Parent_Email"] . "</td>
                    <td>" . $row["Parent_PhoneNumber"] . "</td>
                    <td>" . $row["Parent_DateOfBirth"] . "</td>
                    <td>" . $row["Parent_Gender"] . "</td>
                    <td>" . $row["PupilID"] . "</td>
                    <td>
                        <button onclick=\"location.href='EditParent.php?action=EditParent&ParentID={$row['ParentID']}'\">Edit</button>
                        <button onclick=\"location.href='DeleteParent.php?action=DeleteParent&ParentID={$row['ParentID']}'\">Delete</button>
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