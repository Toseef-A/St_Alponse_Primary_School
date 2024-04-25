<?php
    include("connection.php");
    include("functions.php");
    
    session_start();
    
    // Moved the check_permission_and_message function call after including the required files
    check_permission_and_message($connection, __FILE__);
    
    // Retrieve user data
    $user_data = check_login($connection, get_allowed_permissions(__FILE__));

    if ( isset($_GET["SubjectID"]) ) {
        $SubjectID = $_GET["SubjectID"];

        $DeleteSubjectSQL = "DELETE FROM subject WHERE SubjectID = $SubjectID";
        $connection -> query($DeleteSubjectSQL);
    }

    header("location: /test1/Subject.php");
    exit;

 ?>