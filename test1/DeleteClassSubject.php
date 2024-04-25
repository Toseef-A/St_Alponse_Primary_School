<?php
    include("connection.php");
    include("functions.php");
    
    session_start();
    
    // Moved the check_permission_and_message function call after including the required files
    check_permission_and_message($connection, __FILE__);
    
    // Retrieve user data
    $user_data = check_login($connection, get_allowed_permissions(__FILE__));

    if ( isset($_GET["ClassSubjectID"]) ) {
        $ClassSubjectID = $_GET["ClassSubjectID"];

        $DeleteClassSubjectSQL = "DELETE FROM classsubject WHERE ClassSubjectID = $ClassSubjectID";
        $connection -> query($DeleteClassSubjectSQL);
    }

    header("location: /test1/ClassSubject.php");
    exit;

 ?>