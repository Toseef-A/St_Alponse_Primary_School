<?php
    include("connection.php");
    include("functions.php");

    session_start();

    // Moved the check_permission_and_message function call after including the required files
    check_permission_and_message($connection, __FILE__);

    // Retrieve user data
    $user_data = check_login($connection, get_allowed_permissions(__FILE__));

    $PupilID = "";
    $Pupil_Name = "";
    $Pupil_Address = "";
    $Pupil_PhoneNumber = "";
    $Pupil_DateOfBirth = "";
    $Pupil_MedicalInformation = "";
    $Pupil_Grade = "";
    $ClassID = "";
    $errorMessage = "";
    $successMessage = "";

    $query = "SELECT ClassID, Class_Name FROM class";
    $ClassResult = $connection->query($query);

    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        if (!isset($_GET["PupilID"])) {
            header("location: /test1/Pupil.php");
            exit;
        }

        $PupilID = $_GET["PupilID"];

        $EditPupilSQL = "SELECT * FROM pupil WHERE PupilID=?";
        $stmt = $connection->prepare($EditPupilSQL);
        $stmt->bind_param("i", $PupilID);
        $stmt->execute();
        $result = $stmt->get_result();

        if (!$result) {
            echo "Error executing query: " . $stmt->error;
            exit;
        }

        if ($result->num_rows == 0) {
            echo "No records found for PupilID: " . $PupilID;
            exit;
        }

        $row = $result->fetch_assoc();
        $PupilID = $row["PupilID"];
        $Pupil_Name = $row["Pupil_Name"];
        $Pupil_Address = $row["Pupil_Address"];
        $Pupil_PhoneNumber = $row["Pupil_PhoneNumber"];
        $Pupil_DateOfBirth = $row["Pupil_DateOfBirth"];
        $Pupil_MedicalInformation = $row["Pupil_MedicalInformation"];
        $Pupil_Grade = $row["Pupil_Grade"];
        $ClassID = $row["ClassID"];

    } else {
        $PupilID = $_POST["PupilID"];
        $Pupil_Name = $_POST["Pupil_Name"];
        $Pupil_Address = $_POST["Pupil_Address"];
        $Pupil_PhoneNumber = $_POST["Pupil_PhoneNumber"];
        $Pupil_DateOfBirth = $_POST["Pupil_DateOfBirth"];
        $Pupil_MedicalInformation = $_POST["Pupil_MedicalInformation"];
        $Pupil_Grade = $_POST["Pupil_Grade"];
        $ClassID = $_POST["ClassID"];

        // Check for empty values
        if (empty($PupilID) || empty($Pupil_Name) || empty($Pupil_Address) || empty($Pupil_PhoneNumber) || empty($Pupil_DateOfBirth) || empty($Pupil_MedicalInformation) || empty($Pupil_Grade)) {
            $errorMessage = "All fields are required";
        } else {
            // Validate numeric values
            if (!is_numeric($PupilID)) {
                $errorMessage = "Invalid PupilID";
            }

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
                // Check if the pupil already exists
                $query = "SELECT COUNT(*) as Pupil_Exists FROM pupil WHERE PupilID = ?";
                $stmt = $connection->prepare($query);
                $stmt->bind_param("i", $PupilID);
                $stmt->execute();
                $result = $stmt->get_result();
            
                if (!$result) {
                    $errorMessage = "Error checking if pupil exists: " . $stmt->error;
                } else {
                    $row = $result->fetch_assoc();
                    $Pupil_Exists = $row["Pupil_Exists"];
            
                    if ($Pupil_Exists > 0) {
                        // Pupil already exists, update the entry
                        $EditPupilSQL = "UPDATE pupil SET Pupil_Name=?, Pupil_Address=?, Pupil_PhoneNumber=?, Pupil_DateOfBirth=?, Pupil_MedicalInformation=?, Pupil_Grade=?, ClassID=? WHERE PupilID=?";
                        $stmt = $connection->prepare($EditPupilSQL);
                        $stmt->bind_param("ssssssii", $Pupil_Name, $Pupil_Address, $Pupil_PhoneNumber, $Pupil_DateOfBirth, $Pupil_MedicalInformation, $Pupil_Grade, $ClassID, $PupilID);
                        $stmt->execute();
            
                        if (!$stmt->error) {
                            $successMessage = "Pupil has been updated";
                        } else {
                            $errorMessage = "Query Error: " . $stmt->error;
                        }
                    } else {
                        // Pupil does not exist, insert a new entry
                        $EditPupilSQL = "INSERT INTO pupil (Pupil_Name, Pupil_Address, Pupil_PhoneNumber, Pupil_DateOfBirth, Pupil_MedicalInformation, Pupil_Grade, ClassID)" .
                            "VALUES (?, ?, ?, ?, ?, ?, ?)";
                        $stmt = $connection->prepare($EditPupilSQL);
                        $stmt->bind_param("ssssssi", $Pupil_Name, $Pupil_Address, $Pupil_PhoneNumber, $Pupil_DateOfBirth, $Pupil_MedicalInformation, $Pupil_Grade, $ClassID);
                        $stmt->execute();
            
                        if (!$stmt->error) {
                            $PupilID = $stmt->insert_id;  // Get the ID of the newly inserted pupil
                            $successMessage = "Pupil has been added";
                        } else {
                            $errorMessage = "Query Error: " . $stmt->error;
                        }
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
    <title>Edit Pupil</title>
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
                    <form class="Class-form row " method="POST" action="EditPupil.php" id="ClassForm">
                        <input type="hidden" name="PupilID" value="<?php echo $PupilID; ?>">

                            <div class="row-mb-3">
                                <!-- Label for the First name input field -->
                                <label class="col-sm-3" for="Pupil_Name">Pupil Name:</label>
                                <!-- Text input for the name with placeholder and name attribute -->
                                <div class="col-sm-6">
                                    <input class="input" type="text" name="Pupil_Name" id="Pupil_Name" value="<?php echo $Pupil_Name; ?>">
                                </div>
                            </div>

                            <div class="row-mb-3">
                                <!-- Label for the StudentiD input field -->
                                <label class="col-sm-3" for="Pupil_Address">Pupil Address:</label>
                                <!-- Text input for the name with placeholder and name attribute -->
                                <div class="col-sm-6">
                                    <input class="input" type="text" name="Pupil_Address" id="Pupil_Address" value="<?php echo $Pupil_Address; ?>">
                                </div>
                            </div>

                            <div class="row-mb-3">
                                <!-- Label for the phone input field -->
                                <label class="col-sm-3" for="Pupil_PhoneNumber">Pupil Phone Number:</label>
                                <!-- Tel input for phone number with placeholder and name attribute -->
                                <div class="col-sm-6">
                                    <input class="input" type="tel" name="Pupil_PhoneNumber" id="Pupil_PhoneNumber" value="<?php echo $Pupil_PhoneNumber; ?>">
                                </div>
                            </div>

                            <div class="row-mb-3">
                                <!-- Label for the Date Of Birth input field -->
                                <label class="col-sm-3" for="Pupil_DateOfBirth">Pupil Date Of Birth:</label>
                                <!-- Date Of Birth input -->
                                <div class="col-sm-6">
                                    <input class="input" type="date" name="Pupil_DateOfBirth" id="Pupil_DateOfBirth" value="<?php echo $Pupil_DateOfBirth; ?>">
                                </div>
                            </div>

                            <div class="row-mb-3">
                                <!-- Label for the email input field -->
                                <label class="col-sm-3" for="Pupil_MedicalInformation">Pupil Medical Information:</label>
                                <!-- Email input for email address with placeholder and name attribute -->
                                <div class="col-sm-6">
                                    <input class="input" type="text" name="Pupil_MedicalInformation" id="Pupil_MedicalInformation" value="<?php echo $Pupil_MedicalInformation; ?>">
                                </div>
                            </div>

                            <div class="row-mb-3">
                                <!-- Label for the Date Of Birth input field -->
                                <label class="col-sm-3" for="Pupil_Grade">Pupil Grade:</label>
                                <!-- Date Of Birth input -->
                                <div class="col-sm-6">
                                    <input class="input" type="text" name="Pupil_Grade" id="Pupil_Grade" value="<?php echo $Pupil_Grade; ?>">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="col-sm-3" for="ClassID">ClassID:</label>
                                <div class="col-sm-6">
                                    <select class="form-select" name="ClassID" id="ClassID">
                                        <?php
                                        while ($row = $ClassResult->fetch_assoc()) {
                                            $selected = ($row['ClassID'] == $ClassID) ? 'selected' : '';
                                            echo "<option value='" . $row['ClassID'] . "' $selected>" . $row['ClassID'] . " - " . $row['Class_Name'] . "</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>


                        <?php
                            if ( !empty($successMessage) ) {
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

                        <!-- Full-width column for the submission button -->
                        <div class="row mb-3">
                            <div class="offset-sm-3 col-sm-3 d-grid">
                                <!-- Button for submitting the Class form -->
                                <button class="main-button underline-on-hover" type="submit">Submit</button>
                            </div>
                            <div class="col-sm-3 d-grid">
                                <a class="btn btn-outline-primary" href="Pupil.php" role="button">Cancel</a>
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