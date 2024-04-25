<?php
    // Include necessary files for database connection and functions
    include("connection.php");
    include("functions.php");
    
    // Start the session
    session_start();
    
    // Check user permissions and display any messages
    check_permission_and_message($connection, __FILE__);
    
    // Retrieve user data
    $user_data = check_login($connection, get_allowed_permissions(__FILE__));

    // Initialize variables for form fields and messages
    // Variable to store the selected SubjectID
    $SubjectID = "";
    // Variable to store the selected ClassID
    $ClassID = "";
    // Variable to store error messages during form submission
    $errorMessage = "";
    // Variable to store success messages after successful form submission
    $successMessage = "";

    // Fetch subjects and classes for dropdowns
    $query = "SELECT SubjectID, Subject_Name FROM subject";
    $SubjectResult = $connection->query($query);

    $query = "SELECT ClassID, Class_Name FROM class";
    $ClassResult = $connection->query($query);

    // Handle form submission
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Retrieve form data
        $SubjectID = $_POST["SubjectID"];
        $ClassID = $_POST["ClassID"];

        // Check if both SubjectID and ClassID are provided
        if (empty($SubjectID) || empty($ClassID)) {
            $errorMessage = "Both Subject and Class are required";
        } else {
            // Check if the provided SubjectID exists
            $subjectCheckSQL = "SELECT SubjectID FROM subject WHERE SubjectID=?";
            // Prepare the SQL statement for checking the existence of the provided SubjectID
            $subjectStmt = $connection->prepare($subjectCheckSQL);
            // Bind the parameter to the prepared statement for checking SubjectID existence
            $subjectStmt->bind_param("i", $SubjectID);
            // Execute the prepared statement to check the existence of the provided SubjectID
            $subjectStmt->execute();
            // Get the result set after executing the statement
            $subjectResult = $subjectStmt->get_result();

            if ($subjectResult->num_rows == 0) {
                $errorMessage = "Invalid SubjectID";
            } else {
                // Check if the provided ClassID exists
                $classCheckSQL = "SELECT ClassID FROM class WHERE ClassID=?";
                // Prepare the SQL statement for checking the existence of the provided ClassID
                $classStmt = $connection->prepare($classCheckSQL);
                // Bind the parameter to the prepared statement for checking ClassID existence
                $classStmt->bind_param("i", $ClassID);
                // Execute the prepared statement to check the existence of the provided ClassID
                $classStmt->execute();
                // Get the result set after executing the statement
                $classResult = $classStmt->get_result();

                if ($classResult->num_rows == 0) {
                    $errorMessage = "Invalid ClassID";
                } else {
                    // Insert new Class Subject
                    $AddNewClassSubjectSQL = "INSERT INTO classsubject (SubjectID, ClassID) VALUES (?, ?)";
                    // Prepare the SQL statement for adding a new Class Subject to the database
                    $insertStmt = $connection->prepare($AddNewClassSubjectSQL);
                    // Bind the parameters to the prepared statement for adding a new Class Subject
                    $insertStmt->bind_param("ii", $SubjectID, $ClassID);
                    // Execute the prepared statement to add a new Class Subject to the database
                    $insertStmt->execute();
                    if ($insertStmt->error) {
                        $errorMessage = "Error adding Class Subject: " . $insertStmt->error;
                    } else {
                        $successMessage = "Class Subject has been added";
                    }
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
    <title>Add New Class Subject</title>
    <!-- Link to Bootstrap 5.3.1 CSS library -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <!-- Class section -->
    <div id="Class" class="section">
        <!-- Container-->
        <div class="container">
            <!-- Section header-->
            <div class="section-header text-center">
                <!-- Title-->
                <h2 class="title white-text">Add New Class Subject</h2>
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

            <!-- Class form -->
            <form class="Class-form row" method="POST" action="AddNewClassSubject.php" id="ClassForm">
                <!-- Hidden input to store ClassSubjectID -->
                <input type="hidden" name="ClassSubjectID" value="<?php echo $ClassSubjectID; ?>">

                <!-- Dropdown for selecting SubjectID -->
                <div class="mb-3">
                    <label class="col-form-label" for="SubjectID">SubjectID:</label>
                    <div class="col-sm-6">
                        <select class="form-select" name="SubjectID" id="SubjectID">
                            <?php
                            // Dropdown options for Subject
                            while ($row = $SubjectResult->fetch_assoc()) {
                                echo "<option value='" . $row['SubjectID'] . "'>" . $row['SubjectID'] . " - " . $row['Subject_Name'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <!-- Dropdown for selecting ClassID -->
                <div class="mb-3">
                    <label class="col-sm-3" for="ClassID">ClassID:</label>
                    <div class="col-sm-6">
                        <select class="form-select" name="ClassID" id="ClassID">
                            <?php
                            // Populate options for Class dropdown
                            while ($row = $ClassResult->fetch_assoc()) {
                                echo "<option value='" . $row['ClassID'] . "'>" . $row['ClassID'] . " - " . $row['Class_Name'] . "</option>";
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

                <!-- Submittion and cancellation buttons -->
                <div class="row mb-3">
                    <!-- Submit button -->
                    <div class="offset-sm-3 col-sm-3 d-grid">
                        <button class="main-button underline-on-hover" type="submit">Submit</button>
                    </div>
                    <!-- Cancel button -->
                    <div class="col-sm-3 d-grid">
                        <a class="btn btn-outline-primary" href="ClassSubject.php" role="button">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- /Class section -->

    <!-- Include jQuery and Bootstrap JS libraries -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
```