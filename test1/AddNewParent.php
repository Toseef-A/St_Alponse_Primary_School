<?php
    // Include necessary files for database connection and functions
    include("connection.php");
    include("functions.php");

    // Start the session
    session_start();

    // Check user permissions and display messages
    check_permission_and_message($connection, __FILE__);

    // Retrieve user data
    $user_data = check_login($connection, get_allowed_permissions(__FILE__));

    // Initialize variables for form fields and messages
    $Parent_Name = ""; // Parent's name
    $Parent_Address = ""; // Parent's address
    $Parent_Email = ""; // Parent's email
    $Parent_PhoneNumber = ""; // Parent's phone number
    $Parent_DateOfBirth = ""; // Parent's date of birth
    $Parent_Gender = ""; // Parent's gender
    $PupilID = ""; // Pupil's ID
    $errorMessage = ""; // Error message
    $successMessage = ""; // Success message

    // Fetch pupil data for the dropdown
    $query = "SELECT PupilID, Pupil_Name FROM pupil";
    $PupilResult = $connection->query($query);

    // Check if the form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Retrieve form data
        $Parent_Name = $_POST["Parent_Name"];
        $Parent_Address = $_POST["Parent_Address"];
        $Parent_Email = $_POST["Parent_Email"];
        $Parent_PhoneNumber = $_POST["Parent_PhoneNumber"];
        $Parent_DateOfBirth = $_POST["Parent_DateOfBirth"];
        $Parent_Gender = $_POST["Parent_Gender"];
        $PupilID = $_POST["PupilID"];

        // Input validation
        // Check if anyhting is empty
        if (empty($Parent_Name) || empty($Parent_Address) || empty($Parent_Email) || empty($Parent_PhoneNumber) || empty($Parent_DateOfBirth) || empty($Parent_Gender) || empty($PupilID) ) {
            $errorMessage = "All fields are required";
        } else {
            // Validate name length
            if (strlen($Parent_Name) > 255) {
                $errorMessage = "Name must be at most 255 characters long.";
            } elseif (!filter_var($Parent_Email, FILTER_VALIDATE_EMAIL) || !preg_match("/@gmail\.com$/", $Parent_Email)) {
                // Validate email format and ensure it ends with @gmail.com
                $errorMessage = "Invalid email format. Must end with @gmail.com";
            } elseif (!preg_match("/^\d{10}$/", $Parent_PhoneNumber)) {
                // Validate phone number to have 10 digits from 0-9
                $errorMessage = "Invalid phone number. Must have 10 digits.";
            } elseif (strlen($Parent_Address) > 255) {
                // Validate address length
                $errorMessage = "Address must be at most 255 characters long.";
            } else {
                // Get the current number of parents for the selected pupil
                // SQL query to count the number of parents for a specific pupil
                $query = "SELECT COUNT(*) as Parent_Count FROM parents WHERE PupilID = ?";
                // Prepare the SQL statement for counting the number of parents
                $stmt = $connection->prepare($query);
                // Bind the parameter to the prepared statement for counting the number of parents
                $stmt->bind_param("i", $PupilID);
                // Execute the prepared statement to get the result of the parent count
                $stmt->execute();
                // Get the result set from the executed statement
                $result = $stmt->get_result();

                if (!$result) {
                    $errorMessage = "Error fetching parent count: " . $stmt->error;
                } else {
                    $row = $result->fetch_assoc();
                    $Parent_Count = $row["Parent_Count"];

                    // Check if the pupil already has two parents
                    if ($Parent_Count < 2) {
                        // Proceed with the insertion using prepared statements
                        // SQL query to insert new parent data into the 'parents' table
                        $AddNewParentSQL = "INSERT INTO parents (Parent_Name, Parent_Address, Parent_Email, Parent_PhoneNumber, Parent_DateOfBirth, Parent_Gender, PupilID)" .
                        " VALUES (?, ?, ?, ?, ?, ?, ?)";
                        // Prepare the SQL statement for adding a new parent record to the database
                        $stmt = $connection->prepare($AddNewParentSQL);
                        // Bind the parameters to the prepared statement for adding a new parent record
                        $stmt->bind_param("ssssssi", $Parent_Name, $Parent_Address, $Parent_Email, $Parent_PhoneNumber, $Parent_DateOfBirth, $Parent_Gender, $PupilID);
                        // Execute the prepared statement to add a new parent record to the database
                        $stmt->execute();
                        if ($stmt->error) {
                            $errorMessage = "Query Error: " . $stmt->error;
                        } else {
                            // Reset form fields on successful submission
                            $Parent_Name = "";
                            $Parent_Address = "";
                            $Parent_Email = "";
                            $Parent_PhoneNumber = "";
                            $Parent_DateOfBirth = "";
                            $Parent_Gender = "";
                            $PupilID = "";
                        
                            $successMessage = "Parent has been added";
                        }
                    } else {
                        $errorMessage = "A pupil cannot have more than two parents.";
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
    <title>Add New Parent</title>
    <!-- Link to Bootstrap 5.3.1 CSS library -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <!-- Add New Parent section -->
    <div id="Class" class="section">
        <!-- Container-->
        <div class="container">
            <!-- Section header-->
            <div class="section-header text-center">
                <!-- Title-->
                <h2 class="title white-text">Add New Parent</h2>
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
            <!-- Add New Parent -->
            <form class="Class-form row " method="POST" action="AddNewParent.php" id="ClassForm">
                <input type="hidden" name="ParentID" value="<?php echo $ParentID; ?>">

                <div class="mb-6">
                    <!-- Label for the Parent Name input field -->
                    <label class="col-sm-3" for="Parent_Name">Parent Name:</label>
                    <!-- Text input for the name with placeholder and name attribute -->
                    <div class="col-sm-6">
                        <input class="input" type="text" name="Parent_Name" id="Parent_Name" value="<?php echo $Parent_Name; ?>">
                    </div>
                </div>

                <div class="mb-6">
                    <!-- Label for the Parent Address input field -->
                    <label class="col-sm-3" for="Parent_Address">Parent Address:</label>
                    <!-- Text input for the address with placeholder and name attribute -->
                    <div class="col-sm-6">
                        <input class="input" type="text" name="Parent_Address" id="Parent_Address" value="<?php echo $Parent_Address; ?>">
                    </div>
                </div>

                <div class="mb-6">
                    <!-- Label for the Parent Email input field -->
                    <label class="col-sm-3" for="Parent_Email">Parent Email:</label>
                    <!-- Email input for email address with placeholder and name attribute -->
                    <div class="col-sm-6">
                        <input class="input" type="email" name="Parent_Email" id="Parent_Email" value="<?php echo $Parent_Email; ?>">
                    </div>
                </div>

                <div class="mb-6">
                    <!-- Label for the Parent Phone Number input field -->
                    <label class="col-sm-3" for="Parent_PhoneNumber">Parent Phone Number:</label>
                    <!-- Tel input for phone number with placeholder and name attribute -->
                    <div class="col-sm-6">
                        <input class="input" type="tel" name="Parent_PhoneNumber" id="Parent_PhoneNumber" value="<?php echo $Parent_PhoneNumber; ?>">
                    </div>
                </div>

                <div class="mb-6">
                    <!-- Label for the Parent Date Of Birth input field -->
                    <label class="col-sm-3" for="Parent_DateOfBirth">Parent Date Of Birth:</label>
                    <!-- Date Of Birth input -->
                    <div class="col-sm-6">
                        <input class="input" type="date" name="Parent_DateOfBirth" id="Parent_DateOfBirth" value="<?php echo $Parent_DateOfBirth; ?>">
                    </div>
                </div>

                <div class="mb-6">
                    <!-- Label for the Parent Gender dropdown -->
                    <label class="col-sm-3" for="Parent_Gender">Parent Gender:</label>
                    <!-- Dropdown menu for Parent Gender with three options -->
                    <div class="col-sm-6">
                        <select class="form-select" name="Parent_Gender" id="Parent_Gender">
                            <option value="Male" <?php echo ($Parent_Gender === 'Male') ? 'selected' : ''; ?>>Male</option>
                            <option value="Female" <?php echo ($Parent_Gender === 'Female') ? 'selected' : ''; ?>>Female</option>
                            <option value="Dont want to specify" <?php echo ($Parent_Gender === 'Other') ? 'selected' : ''; ?>>Other</option>
                        </select>
                    </div>
                </div>

                <div class="mb-6">
                    <!-- Label for the Pupil ID dropdown -->
                    <label class="col-sm-3" for="PupilID">Pupil ID:</label>
                    <!-- Dropdown menu for Pupil ID -->
                    <div class="col-sm-6">
                        <select class="form-select" name="PupilID" id="PupilID">
                            <?php
                            // Populate the dropdown with Pupil IDs and names
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
                        <div class='mb-3'>
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

                <!-- ubmission and cancellation button -->
                <div class="row mb-3">
                    <!-- Submission button -->
                    <div class="offset-sm-3 col-sm-3 d-grid">
                        <button class="main-button underline-on-hover" type="submit">Submit</button>
                    </div>
                    <!-- Cancel button -->
                    <div class="col-sm-3 d-grid">
                        <a class="btn btn-outline-primary" href="Parents.php" role="button">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- /Add New Parent section -->

    <!-- JavaScript libraries -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>