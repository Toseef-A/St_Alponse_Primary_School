<?php
    include("connection.php");
    include("functions.php");
    
    session_start();
    
    // Moved the check_permission_and_message function call after including the required files
    check_permission_and_message($connection, __FILE__);
    
    // Retrieve user data
    $user_data = check_login($connection, get_allowed_permissions(__FILE__));

    $SubjectID = "";
    $Subject_Name = "";
    $Subject_Timetable = "";
    $Subject_StartDate = "";
    $Subject_EndDate = "";
    $errorMessage = "";
    $successMessage = "";

    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        if (!isset($_GET["SubjectID"])) {
            header("location: /test1/Subject.php");
            exit;
        }

        $SubjectID = $_GET["SubjectID"];

        $EditSubjectSQL = "SELECT * FROM subject WHERE SubjectID=?";
        $stmt = $connection->prepare($EditSubjectSQL);
        $stmt->bind_param("i", $SubjectID);
        $stmt->execute();
        $result = $stmt->get_result();

        if (!$result) {
            echo "Error executing query: " . $stmt->error;
            exit;
        }

        if ($result->num_rows == 0) {
            echo "No records found for SubjectID: " . $SubjectID;
            exit;
        }

        $row = $result->fetch_assoc();
        $SubjectID = $row["SubjectID"];
        $Subject_Name = $row["Subject_Name"];
        $Subject_Timetable = $row["Subject_Timetable"];
        $Subject_StartDate = $row["Subject_StartDate"];
        $Subject_EndDate = $row["Subject_EndDate"];
        
    } else {
        $SubjectID = $_POST["SubjectID"];
        $Subject_Name = $_POST["Subject_Name"];
        $Subject_Timetable = $_POST["Subject_Timetable"];
        $Subject_StartDate = $_POST["Subject_StartDate"];
        $Subject_EndDate = $_POST["Subject_EndDate"];

        // Check for empty values
        if (empty($SubjectID) || empty($Subject_Name) || empty($Subject_Timetable) || empty($Subject_StartDate) || empty($Subject_EndDate)) {
            $errorMessage = "All fields are required";
        } else {
            // Check for numeric values
            if (!is_numeric($SubjectID)) {
                $errorMessage = "Invalid SubjectID";
            }

            // Validate name length
            if (strlen($Subject_Name) > 255) {
                $errorMessage = "Subject Name must be at most 255 characters long.";
            }

            // Validate timetable length
            if (strlen($Subject_Timetable) > 255) {
                $errorMessage = "Subject Timetable must be at most 255 characters long.";
            }

            // Add more validations as needed...

            if (empty($errorMessage)) {
                $SubjectSQL = "UPDATE subject SET Subject_Name=?, Subject_Timetable=?, Subject_StartDate=?, Subject_EndDate=? WHERE SubjectID=?";
                $stmt = $connection->prepare($SubjectSQL);
                $stmt->bind_param("ssssi", $Subject_Name, $Subject_Timetable, $Subject_StartDate, $Subject_EndDate, $SubjectID);
                $stmt->execute();

                if ($stmt->error) {
                    $errorMessage = "Error updating Subject: " . $stmt->error;
                } else {
                    $successMessage = "Subject updated correctly";
                }
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
    <title>Edit Subject</title>
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
                    <form class="Class-form row " method="POST" action="EditSubject.php" id="ClassForm">
                        <input type="hidden" name="SubjectID" value="<?php echo $SubjectID; ?>">

                            <div class="row-mb-3">
                                <!-- Label for the First name input field -->
                                <label class="col-sm-3" for="Subject_Name">Subject Name:</label>
                                <!-- Text input for the name with placeholder and name attribute -->
                                <div class="col-sm-6">
                                    <input class="input" type="text" name="Subject_Name" id="Subject_Name" value="<?php echo $Subject_Name; ?>">
                                </div>
                            </div>

                            <div class="row-mb-3">
                                <!-- Label for the StudentiD input field -->
                                <label class="col-sm-3" for="Subject_Timetable">Subject_Timetable:</label>
                                <!-- Text input for the name with placeholder and name attribute -->
                                <div class="col-sm-6">
                                    <input class="input" type="text" name="Subject_Timetable" id="Subject_Timetable" value="<?php echo $Subject_Timetable; ?>">
                                </div>
                            </div>

                            <div class="row-mb-3">
                                <!-- Label for the email input field -->
                                <label class="col-sm-3" for="Subject_StartDate">Subject_StartDate:</label>
                                <!-- Email input for email address with placeholder and name attribute -->
                                <div class="col-sm-6">
                                    <input class="input" type="date" name="Subject_StartDate" id="Subject_StartDate" value="<?php echo $Subject_StartDate; ?>">
                                </div>
                            </div>

                            <div class="row-mb-3">
                                <!-- Label for the phone input field -->
                                <label class="col-sm-3" for="Subject_EndDate">Subject_EndDate:</label>
                                <!-- Tel input for phone number with placeholder and name attribute -->
                                <div class="col-sm-6">
                                    <input class="input" type="date" name="Subject_EndDate" id="Subject_EndDate" value="<?php echo $Subject_EndDate; ?>">
                                </div>
                            </div>

                        <?php
                            if ( !empty($successMessage) ) {
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
                                <a class="btn btn-outline-primary" href="Subject.php" role="button">Cancel</a>
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