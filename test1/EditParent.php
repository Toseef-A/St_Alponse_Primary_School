<?php
    // Include necessary PHP files
    include("connection.php");
    include("functions.php");
    
    // Start a new PHP session
    session_start();
    
    // Check user permissions and display any messages
    check_permission_and_message($connection, __FILE__);
    
    // Retrieve user data to check login status
    $user_data = check_login($connection, get_allowed_permissions(__FILE__));

    // Initialize variables for form fields and messages
    $ParentID = "";
    $Parent_Name = "";
    $Parent_Address = "";
    $Parent_Email = "";
    $Parent_PhoneNumber = "";
    $Parent_DateOfBirth = "";
    $Parent_Gender = "";
    $PupilID = "";
    $errorMessage = "";
    $successMessage = "";

    // Query to fetch pupil data (commented out)
    $query = "SELECT PupilID, Pupil_Name FROM pupil";
    $PupilResult = $connection->query($query);

    // Check if the request method is GET
    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        // Check if ParentID is set in the GET request
        if (!isset($_GET["ParentID"])) {
            // Redirect to the Parents.php page if ParentID is not set
            header("location: /test1/Parents.php");
            exit;
        }

        // Get ParentID from GET request
        $ParentID = $_GET["ParentID"];

        // Query to fetch parent data by ParentID using prepared statements
        $sql = "SELECT * FROM parents WHERE ParentID=?";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("i", $ParentID);
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if the query execution was unsuccessful
        if (!$result) {
            $errorMessage = "Error executing query: " . $stmt->error;
        } 
        // Check if no records were found for the provided ParentID
        elseif ($result->num_rows == 0) {
            $errorMessage = "No records found for ParentID: " . $ParentID;
        } 
        // Records found, fetch data
        else {
            $row = $result->fetch_assoc();
            $ParentID = $row["ParentID"];
            $Parent_Name = $row["Parent_Name"];
            $Parent_Address = $row["Parent_Address"];
            $Parent_Email = $row["Parent_Email"];
            $Parent_PhoneNumber = $row["Parent_PhoneNumber"];
            $Parent_DateOfBirth = $row["Parent_DateOfBirth"];
            $Parent_Gender = $row["Parent_Gender"];
            $PupilID = $row["PupilID"];
        }
    } 
    // Request method is POST (form submitted)
    else {
        // Get data from POST request
        $ParentID = $_POST["ParentID"];
        $Parent_Name = $_POST["Parent_Name"];
        $Parent_Address = $_POST["Parent_Address"];
        $Parent_Email = $_POST["Parent_Email"];
        $Parent_PhoneNumber = $_POST["Parent_PhoneNumber"];
        $Parent_DateOfBirth = $_POST["Parent_DateOfBirth"];
        $Parent_Gender = $_POST["Parent_Gender"];
        $PupilID = $_POST["PupilID"];

        // Validation checks
        if (empty($ParentID) || empty($Parent_Name) || empty($Parent_Address) || empty($Parent_Email) || empty($Parent_PhoneNumber) || empty($Parent_DateOfBirth) || empty($Parent_Gender) ) {
            $errorMessage = "All fields are required";
        } else {
            // Validate name length
            if (strlen($Parent_Name) > 255) {
                $errorMessage = "Name must be at most 255 characters long.";
            } 
            // Validate email format and ensure it ends with @gmail.com
            elseif (!filter_var($Parent_Email, FILTER_VALIDATE_EMAIL) || !preg_match("/@gmail\.com$/", $Parent_Email)) {
                $errorMessage = "Invalid email format. Must end with @gmail.com";
            } 
            // Validate phone number to have 10 digits from 0-9
            elseif (!preg_match("/^\d{10}$/", $Parent_PhoneNumber)) {
                $errorMessage = "Invalid phone number. Must have 10 digits.";
            } 
            // Validate address length
            elseif (strlen($Parent_Address) > 255) {
                $errorMessage = "Address must be at most 255 characters long.";
            } else {
                // Get the current number of parents for the selected pupil
                $query = "SELECT COUNT(*) as Parent_Count FROM parents WHERE PupilID = ?";
                $stmt = $connection->prepare($query);
                $stmt->bind_param("i", $PupilID);
                $stmt->execute();
                $result = $stmt->get_result();

                // Check if there was an error while fetching parent count
                if (!$result) {
                    $errorMessage = "Error fetching parent count: " . $stmt->error;
                } else {
                    $row = $result->fetch_assoc();
                    $Parent_Count = $row["Parent_Count"];

                    // Check if the pupil already has two parents
                    if ($Parent_Count < 3) {
                        // Proceed with the update using prepared statements
                        $EditParentSQL = "UPDATE parents SET Parent_Name=?, Parent_Address=?, Parent_Email=?, Parent_PhoneNumber=?, Parent_DateOfBirth=?, Parent_Gender=? WHERE PupilID=?";
                        $stmt = $connection->prepare($EditParentSQL);
                        $stmt->bind_param("ssssssi", $Parent_Name, $Parent_Address, $Parent_Email, $Parent_PhoneNumber, $Parent_DateOfBirth, $Parent_Gender, $PupilID);
                        $stmt->execute();

                        // Check for errors during the update
                        if ($stmt->error) {
                            $errorMessage = "Invalid Query: " . $stmt->error;
                        } else {
                            // Reset form fields and display success message
                            $Parent_Name = "";
                            $Parent_Address = "";
                            $Parent_Email = "";
                            $Parent_PhoneNumber = "";
                            $Parent_DateOfBirth = "";
                            $Parent_Gender = "";
                            $PupilID = "";

                            $successMessage = "Parent has been updated";
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
    <title>Edit Parent</title>
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