<?php

include ("database_params.php");
include("generateurl.php");
session_start();
$db_handler = mysql_connect($hostname, $username, $password);
$message = '';
$host = $_SERVER['SERVER_NAME'];
$uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
if (mysql_errno()) {
    $message = "Connection failed :" . mysql_error();
    $_SESSION["trackingurls_database_message"] = $message;
    $extra = 'addtrackingurls.php';
    header("Location: http://$host$uri/$extra");
}

mysql_select_db($databasename, $db_handler);

if (isset($_POST['submit'])) {
    $count = sizeof($_POST);
    $campaignId = $_SESSION["campaign_id"];
    $extra = 'addnewsurls.php';
    if (empty($_SESSION["link_data"])) {
        $link_count = 0;
        $tracUrlArr = array();
        for ($i = 1; $i < $count; $i++) {
            $trackingurl = $_POST['link_' . $i];
            $link_source = $_POST['source_' . $i];
	    //$link_title = $_POST['title_' . $i];
            if ($trackingurl != '') {
                $generatedurl = generateTrackingUrl($trackingurl, $campaignId, $i);
                $query = "insert into links";
                $query .="(destination_url,generated_url,campaigns_id,source)values";
                $query .= "('$trackingurl','$generatedurl','$campaignId','$link_source')";
                $result = mysql_query($query);
                $id = mysql_insert_id();
                $tracUrlArr[$link_count]['id'] = $id;
                $tracUrlArr[$link_count]['url'] = $trackingurl;
                if (mysql_errno()) {
                    $message = "Database error : " . mysql_error();
                    $_SESSION["trackingurls_database_message"] = $message;
                    $extra = 'addtrackingurls.php';
                    header("Location: http://$host$uri/$extra");
                }
                $link_count++;
            }
        }
        $_SESSION["link_data"] = $tracUrlArr;
        header("Location: http://$host$uri/$extra");
    } else {
        $session_link_data = $_SESSION["link_data"];
        $tracUrlArr = array();
        $link_count = 0;
        for ($j = 1; $j < $count; $j++) {
            if ($j <= sizeof($session_link_data)) {
                $id = $session_link_data[($j - 1)]['id'];
                $trackingurl = $_POST['link_' . $j];
		$link_source = $_POST['source_' . $j];
                //link_title = $_POST['title_' . $j];
               // $generatedurl = generateTrackingUrl($trackingurl, $campaignId, $j);
                $query = "update links set destination_url ='$trackingurl', source='$link_source' ". "where id =" . $id;
                $result = mysql_query($query);
                $tracUrlArr[($j - 1)]['id'] = $id;
                $tracUrlArr[($j - 1)]['url'] = $trackingurl;
                if (mysql_errno()) {
                    $message = "Database error : " . mysql_error();
                    $_SESSION["trackingurls_database_message"] = $message;
                    $extra = 'addtrackingurls.php';
                    header("Location: http://$host$uri/$extra");
                }
                $link_count++;
            } else {
                $trackingurl = $_POST['link_' . $j];
		$link_source = $_POST['source_' . $j];
                //link_title = $_POST['title_' . $i];
                if ($trackingurl != '') {
                    $generatedurl = generateTrackingUrl($trackingurl, $campaignId, $j);
                    $query = "insert into links";
                    $query .="(destination_url,generated_url,campaigns_id,source)values";
                    $query .= "('$trackingurl','$generatedurl','$campaignId','$link_source')";
                    $result = mysql_query($query);
                    $id = mysql_insert_id();
                    $tracUrlArr[$link_count]['id'] = $id;
                    $tracUrlArr[$link_count]['url'] = $trackingurl;
                    if (mysql_errno()) {
                        $message = "Database error : " . mysql_error();
                        $_SESSION["trackingurls_database_message"] = $message;
                        $extra = 'addtrackingurls.php';
                        header("Location: http://$host$uri/$extra");
                    }
                    $link_count++;
                }
            }
        }
        $_SESSION["link_data"] = '';
        $_SESSION["link_data"] = $tracUrlArr;
        header("Location: http://$host$uri/$extra");
    }
} else if (isset($_POST['back'])) {
    $extra = 'addcampaign.php';
    header("Location: http://$host$uri/$extra");
}
?>
