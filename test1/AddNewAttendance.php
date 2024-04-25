<?php
    // Include necessary files for database connection and functions
    // Include the file for database connection
    include("connection.php");
    // Include the file containing custom functions
    include("functions.php");

    // Start the session
    session_start();

    // Check user permissions and display any messages
    check_permission_and_message($connection, __FILE__);

    // Retrieve user data
    $user_data = check_login($connection, get_allowed_permissions(__FILE__));

    // Initialize variables for form fields and messages
    // Variable to store the selected attendance date
    $Attendance_Date = "";
    // Variable to store the selected attendance status
    $Attendance_Status = "";
    // Variable to store the selected pupil ID
    $PupilID = "";
    // Variable to store error messages during form submission
    $errorMessage = "";
    // Variable to store success messages after successful form submission
    $successMessage = "";

    // Retrieve pupil data for dropdown
    $query = "SELECT PupilID, Pupil_Name FROM pupil";
    $PupilResult = $connection->query($query);

    // Handle form submission
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $Attendance_Date = $_POST["Attendance_Date"];
        $Attendance_Status = $_POST["Attendance_Status"];
        $PupilID = $_POST["PupilID"];

        // Validate required fields
        // Check if anything is empty
        if (empty($Attendance_Date) || empty($Attendance_Status) || empty($PupilID)) {
            $errorMessage = "All fields are required";
        } else {
            // Check for numeric values
            if (!is_numeric($PupilID)) {
                $errorMessage = "Invalid PupilID";
            } else {
                // Inserts new attendance to the database
                $AddNewAttendanceSQL = "INSERT INTO Attendance (Attendance_Date, Attendance_Status, PupilID) VALUES (?, ?, ?)";
                // Prepare the SQL statement for adding a new attendance record to the database
                $stmt = $connection->prepare($AddNewAttendanceSQL);
                // Bind the parameters to the prepared statement for adding a new attendance record
                $stmt->bind_param("sss", $Attendance_Date, $Attendance_Status, $PupilID);
                // Execute the prepared statement to add a new attendance record to the database and store the result
                $result = $stmt->execute();
                // Close the prepared statement after executing to free up resources
                $stmt->close();


                if (!$result) {
                    $errorMessage = "Invalid Query: " . $connection->error;
                } else {
                    // Reset form fields on successful submission
                    $Attendance_Date = "";
                    $Attendance_Status = "";
                    $PupilID = "";

                    $successMessage = "Attendance has been added";
                }
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Attendance</title>
    <!-- Link to Bootstrap 5.3.1 CSS library -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    
    <!-- Add New Attendance section -->
    <div id="Class" class="section">
        <!-- Container-->
        <div class="container">
            <!-- Section header-->
            <div class="section-header text-center">
                <!-- Title-->
                <h2 class="title white-text">Add New Attendance</h2>
            </div>
            
            <?php
                // Display error message
                if (!empty($errorMessage)) {
                    echo "
                    <div class='alert alert-warning alert-dismissible fade show' role='alert'>
                        <strong>$errorMessage</strong>
                        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                    </div>
                    ";
                }
            ?>

            <!-- Attendance form-->
            <form class="Class-form row" method="POST" action="AddNewAttendance.php" id="ClassForm">
                <input type="hidden" name="AttendanceID" value="<?php echo $AttendanceID; ?>">

                <div class="mb-3">
                    <!-- Label for the Attendance_Date input field -->
                    <label class="col-sm-3" for="Attendance_Date">Attendance_Date:</label>
                    <!-- Text input for the date attribute -->
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
                            // Dropdown menu with pupil data
                            while ($row = $PupilResult->fetch_assoc()) {
                                echo "<option value='" . $row['PupilID'] . "'>" . $row['PupilID'] . " - " . $row['Pupil_Name'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <?php
                    // Display success message
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

                <!-- Submission and cancelation button -->
                <div class="row mb-3">
                    <div class="offset-sm-3 col-sm-3 d-grid">
                        <!-- Button for submitting the Attendance form -->
                        <button class="main-button underline-on-hover" type="submit">Submit</button>
                    </div>
                    <div class="col-sm-3 d-grid">
                        <!-- Button to cancel and redirect to Attendance page -->
                        <a class="btn btn-outline-primary" href="Attendance.php" role="button">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- /Add New Attendance section -->

    <!-- Include jQuery and Bootstrap JS library -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
