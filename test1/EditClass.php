<?php
    include("connection.php");
    include("functions.php");
    
    session_start();
    
    // Moved the check_permission_and_message function call after including the required files
    check_permission_and_message($connection, __FILE__);
    
    // Retrieve user data
    $user_data = check_login($connection, get_allowed_permissions(__FILE__));

    $ClassID = "";
    $Class_Name = "";
    $Class_Capacity = "";
    $Class_Room = "";
    $errorMessage = "";
    $successMessage = "";

    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        if (!isset($_GET["ClassID"])) {
            header("location: /test1/Class.php");
            exit;
        }

        $ClassID = $_GET["ClassID"];

        $EditClassSQL = "SELECT * FROM class WHERE ClassID=?";
        $stmt = $connection->prepare($EditClassSQL);
        $stmt->bind_param("i", $ClassID);
        $stmt->execute();
        $result = $stmt->get_result();

        if (!$result) {
            echo "Error executing query: " . $stmt->error;
            exit;
        }

        if ($result->num_rows == 0) {
            echo "No records found for ClassID: " . $ClassID;
            exit;
        }

        $row = $result->fetch_assoc();
        $ClassID = $row["ClassID"];
        $Class_Name = $row["Class_Name"];
        $Class_Capacity = $row["Class_Capacity"];
        $Class_Room = $row["Class_Room"];
        
    } else {
        $ClassID = $_POST["ClassID"];
        $Class_Name = $_POST["Class_Name"];
        $Class_Capacity = $_POST["Class_Capacity"];
        $Class_Room = $_POST["Class_Room"];

        // Validation
        if (empty($ClassID) || empty($Class_Name) || empty($Class_Capacity) || empty($Class_Room)) {
            $errorMessage = "All fields are required";
        } else {
            // Enforce max character limit for Class_Name and Class_Room
            $maxCharLimit = 255;
            if (strlen($Class_Name) > $maxCharLimit || strlen($Class_Room) > $maxCharLimit) {
                $errorMessage = "Class Name and Class Room must be at most $maxCharLimit characters long.";
            } else {
                // Check for numeric values
                if (!is_numeric($ClassID)) {
                    $errorMessage = "Invalid ClassID";
                } else {
                    $ClassSQL = "UPDATE class SET Class_Name=?, Class_Capacity=?, Class_Room=? WHERE ClassID=?";
                    $stmt = $connection->prepare($ClassSQL);
                    $stmt->bind_param("sssi", $Class_Name, $Class_Capacity, $Class_Room, $ClassID);
                    $stmt->execute();

                    if ($stmt->error) {
                        $errorMessage = "Error updating Class: " . $stmt->error;
                    } else {
                        $successMessage = "Class updated correctly";
                    }
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
    <title>Edit Class</title>
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
                    <form class="Class-form row " method="POST" action="EditClass.php" id="ClassForm">
                        <input type="hidden" name="ClassID" value="<?php echo $ClassID; ?>">

                            <div class="row">
                                <!-- Label for the First name input field -->
                                <label class="col-3" for="Class_Name">Class Name:</label>
                                <!-- Text input for the name with placeholder and name attribute -->
                                <div class="col-sm-6">
                                    <input class="input" type="text" name="Class_Name" id="Class_Name" value="<?php echo $Class_Name; ?>">
                                </div>
                            </div>

                            <div class="row">
                                <!-- Label for the StudentiD input field -->
                                <label class="col-3" for="Class_Capacity">Class_Capacity:</label>
                                <!-- Text input for the name with placeholder and name attribute -->
                                <div class="col-sm-6">
                                    <input class="input" type="number" name="Class_Capacity" id="Class_Capacity" value="<?php echo $Class_Capacity; ?>">
                                </div>
                            </div>

                            <div class="row">
                                <!-- Label for the email input field -->
                                <label class="col-3" for="Class_Room">Class_Room:</label>
                                <!-- Email input for email address with placeholder and name attribute -->
                                <div class="col-sm-6">
                                    <input class="input" type="text" name="Class_Room" id="Class_Room" value="<?php echo $Class_Room; ?>">
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
                                <a class="btn btn-outline-primary" href="Class.php" role="button">Cancel</a>
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