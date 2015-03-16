<?php

include ("database_params.php");

session_start();
$db_handler = mysql_connect($hostname, $username, $password);
if (mysql_errno()) {
    echo "Connection failed :" . mysql_error();
}

mysql_select_db($databasename, $db_handler);

$host = $_SERVER['SERVER_NAME'];
$uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
$message = '';

if (isset($_POST['save'])) {
    $baseTrackingUrl = $_POST['tracking_url'];
    if(substr($baseTrackingUrl, -1) != '/') {
       $baseTrackingUrl .= '/';
    }
    
    $landingPageUrl = $_POST['landing_url'];
        if(substr($landingPageUrl, -1) != '/') {
       $landingPageUrl .= '/';
    }
    
    $emailOpenUrl = $_POST['email_url'];
        if(substr($emailOpenUrl, -1) != '/') {
       $emailOpenUrl .= '/';
    }

    $query = "update baseurls set url = '" . $baseTrackingUrl . "' where id = 1";
    $result = mysql_query($query);
    if ($result) {
        $query = "update baseurls set url = '" . $landingPageUrl . "' where id = 2";
        $result = mysql_query($query);
        if ($result) {
            $query = "update baseurls set url = '" . $emailOpenUrl . "' where id = 3";
            $result = mysql_query($query);
            if ($result) {
                $message = "Records updated.";
            } else {
                $message = "Database error :" . mysql_error();
            }
            $_SESSION["definebaseurl_message"] = $message;
        }
    }
    $extra = 'definebaseurls.php';
    header("Location: http://$host$uri/$extra");
} else if ($_POST['back']) {
    $extra = 'index.php';
    header("Location: http://$host$uri/$extra");
}
?>
