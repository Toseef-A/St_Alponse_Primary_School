<?php
    include("connection.php");
    include("functions.php");

    session_start();

    // Moved the check_permission_and_message function call after including the required files
    check_permission_and_message($connection, __FILE__);

    // Retrieve user data
    $user_data = check_login($connection, get_allowed_permissions(__FILE__));

    $AttendanceID = "";
    $Attendance_Date = "";
    $Attendance_Status = "";
    $PupilID = "";
    $errorMessage = "";
    $successMessage = "";

    $query = "SELECT PupilID, Pupil_Name FROM pupil";
    $PupilResult = $connection->query($query);

    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        if (!isset($_GET["AttendanceID"])) {
            header("location: /test1/Attendance.php");
            exit;
        }

        $AttendanceID = $_GET["AttendanceID"];

        $EditAttendanceSQL = "SELECT * FROM attendance WHERE AttendanceID=?";
        $stmt = $connection->prepare($EditAttendanceSQL);
        $stmt->bind_param("i", $AttendanceID);
        $stmt->execute();
        $result = $stmt->get_result();

        if (!$result) {
            echo "Error executing query: " . $stmt->error;
            exit;
        }

        if ($result->num_rows == 0) {
            echo "No records found for AttendanceID: " . $AttendanceID;
            exit;
        }

        $row = $result->fetch_assoc();
        $AttendanceID = $row["AttendanceID"];
        $Attendance_Date = $row["Attendance_Date"];
        $Attendance_Status = $row["Attendance_Status"];
        $PupilID = $row["PupilID"];
    } else {
        $AttendanceID = $_POST["AttendanceID"];
        $Attendance_Date = $_POST["Attendance_Date"];
        $Attendance_Status = $_POST["Attendance_Status"];
        $PupilID = $_POST["PupilID"];

        // Validation
        if (empty($Attendance_Date) || empty($Attendance_Status) || empty($PupilID)) {
            $errorMessage = "All fields are required";
        } else {
            $AttendanceSQL = "UPDATE attendance SET Attendance_Date=?, Attendance_Status=?, PupilID=? WHERE AttendanceID=?";
            $stmt = $connection->prepare($AttendanceSQL);
            $stmt->bind_param("sssi", $Attendance_Date, $Attendance_Status, $PupilID, $AttendanceID);
            $stmt->execute();

            if ($stmt->error) {
                $errorMessage = "Error updating Attendance: " . $stmt->error;
            } else {
                $successMessage = "Attendance updated correctly";
            }
        }
    }

    // Close the database connection
    $connection->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Attendance</title>
    <!-- Link to Bootstrap 5.3.1 CSS library -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <!-- Class section -->
    <div id="Class" class="section">
        <!-- Container-->
        <div class="container">
            <!-- Row-->
            <div class="row">
                <!-- Column for Class form on medium and small screens -->
                <div class="col-md-12 col-md-offset-1 col-sm-10 col-sm-offset-1">

                <?php
                    if ( !empty($errorMessage) ) {
                        echo "
                        <div class='alert alert-warning alert-dismissible fade show' role='alert'>
                            <strong>$errorMessage</strong>
                            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                        </div>
                        ";
                    }
                ?>
                    <!-- Class form within a row -->
                    <form class="Class-form row " method="POST" action="EditAttendance.php" id="ClassForm">
                        <input type="hidden" name="AttendanceID" value="<?php echo $AttendanceID; ?>">

                            <div class="mb-3">
                                <!-- Label for the First name input field -->
                                <label class="col-sm-3" for="Attendance_Date">Attendance_Date:</label>
                                <!-- Text input for the name with placeholder and name attribute -->
                                <div class="col-sm-6">
                                    <input class="input" type="date" name="Attendance_Date" id="Attendance_Date" value="<?php echo $Attendance_Date; ?>">
                                </div>
                            </div>

                            <div class="mb-3">
                                <!-- Label for the Attendance_Status dropdown -->
                                <label class="col-sm-3" for="Attendance_Status">Attendance_Status:</label>
                                <!-- Dropdown menu for Attendance_Status with three options -->
                                <div class="col-sm-6">
                                    <select class="form-select" name="Attendance_Status" id="Attendance_Status">
                                        <option value="Present" <?php echo ($Attendance_Status === 'Present') ? 'selected' : ''; ?>>Present</option>
                                        <option value="Absent" <?php echo ($Attendance_Status === 'Absent') ? 'selected' : ''; ?>>Absent</option>
                                        <option value="Late" <?php echo ($Attendance_Status === 'Late') ? 'selected' : ''; ?>>Late</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="col-sm-3" for="PupilID">PupilID:</label>
                                <div class="col-sm-6">
                                    <select class="form-select" name="PupilID" id="PupilID">
                                        <?php
                                        while ($row = $PupilResult->fetch_assoc()) {
                                            $selected = ($row['PupilID'] == $PupilID) ? 'selected' : '';
                                            echo "<option value='" . $row['PupilID'] . "' $selected>" . $row['PupilID'] . " - " . $row['Pupil_Name'] . "</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>


                        <?php
                            if (!empty($successMessage)) {
                                echo "
                                <div class='row mb-3'>
                                    <div class='offset-sm-3 col-sm-6'>
                                        <div class='alert alert-success alert-dismissible fade show' role='alert'>
                                            <strong>$successMessage</strong>
                                            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                                        </div>
                                    </div>
                                </div>
                                ";
                            }
                        ?>

                        <!-- Full-width column for the submission button -->
                        <div class="row mb-3">
                            <div class="offset-sm-3 col-sm-3 d-grid">
                                <!-- Button for submitting the Class form -->
                                <button class="main-button underline-on-hover" type="submit">Submit</button>
                            </div>
                            <div class="col-sm-3 d-grid">
                                <a class="btn btn-outline-primary" href="Attendance.php" role="button">Cancel</a>
                            </div>
                        </div>
                    </form>  
                </div>
            </div>
        </div>
    </div>
    <!-- Class section -->

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>


</body>
</html>