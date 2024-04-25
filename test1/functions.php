<?php
// Include the database connection file
include("connection.php");

// Function to determine allowed permissions based on the current file
function get_allowed_permissions($file) {
    $allowed_permissions = [
        // Associative array mapping filenames to allowed permissions
        // This array specifies which permissions are allowed for each PHP file
        'index.php' => ['pupil', 'parent', 'teacher', 'administration'],
        'Attendance.php' => ['pupil', 'parent', 'teacher', 'administration'],
        'Class.php' => ['pupil', 'parent', 'teacher', 'administration'],
        'ClassSubject.php' => ['teacher', 'administration'],
        'Parents.php' => ['pupil', 'parent', 'teacher', 'administration'],
        'Pupil.php' => ['pupil', 'parent', 'teacher', 'administration'],
        'Subject.php' => ['pupil', 'parent', 'teacher', 'administration'],
        'Teacher.php' => ['teacher', 'administration'],
        'UserSessions.php' => ['administration'],
        'AddNewAttendance.php' => ['teacher', 'administration'],
        'AddNewClass.php' => ['administration'],
        'AddNewClassSubject.php' => ['administration'],
        'AddNewParent.php' => ['administration'],
        'AddNewPupil.php' => ['administration'],
        'AddNewSubject.php' => ['administration'],
        'AddNewTeacher.php' => ['administration'],
        'EditAttendance.php' => ['teacher', 'administration'],
        'EditClass.php' => ['administration'],
        'EditUser.php' => ['administration'],
        'EditClassSubject.php' => ['administration'],
        'EditParent.php' => ['administration'],
        'EditPupil.php' => ['administration'],
        'EditSubject.php' => ['administration'],
        'EditTeacher.php' => ['administration'],
        'DeleteAttendance.php' => ['teacher', 'administration'],
        'DeleteClass.php' => ['administration'],
        'DeleteClassSubject.php' => ['administration'],
        'DeleteParent.php' => ['administration'],
        'DeletePupil.php' => ['administration'],
        'DeleteSubject.php' => ['administration'],
        'DeleteTeacher.php' => ['administration'],
        'DeleteUser.php' => ['administration'],
    ];

    // Get the filename from the provided file path
    $filename = ($file !== NULL) ? basename($file) : '';

    // Check if the key (filename) exists in the array, if not, return an empty array
    return isset($allowed_permissions[$filename]) ? $allowed_permissions[$filename] : [];
}

// Function to check permissions and display a message if access is denied
function check_permission_and_message($connection, $file)
{
    // Get the allowed permissions for the current file
    $allowed_permissions = get_allowed_permissions($file);

    // Check if the allowed permissions array is empty or the user's permission level is not in the allowed list
    if (empty($allowed_permissions) || !in_array($_SESSION['PermissionLevel'], $allowed_permissions)) {
        // Display an error message or redirect to an error page
        echo "You don't have access to this page. Please contact the administrator.";
        exit();
    }
}

// Function to check if a user is logged in and has the required permissions
function check_login($connection, $allowed_permissions) {
    if (isset($_SESSION['UserID'])) {
        $UserID = (int)$_SESSION['UserID'];

        // Query to fetch user data from the 'usersessions' table
        $query = "SELECT * FROM usersessions WHERE UserID = ? LIMIT 1";
        $stmt = $connection->prepare($query);
        $stmt->bind_param("i", $UserID);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            $User_data = $result->fetch_assoc();

            // Check if the user's permission level is in the allowed list
            if (in_array($User_data['PermissionLevel'], $allowed_permissions)) {
                return $User_data;
            }
        }
    }

    // Redirect to the login page if not logged in or permissions are insufficient
    header("Location: login.php");
    die;
}

// Function to generate a random number of a specified length
function random_num($length) {
    // Ensure the minimum length is 8 characters
    $length = max($length, 8);
    $text = '';
    $usedDigits = [];

    // Generate a random number by appending unique digits
    while (strlen($text) < $length) {
        $digit = mt_rand(0, 9);
        if (!in_array($digit, $usedDigits)) {
            $usedDigits[] = $digit;
            $text .= $digit;
        }
    }

    return $text;
}
?>
