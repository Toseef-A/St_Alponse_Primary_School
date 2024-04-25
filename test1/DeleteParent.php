 <?php
    include("connection.php");
    include("functions.php");
    // Start session
    session_start();
    
    // Moved the check_permission_and_message function call after including the required files
    check_permission_and_message($connection, __FILE__);
    
    // Retrieve user data
    $user_data = check_login($connection, get_allowed_permissions(__FILE__));

    // Check if parent id is received
    if ( isset($_GET["ParentID"]) ) {
        // Get parent id
        $ParentID = $_GET["ParentID"];
        // Delete the data from the database
        $DeleteParentSQL = "DELETE FROM parents WHERE ParentID = $ParentID";
        $connection -> query($DeleteParentSQL);
    }

    header("location: /test1/Parents.php");
    exit;

 ?>