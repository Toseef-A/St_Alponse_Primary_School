<?php
    include("connection.php");
    include("functions.php");
    
    session_start();
    
    // Moved the check_permission_and_message function call after including the required files
    check_permission_and_message($connection, __FILE__);
    
    // Retrieve user data
    $user_data = check_login($connection, get_allowed_permissions(__FILE__));

    $Teacher_Name = "";
    $Teacher_Address = "";
    $Teacher_Email = "";
    $Teacher_PhoneNumber = "";
    $Teacher_DateOfBirth = "";
    $Teacher_AnnualSalary = "";
    $Teacher_BackgroundCheck = "";
    $ClassID = "";
    $errorMessage = "";
    $successMessage = "";

    $query = "SELECT ClassID, Class_Name FROM class";
    $ClassResult = $connection->query($query);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $Teacher_Name = $_POST["Teacher_Name"];
        $Teacher_Address = $_POST["Teacher_Address"];
        $Teacher_Email = $_POST["Teacher_Email"];
        $Teacher_PhoneNumber = $_POST["Teacher_PhoneNumber"];
        $Teacher_DateOfBirth = $_POST["Teacher_DateOfBirth"];
        $Teacher_AnnualSalary = $_POST["Teacher_AnnualSalary"];
        $Teacher_BackgroundCheck = $_POST["Teacher_BackgroundCheck"];
        $ClassID = $_POST["ClassID"];

        // Validation
        if (
            empty($Teacher_Name) || 
            empty($Teacher_Address) || 
            empty($Teacher_Email) || 
            empty($Teacher_PhoneNumber) || 
            empty($Teacher_DateOfBirth) || 
            empty($Teacher_AnnualSalary) || 
            empty($Teacher_BackgroundCheck)
        ) {
            $errorMessage = "All fields are required";
        } else {
            // Validate name length
            if (strlen($Teacher_Name) > 255) {
                $errorMessage = "Name must be at most 255 characters long.";
            }

            // Validate address length
            if (strlen($Teacher_Address) > 255) {
                $errorMessage = "Address must be at most 255 characters long.";
            }

            // Validate email format and ensure it ends with @gmail.com
            if (!filter_var($Teacher_Email, FILTER_VALIDATE_EMAIL) || !preg_match("/@gmail\.com$/", $Teacher_Email)) {
                $errorMessage = "Invalid email format. Must end with @gmail.com";
            }

            // Validate phone number format
            if (!preg_match("/^\d{10}$/", $Teacher_PhoneNumber)) {
                $errorMessage = "Invalid phone number. Must have 10 digits.";
            }

            // Validate annual salary to have only digits
            if (!ctype_digit($Teacher_AnnualSalary)) {
                $errorMessage = "Annual salary must contain only digits.";
            }

            // Add more validations as needed...

            if (empty($errorMessage)) {
                // Check if a teacher is already assigned to the selected class
                $query = "SELECT COUNT(*) as Teacher_Count FROM teacher WHERE ClassID = ?";
                $stmt = $connection->prepare($query);
                $stmt->bind_param("i", $ClassID);
                $stmt->execute();
                $result = $stmt->get_result();

                if (!$result) {
                    $errorMessage = "Error fetching teacher count: " . $stmt->error;
                } else {
                    $row = $result->fetch_assoc();
                    $Teacher_Count = $row["Teacher_Count"];

                    // Check if the class already has a teacher
                    if ($Teacher_Count == 0) {
                        // Proceed with the insertion
                        $AddNewTeacherSQL = "INSERT INTO teacher (Teacher_Name, Teacher_Address, Teacher_Email, Teacher_PhoneNumber, Teacher_DateOfBirth, Teacher_AnnualSalary, Teacher_BackgroundCheck, ClassID)" . 
                            "VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                        $stmt = $connection->prepare($AddNewTeacherSQL);
                        $stmt->bind_param("sssssssi", $Teacher_Name, $Teacher_Address, $Teacher_Email, $Teacher_PhoneNumber, $Teacher_DateOfBirth, $Teacher_AnnualSalary, $Teacher_BackgroundCheck, $ClassID);
                        $stmt->execute();

                        if ($stmt->error) {
                            $errorMessage = "Invalid Query: " . $stmt->error;
                        } else {
                            $Teacher_Name = "";
                            $Teacher_Address = "";
                            $Teacher_Email = "";
                            $Teacher_PhoneNumber = "";
                            $Teacher_DateOfBirth = "";
                            $Teacher_AnnualSalary = "";
                            $Teacher_BackgroundCheck = "";
                            $ClassID = "";

                            $successMessage = "Teacher has been added";
                        }
                    } else {
                        $errorMessage = "A class can only have one teacher.";
                    }
                }
            }
        }
    }

    $connection->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Teacher</title>
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
                <h2 class="title white-text">Add New Teacher</h2>
            </div>
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
                    <form class="Class-form row " method="POST" action="AddNewTeacher.php" id="ClassForm">
                        <input type="hidden" name="TeacherID" value="<?php echo $TeacherID; ?>">

                            <div class="row-mb-3">
                                <!-- Label for the First name input field -->
                                <label class="col-sm-3" for="Teacher_Name">Teacher Name:</label>
                                <!-- Text input for the name with placeholder and name attribute -->
                                <div class="col-sm-6">
                                    <input class="input" type="text" name="Teacher_Name" id="Teacher_Name" value="<?php echo $Teacher_Name; ?>">
                                </div>
                            </div>

                            <div class="row-mb-3">
                                <!-- Label for the StudentiD input field -->
                                <label class="col-sm-3" for="Teacher_Address">Teacher Address:</label>
                                <!-- Text input for the name with placeholder and name attribute -->
                                <div class="col-sm-6">
                                    <input class="input" type="text" name="Teacher_Address" id="Teacher_Address" value="<?php echo $Teacher_Address; ?>">
                                </div>
                            </div>

                            <div class="row-mb-3">
                                <!-- Label for the email input field -->
                                <label class="col-sm-3" for="Teacher_Email">Teacher Email:</label>
                                <!-- Email input for email address with placeholder and name attribute -->
                                <div class="col-sm-6">
                                    <input class="input" type="email" name="Teacher_Email" id="Teacher_Email" value="<?php echo $Teacher_Email; ?>">
                                </div>
                            </div>
                                
                            <div class="row-mb-3">
                                <!-- Label for the phone input field -->
                                <label class="col-sm-3" for="Teacher_PhoneNumber">Teacher Phone Number:</label>
                                <!-- Tel input for phone number with placeholder and name attribute -->
                                <div class="col-sm-6">
                                    <input class="input" type="tel" name="Teacher_PhoneNumber" id="Teacher_PhoneNumber" value="<?php echo $Teacher_PhoneNumber; ?>">
                                </div>
                            </div>

                            <div class="row-mb-3">
                                <!-- Label for the Date Of Birth input field -->
                                <label class="col-sm-3" for="Teacher_DateOfBirth">Teacher Date Of Birth:</label>
                                <!-- Date Of Birth input -->
                                <div class="col-sm-6">
                                    <input class="input" type="date" name="Teacher_DateOfBirth" id="Teacher_DateOfBirth" value="<?php echo $Teacher_DateOfBirth; ?>">
                                </div>
                            </div>

                            <div class="row-mb-3">
                                <!-- Label for the Date Of Birth input field -->
                                <label class="col-sm-3" for="Teacher_AnnualSalary">Teacher Annual Salary:</label>
                                <!-- Date Of Birth input -->
                                <div class="col-sm-6">
                                    <input class="input" type="text" name="Teacher_AnnualSalary" id="Teacher_AnnualSalary" value="<?php echo $Teacher_AnnualSalary; ?>">
                                </div>
                            </div>

                            <div class="row-mb-3">
                                <!-- Label for the Teacher BackgroundCheck dropdown -->
                                <label class="col-sm-3" for="Teacher_BackgroundCheck">Teacher BackgroundCheck:</label>
                                <!-- Dropdown menu for Teacher BackgroundCheck with three options -->
                                <div class="col-sm-6">
                                    <select class="form-select" name="Teacher_BackgroundCheck" id="Teacher_BackgroundCheck">
                                        <option value="Clear" <?php echo ($Teacher_BackgroundCheck === 'Clear') ? 'selected' : ''; ?>>Clear</option>
                                        <option value="Pending" <?php echo ($Teacher_BackgroundCheck === 'Pending') ? 'selected' : ''; ?>>Pending</option>
                                        <option value="Not Clear" <?php echo ($Teacher_BackgroundCheck === 'Not Clear') ? 'selected' : ''; ?>>Not Clear</option>
                                    </select>
                                </div>
                            </div>


                            <div class="mb-3">
                                <label class="col-sm-3" for="ClassID">ClassID:</label>
                                <div class="col-sm-6">
                                    <select class="form-select" name="ClassID" id="ClassID">
                                        <?php
                                        while ($row = $ClassResult->fetch_assoc()) {
                                            echo "<option value='" . $row['ClassID'] . "'>" . $row['ClassID'] . " - " . $row['Class_Name'] . "</option>";
                                        }
                                        ?>
                                    </select>
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
                                <a class="btn btn-outline-primary" href="Teacher.php" role="button">Cancel</a>
                            </div>
                        </div>
                    </form>
        </div>
    </div>
    <!-- Class section -->

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>


</body>
</html>