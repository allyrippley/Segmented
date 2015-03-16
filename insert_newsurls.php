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
    $_SESSION["newsurls_database_message"] = $message;
    $extra = 'addnewsurls.php';
    header("Location: http://$host$uri/$extra");
}

mysql_select_db($databasename, $db_handler);

if (isset($_POST['submit'])) {
    $count = sizeof($_POST);
    $campaignId = $_SESSION["campaign_id"];
    $extra = 'contentblock.php';
    if (empty($_SESSION["link_data"])) {
        $link_count = 0;
        $newsUrlArr = array();
        for ($i = 1; $i < $count; $i++) {
            $trackingurl = cleanString($_POST['link_' . $i]);
            $link_source = cleanString($_POST['source_' . $i]);
			$link_title = cleanString($_POST['title_' . $i]);
            $link_description = cleanString($_POST['description_' . $i]);
            if ($trackingurl != '') {
                $generatedurl = generateTrackingUrl($trackingurl, $campaignId, $i);
                $query = "insert into links";
                $query .="(destination_url,generated_url,campaigns_id,source,title,description) values";
                $query .= "('$trackingurl','$generatedurl','$campaignId','$link_source','$link_title','$link_description')";
                $result = mysql_query($query);
                $id = mysql_insert_id();
                $newsUrlArr[$link_count]['id'] = $id;
                $newsUrlArr[$link_count]['url'] = $trackingurl;
                if (mysql_errno()) {
                    $message = "Database error : " . mysql_error();
                    $_SESSION["newsurls_database_message"] = $message;
                    $extra = 'addnewsurls.php';
                    header("Location: http://$host$uri/$extra");
                }
                $link_count++;
            }
        }
        $_SESSION["link_data"] = $newsUrlArr;
        header("Location: http://$host$uri/$extra");
    } else {
        $session_link_data = $_SESSION["link_data"];
        $newsUrlArr = array();
        $link_count = 0;
        for ($j = 1; $j < $count; $j++) {
            if ($j <= sizeof($session_link_data)) {
                $id = $session_link_data[($j - 1)]['id'];
                $trackingurl = cleanString($_POST['link_' . $j]);
				$link_source = cleanString($_POST['source_' . $j]);
                $link_title = cleanString($_POST['title_' . $j]);
                $link_description = cleanString($_POST['description_' . $j]);  
               // $generatedurl = generateTrackingUrl($trackingurl, $campaignId, $j);
                $query = "update links set destination_url ='$trackingurl', source='$link_source', title='$link_title', description='$link_description' ". "where id =" . $id;
                $result = mysql_query($query);
                $newsUrlArr[($j - 1)]['id'] = $id;
                $newsUrlArr[($j - 1)]['url'] = $trackingurl;
                if (mysql_errno()) {
                    $message = "Database error : " . mysql_error();
                    $_SESSION["newsurls_database_message"] = $message . " || " . $query;
                    $extra = 'addnewsurls.php';
                    header("Location: http://$host$uri/$extra");
                }
                $link_count++;
            } else {
                $trackingurl = cleanString($_POST['link_' . $j]);
				$link_source = cleanString($_POST['source_' . $j]);
                $link_title = cleanString($_POST['title_' . $j]);
                $link_description = cleanString($_POST['description_' . $j]);
                if ($trackingurl != '') {
                    $generatedurl = generateTrackingUrl($trackingurl, $campaignId, $j);
                    $query = "insert into links";
                    $query .="(destination_url,generated_url,campaigns_id,source,title,description)values";
                    $query .= "('$trackingurl','$generatedurl','$campaignId','$link_source','$link_title','$link_description')";
                    $result = mysql_query($query);
                    $id = mysql_insert_id();
                    $newsUrlArr[$link_count]['id'] = $id;
                    $newsUrlArr[$link_count]['url'] = $trackingurl;
                    if (mysql_errno()) {
                        $message = "Database error : " . mysql_error();
                        $_SESSION["newsurls_database_message"] = $message;
                        $extra = 'addnewsurls.php';
                        header("Location: http://$host$uri/$extra");
                    }
                    $link_count++;
                }
            }
        }
        $_SESSION["link_data"] = '';
        $_SESSION["link_data"] = $newsUrlArr;
        header("Location: http://$host$uri/$extra");
    }
} else if (isset($_POST['back'])) {
    $extra = 'addtrackingurls.php';
    header("Location: http://$host$uri/$extra");
}

function cleanString($string) {
	$string = preg_replace("@<script[^>]*>.+</script[^>]*>@i", "", $string);
	$string = str_replace("’", "'", $string);
	$string = mb_convert_encoding($string, "HTML-ENTITIES", "UTF-8");
	$string = mysql_real_escape_string(stripslashes($string));
	return $string;
}
?>