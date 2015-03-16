<?php

include ("database_params.php");
include ("generateurl.php");
include ("simple_html_dom.php");

// Get verbose errors for troubleshooting
//if (!ini_get('display_errors')) {
//    ini_set('display_errors', '1');
//}

session_start();
$db_handler = mysql_connect($hostname, $username, $password);

if (mysql_errno()) {
    echo "Connection failed : " . mysql_error();
}

mysql_select_db($databasename, $db_handler);
$message = '';
$host = $_SERVER['SERVER_NAME'];
$uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
$campaignId = $_SESSION["campaign_id"];
if (isset($_POST['id'])) {
    $campaignId = $_POST['id'];
}

if (isset($_POST['save'])) {
    $emailContent = $_POST["emailContent"];
    if (isset($_SESSION["emailContent"])) {
        $emailContent = $_SESSION["emailContent"];
        unset($_SESSION["emailContent"]);
    }
}

    // Parse all content for links, and if they aren't in the database, create tracking URLs
    $query = "select url from baseurls where id = 2";
    $result = mysql_query($query);
    $baseTrackingUrl = mysql_fetch_row($result);
    
    $link_source = "email";
    $html = str_get_html($emailContent);
    
    if (strlen($html) > 0) {
        foreach($html->find('a') as $element)  {
            if ((stripos($element, 'mailto:') == false) && (stripos($row['destination_url'], $baseTrackingUrl) == false)) {
                if (stripos($element->href, '://') == false) { $element->href = $baseTrackingUrl[0] . $element->href; }
                //echo $element->href . '<br>';
                $query = "SELECT *  FROM `links` WHERE `destination_url` LIKE '" . $element->href . "' AND `campaigns_id` = '$campaignId' AND `source` = 'email'";
                $result = mysql_query($query);
                $row = mysql_fetch_row($result);

                // If the URL is not in the database then add the tracking URL
                if (empty($row)) {
                    $generatedurl = generateTrackingUrl($element->href, $campaignId, 1);
                    $query = "insert into links";
                    $query .="(destination_url,generated_url,campaigns_id,source)values";
                    $query .= "('$element->href','$generatedurl','$campaignId','$link_source')";
                    $result = mysql_query($query);
                }
            }
        }
    }
    
    // query for all links belonging to this campaign and replace the content
    $query = "select * from links where campaigns_id =" . $campaignId . " AND `source` = 'email'";
    $result = mysql_query($query);
    $i = 1;
    while ($row = mysql_fetch_array($result)) {
                // Use this loop to replace destination URLs in the article content
                if ($row['source']=="email") {
                    $emailContent = str_ireplace($row['destination_url'], $row['generated_url'] . "&uid=[customer_id]", $emailContent);
                }
	}
        
    ?>
    
<html>
    <head><title>Email Code with Tracking Links</title></head>
    <body>
        <h1>Revised Email HTML</h1>
        <form>
            <textarea><?php echo $emailContent; ?></textarea>
        </form>
    </body>
</html>
