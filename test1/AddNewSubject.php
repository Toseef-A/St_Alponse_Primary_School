<?php
    // Include necessary files
    include("connection.php");
    include("functions.php");

    // Start session
    session_start();

    // Move the check_permission_and_message function call after including the required files
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

    // Process form submission
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $Subject_Name = $_POST["Subject_Name"];
        $Subject_Timetable = $_POST["Subject_Timetable"];
        $Subject_StartDate = $_POST["Subject_StartDate"];
        $Subject_EndDate = $_POST["Subject_EndDate"];

        // Validation
        if (empty($Subject_Name) || empty($Subject_Timetable) || empty($Subject_StartDate) || empty($Subject_EndDate)) {
            $errorMessage = "All fields are required";
        } else {
            // Validate name length
            if (strlen($Subject_Name) > 255) {
                $errorMessage = "Subject Name must be at most 255 characters long.";
            }

            // Validate timetable length
            if (strlen($Subject_Timetable) > 255) {
                $errorMessage = "Subject Timetable must be at most 255 characters long.";
            }

            if (empty($errorMessage)) {
                do {
                    // Add new subject to the database
                    $AddNewSubjectSQL = "INSERT INTO subject (Subject_Name, Subject_Timetable, Subject_StartDate, Subject_EndDate)" . 
                        "VALUES (?, ?, ?, ?)";
                    $stmt = $connection->prepare($AddNewSubjectSQL);
                    $stmt->bind_param("ssss", $Subject_Name, $Subject_Timetable, $Subject_StartDate, $Subject_EndDate);
                    $stmt->execute();

                    if (!$stmt->error) {
                        $Subject_Name = "";
                        $Subject_Timetable = "";
                        $Subject_StartDate = "";
                        $Subject_EndDate = "";

                        $successMessage = "Subject has been added";
                    } else {
                        $errorMessage = "Query Error: " . $stmt->error;
                    }

                } while (false);
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Subject</title>
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
                <h2 class="title white-text">Add New Subject</h2>
            </div>
                <?php
                    if (!empty($errorMessage)) {
                        echo "
                        <div class='alert alert-warning alert-dismissible fade show' role='alert'>
                            <strong>$errorMessage</strong>
                            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                        </div>
                        ";
                    }
                ?>
                    <!-- Class form within a row -->
                    <form class="Class-form row " method="POST" action="AddNewSubject.php" id="ClassForm">
                        <input type="hidden" name="SubjectID" value="<?php echo $SubjectID; ?>">

                            <div class="row-mb-3">
                                <!-- Label for the Subject Name input field -->
                                <label class="col-sm-3" for="Subject_Name">Subject Name:</label>
                                <!-- Text input for the name with placeholder and name attribute -->
                                <div class="col-sm-6">
                                    <input class="input" type="text" name="Subject_Name" id="Subject_Name" value="<?php echo $Subject_Name; ?>">
                                </div>
                            </div>

                            <div class="row-mb-3">
                                <!-- Label for the Subject Timetable input field -->
                                <label class="col-sm-3" for="Subject_Timetable">Subject Timetable:</label>
                                <!-- Text input for the name with placeholder and name attribute -->
                                <div class="col-sm-6">
                                    <input class="input" type="text" name="Subject_Timetable" id="Subject_Timetable" value="<?php echo $Subject_Timetable; ?>">
                                </div>
                            </div>

                            <div class="row-mb-3">
                                <!-- Label for the Subject StartDate input field -->
                                <label class="col-sm-3" for="Subject_StartDate">Subject StartDate:</label>
                                <!-- Date input for the start date with placeholder and name attribute -->
                                <div class="col-sm-6">
                                    <input class="input" type="date" name="Subject_StartDate" id="Subject_StartDate" value="<?php echo $Subject_StartDate; ?>">
                                </div>
                            </div>

                            <div class="row-mb-3">
                                <!-- Label for the Subject EndDate input field -->
                                <label class="col-sm-3" for="Subject_EndDate">Subject EndDate:</label>
                                <!-- Date input for the end date with placeholder and name attribute -->
                                <div class="col-sm-6">
                                    <input class="input" type="date" name="Subject_EndDate" id="Subject_EndDate" value="<?php echo $Subject_EndDate; ?>">
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
                                <!-- Button for canceling and redirecting to Subject.php -->
                                <a class="btn btn-outline-primary" href="Subject.php" role="button">Cancel</a>
                            </div>
                        </div>
                    </form>
        </div>
    </div>
    <!-- /Class section -->

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>