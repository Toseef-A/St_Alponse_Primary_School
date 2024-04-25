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
    // Variable to store the class name
    $Class_Name = "";
    // Variable to store the class capacity
    $Class_Capacity = "";
    // Variable to store the class room
    $Class_Room = "";
    // Variable to store error messages during form submission
    $errorMessage = "";
    // Variable to store success messages after successful form submission
    $successMessage = "";

    // Handle form submission
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Retrieve form data
        $Class_Name = $_POST["Class_Name"];
        $Class_Capacity = $_POST["Class_Capacity"];
        $Class_Room = $_POST["Class_Room"];

        // Validation
        // Check id anyhting is empty
        if (empty($Class_Name) || empty($Class_Capacity) || empty($Class_Room)) {
            $errorMessage = "All fields are required";
        } else {
            // Enforce max character limit for Class_Name and Class_Room
            $maxCharLimit = 255;
            if (strlen($Class_Name) > $maxCharLimit || strlen($Class_Room) > $maxCharLimit) {
                $errorMessage = "Class Name and Class Room must be at most $maxCharLimit characters long.";
            } else {
                do {
                    // Prepare the SQL statement for adding a new class to the database
                    $AddNewClassSQL = "INSERT INTO class (Class_Name, Class_Capacity, Class_Room) VALUES (?, ?, ?)";
                    // Bind the parameters to the prepared statement for adding a new class
                    $stmt = $connection->prepare($AddNewClassSQL);
                    $stmt->bind_param("sss", $Class_Name, $Class_Capacity, $Class_Room);
                    // Execute the prepared statement to add a new class to the database and store the result
                    $result = $stmt->execute();
                    // Close the prepared statement after executing to free up resources
                    $stmt->close();

                    if (!$result) {
                        $errorMessage = "Invalid Query: " . $connection->error;
                        break;
                    }

                    // Reset form fields on successful submission
                    $Class_Name = "";
                    $Class_Capacity = "";
                    $Class_Room = "";

                    $successMessage = "Class has been added";

                } while (false);
            }
        }
    }
?>
<!-- HTML document starts here -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Class</title>
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
                <h2 class="title white-text">Add New Class</h2>
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
            <form class="Class-form row" method="POST" action="AddNewClass.php" id="ClassForm">
                <input type="hidden" name="ClassID" value="<?php echo $ClassID; ?>">

                <div class="row">
                    <!-- Label for the Class_Name input field -->
                    <label class="col-3" for="Class_Name">Class Name:</label>
                    <!-- Text input for the class name -->
                    <div class="col-sm-6">
                        <input class="input" type="text" name="Class_Name" id="Class_Name" value="<?php echo $Class_Name; ?>">
                    </div>
                </div>

                <div class="row">
                    <!-- Label for the Class_Capacity input field -->
                    <label class="col-3" for="Class_Capacity">Class Capacity:</label>
                    <!-- Text input for the class capacity -->
                    <div class="col-sm-6">
                        <input class="input" type="number" name="Class_Capacity" id="Class_Capacity" value="<?php echo $Class_Capacity; ?>">
                    </div>
                </div>

                <div class="row">
                    <!-- Label for the Class_Room input field -->
                    <label class="col-3" for="Class_Room">Class Room:</label>
                    <!-- Text input for the class room -->
                    <div class="col-sm-6">
                        <input class="input" type="text" name="Class_Room" id="Class_Room" value="<?php echo $Class_Room; ?>">
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
                        <!-- Button for submitting the Class form -->
                        <button class="main-button underline-on-hover" type="submit">Submit</button>
                    </div>
                    <div class="col-sm-3 d-grid">
                        <!-- Button to cancel and redirect to Class page -->
                        <a class="btn btn-outline-primary" href="Class.php" role="button">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- /Class section -->

    <!-- Include jQuery and Bootstrap JS library -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
