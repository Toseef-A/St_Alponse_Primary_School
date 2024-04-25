<?php
    include("connection.php");
    include("functions.php");
    
    session_start();
    
    // Moved the check_permission_and_message function call after including the required files
    check_permission_and_message($connection, __FILE__);
    
    // Retrieve user data
    $user_data = check_login($connection, get_allowed_permissions(__FILE__));

    $ClassSubjectID = "";
    $SubjectID = "";
    $ClassID = "";
    $errorMessage = "";
    $successMessage = "";

    // Fetch subjects and classes for dropdowns
    $query = "SELECT SubjectID, Subject_Name FROM subject";
    $SubjectResult = $connection->query($query);

    $query = "SELECT ClassID, Class_Name FROM class";
    $ClassResult = $connection->query($query);

    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        if (!isset($_GET["ClassSubjectID"])) {
            header("location: /test1/ClassSubject.php");
            exit;
        }

        $ClassSubjectID = $_GET["ClassSubjectID"];

        $EditClassSubjectSQL = "SELECT * FROM classsubject WHERE ClassSubjectID=?";
        $stmt = $connection->prepare($EditClassSubjectSQL);
        $stmt->bind_param("i", $ClassSubjectID);
        $stmt->execute();
        $result = $stmt->get_result();

        if (!$result) {
            echo "Error executing query: " . $stmt->error;
            exit;
        }

        if ($result->num_rows == 0) {
            echo "No records found for ClassSubjectID: " . $ClassSubjectID;
            exit;
        }

        $row = $result->fetch_assoc();
        $ClassSubjectID = $row["ClassSubjectID"];
        $SubjectID = $row["SubjectID"];
        $ClassID = $row["ClassID"];
        
    } else {
        $ClassSubjectID = $_POST["ClassSubjectID"];
        $SubjectID = $_POST["SubjectID"];
        $ClassID = $_POST["ClassID"];

        // Validation
        if (empty($ClassSubjectID) || empty($SubjectID) || empty($ClassID)) {
            $errorMessage = "All fields are required";
        } else {
            // Check for numeric values
            if (!is_numeric($ClassSubjectID) || !is_numeric($SubjectID) || !is_numeric($ClassID)) {
                $errorMessage = "Invalid values for ClassSubjectID, SubjectID, or ClassID";
            } else {
                // Check if SubjectID exists
                $subjectCheckSQL = "SELECT SubjectID FROM subject WHERE SubjectID=?";
                $subjectStmt = $connection->prepare($subjectCheckSQL);
                $subjectStmt->bind_param("i", $SubjectID);
                $subjectStmt->execute();
                $subjectResult = $subjectStmt->get_result();

                // Check if ClassID exists
                $classCheckSQL = "SELECT ClassID FROM class WHERE ClassID=?";
                $classStmt = $connection->prepare($classCheckSQL);
                $classStmt->bind_param("i", $ClassID);
                $classStmt->execute();
                $classResult = $classStmt->get_result();

                if ($subjectResult->num_rows == 0) {
                    $errorMessage = "Invalid SubjectID";
                } elseif ($classResult->num_rows == 0) {
                    $errorMessage = "Invalid ClassID";
                } else {
                    // Update Class Subject
                    $ClassSubjectSQL = "UPDATE classsubject SET SubjectID=?, ClassID=? WHERE ClassSubjectID=?";
                    $stmt = $connection->prepare($ClassSubjectSQL);
                    $stmt->bind_param("sii", $SubjectID, $ClassID, $ClassSubjectID);
                    $stmt->execute();

                    if ($stmt->error) {
                        $errorMessage = "Error updating Class Subject: " . $stmt->error;
                    } else {
                        $successMessage = "Class Subject updated correctly";
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
    <title>Edit Class Subject</title>
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
                    <form class="Class-form row " method="POST" action="EditClassSubject.php" id="ClassForm">
                        <input type="hidden" name="ClassSubjectID" value="<?php echo $ClassSubjectID; ?>">

                        <div class="mb-3">
                            <label class="col-form-label" for="SubjectID">SubjectID:</label>
                            <div class="col-sm-6">
                                <select class="form-select" name="SubjectID" id="SubjectID">
                                    <?php
                                    while ($row = $SubjectResult->fetch_assoc()) {
                                        $selected = ($row['SubjectID'] == $SubjectID) ? 'selected' : '';
                                        echo "<option value='" . $row['SubjectID'] . "' $selected>" . $row['SubjectID'] . " - " . $row['Subject_Name'] . "</option>";
                                    }
                                    ?>
                                </select>
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
                                <a class="btn btn-outline-primary" href="ClassSubject.php" role="button">Cancel</a>
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