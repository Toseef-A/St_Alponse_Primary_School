<?php
    // Include necessary files
    include("connection.php");
    include("functions.php");

    // Start the session
    session_start();

    // Moved the check_permission_and_message function call after including the required files
    check_permission_and_message($connection, __FILE__);

    // Retrieve user data
    $user_data = check_login($connection, get_allowed_permissions(__FILE__));

    // Initialize variables for form fields and messages
    $Pupil_Name = "";               // Initialize variable for Pupil Name
    $Pupil_Address = "";            // Initialize variable for Pupil Address
    $Pupil_PhoneNumber = "";        // Initialize variable for Pupil Phone Number
    $Pupil_DateOfBirth = "";        // Initialize variable for Pupil Date of Birth
    $Pupil_MedicalInformation = ""; // Initialize variable for Pupil Medical Information
    $Pupil_Grade = "";              // Initialize variable for Pupil Grade
    $ClassID = "";                  // Initialize variable for Class ID
    $errorMessage = "";             // Initialize variable for error messages
    $successMessage = "";           // Initialize variable for success messages


    // Query to retrieve ClassID and Class_Name for dropdown
    $query = "SELECT ClassID, Class_Name FROM class";
    $ClassResult = $connection->query($query);

    // Process form submission
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Retrieve form data
        $Pupil_Name = $_POST["Pupil_Name"];
        $Pupil_Address = $_POST["Pupil_Address"];
        $Pupil_PhoneNumber = $_POST["Pupil_PhoneNumber"];
        $Pupil_DateOfBirth = $_POST["Pupil_DateOfBirth"];
        $Pupil_MedicalInformation = $_POST["Pupil_MedicalInformation"];
        $Pupil_Grade = $_POST["Pupil_Grade"];
        $ClassID = $_POST["ClassID"];

        // Check for empty values
        if (empty($Pupil_Name) || empty($Pupil_Address) || empty($Pupil_PhoneNumber) || empty($Pupil_DateOfBirth) || empty($Pupil_Grade) || empty($ClassID)) {
            $errorMessage = "All fields are required";
        } else {
            // Validate name length
            if (strlen($Pupil_Name) > 255) {
                $errorMessage = "Name must be at most 255 characters long.";
            }

            // Validate address length
            if (strlen($Pupil_Address) > 255) {
                $errorMessage = "Address must be at most 255 characters long.";
            }

            // Validate phone number format
            if (!preg_match("/^\d{10}$/", $Pupil_PhoneNumber)) {
                $errorMessage = "Invalid phone number. Must have 10 digits.";
            }

            if (empty($errorMessage)) {
                do {
                    // Query to retrieve the count of pupils for the selected class
                    // SQL query to count pupils in a specific class
                    $query = "SELECT COUNT(*) as Pupil_Count FROM pupil WHERE ClassID = ?"; 
                    // Prepare the SQL statement
                    $stmt = $connection->prepare($query);         
                    // Bind the parameter (ClassID) to the prepared statement    
                    $stmt->bind_param("i", $ClassID); 
                    // Execute the prepared statement                
                    $stmt->execute();                                
                    // Get the result of the executed statement
                    $result = $stmt->get_result();                    

                    // Check for errors in fetching pupil count
                    if (!$result) {
                        $errorMessage = "Error fetching pupil count: " . $stmt->error;
                        break;
                    }

                    // Fetch the result and check if the class has reached its capacity
                    $row = $result->fetch_assoc();
                    $Pupil_Count = $row["Pupil_Count"];

                    if ($Pupil_Count < $Class_Capacity) {
                        // Proceed with the pupil insertion if the class has not reached its capacity
                        $AddNewPupilSQL = "INSERT INTO pupil (Pupil_Name, Pupil_Address, Pupil_PhoneNumber, Pupil_DateOfBirth, Pupil_MedicalInformation, Pupil_Grade, ClassID)" .
                        // SQL query to insert new pupil data into the 'pupil' table
                        "VALUES (?, ?, ?, ?, ?, ?, ?)"; 
                        // Prepare the SQL statement
                        $stmt = $connection->prepare($AddNewPupilSQL); 
                        // Bind parameters to the prepared statement
                        $stmt->bind_param("ssssssi", $Pupil_Name, $Pupil_Address, $Pupil_PhoneNumber, $Pupil_DateOfBirth, $Pupil_MedicalInformation, $Pupil_Grade, $ClassID); 
                        // Execute the prepared statement to insert the new pupil record
                        $stmt->execute(); 

                        // Check for query errors
                        if ($stmt->error) {
                            $errorMessage = "Query Error: " . $stmt->error;
                            break;
                        }

                        // Reset form fields
                        $Pupil_Name = "";
                        $Pupil_Address = "";
                        $Pupil_PhoneNumber = "";
                        $Pupil_DateOfBirth = "";
                        $Pupil_MedicalInformation = "";
                        $Pupil_Grade = "";
                        $ClassID = "";

                        // Set success message
                        $successMessage = "Pupil has been added";
                    } else {
                        // Set error message if the class has reached its capacity
                        $errorMessage = "The class has reached its capacity. Cannot add more pupils.";
                    }
                } while (false);
            }
        }
    }
?>

<!-- HTML section -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Pupil</title>
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
                <h2 class="title white-text">Add New Pupil</h2>
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

            <!-- Add new pupil form -->
            <form class="Class-form row " method="POST" action="AddNewPupil.php" id="ClassForm">
                <!-- Hidden field for PupilID -->
                <input type="hidden" name="PupilID" value="<?php echo $PupilID; ?>">

                <div class="row-mb-3">
                    <!-- Label for the Pupil Name input field -->
                    <label class="col-sm-3" for="Pupil_Name">Pupil Name:</label>
                    <!-- Text input for the name with placeholder and name attribute -->
                    <div class="col-sm-6">
                        <input class="input" type="text" name="Pupil_Name" id="Pupil_Name" value="<?php echo $Pupil_Name; ?>">
                    </div>
                </div>

                <div class="row-mb-3">
                    <!-- Label for the Pupil Address input field -->
                    <label class="col-sm-3" for="Pupil_Address">Pupil Address:</label>
                    <!-- Text input for the Pupil Address with placeholder and name attribute -->
                    <div class="col-sm-6">
                        <input class="input" type="text" name="Pupil_Address" id="Pupil_Address" value="<?php echo $Pupil_Address; ?>">
                    </div>
                </div>

                <div class="row-mb-3">
                    <!-- Label for the Pupil Phone Number input field -->
                    <label class="col-sm-3" for="Pupil_PhoneNumber">Pupil Phone Number:</label>
                    <!-- Tel input for Pupil Phone Number with placeholder and name attribute -->
                    <div class="col-sm-6">
                        <input class="input" type="tel" name="Pupil_PhoneNumber" id="Pupil_PhoneNumber" value="<?php echo $Pupil_PhoneNumber; ?>">
                    </div>
                </div>

                <div class="row-mb-3">
                    <!-- Label for the Pupil Date Of Birth input field -->
                    <label class="col-sm-3" for="Pupil_DateOfBirth">Pupil Date Of Birth:</label>
                    <!-- Date Of Birth input for Pupil -->
                    <div class="col-sm-6">
                        <input class="input" type="date" name="Pupil_DateOfBirth" id="Pupil_DateOfBirth" value="<?php echo $Pupil_DateOfBirth; ?>">
                    </div>
                </div>

                <div class="row-mb-3">
                    <!-- Label for the Pupil Medical Information input field -->
                    <label class="col-sm-3" for="Pupil_MedicalInformation">Pupil Medical Information:</label>
                    <!-- Text input for Pupil Medical Information with placeholder and name attribute -->
                    <div class="col-sm-6">
                        <input class="input" type="text" name="Pupil_MedicalInformation" id="Pupil_MedicalInformation" value="<?php echo $Pupil_MedicalInformation; ?>">
                    </div>
                </div>

                <div class="row-mb-3">
                    <!-- Label for the Pupil Grade input field -->
                    <label class="col-sm-3" for="Pupil_Grade">Pupil Grade:</label>
                    <!-- Text input for Pupil Grade with placeholder and name attribute -->
                    <div class="col-sm-6">
                        <input class="input" type="text" name="Pupil_Grade" id="Pupil_Grade" value="<?php echo $Pupil_Grade; ?>">
                    </div>
                </div>

                <div class="mb-3">
                    <!-- Label and dropdown for Class ID selection -->
                    <label class="col-sm-3" for="ClassID">Class ID:</label>
                    <div class="col-sm-6">
                        <select class="form-select" name="ClassID" id="ClassID">
                            <?php
                            // Display options for class dropdown
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
                            <div class='offset-sm-3' col-sm-6>
                                <div class='alert alert-success alert-dismissible fade show' role='alert'>
                                    <strong>$successMessage</strong>
                                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                                </div>
                            </div>
                        </div>
                        ";
                    }
                ?>

                <!-- Submittion and cancellation button -->
                <div class="row mb-3">
                    <div class="offset-sm-3 col-sm-3 d-grid">
                        <!-- Button for submitting the Class form -->
                        <button class="main-button underline-on-hover" type="submit">Submit</button>
                    </div>
                    <div class="col-sm-3 d-grid">
                        <!-- Button to cancel and return to Pupil.php -->
                        <a class="btn btn-outline-primary" href="Pupil.php" role="button">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- /Add new pupil section -->

    <!-- JavaScript libraries -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>