<?php

include ("database_params.php");
include ("generateurl.php");

session_start();
$db_handler = mysql_connect($hostname, $username, $password);
$host = $_SERVER['SERVER_NAME'];
$uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
$message = '';
if (mysql_errno()) {
    $message = "Connection failed : " . mysql_error();
    $_SESSION["campaign_database_meessage"] = $message;
    $extra = 'addcampaign.php';
    header("Location: http://$host$uri/$extra");
}

mysql_select_db($databasename, $db_handler);

if (isset($_POST['submit'])) {
    $campaign_name = $_POST['name'];
    $start_date = date("y-m-d", strtotime($_POST['start']));
    $end_date = date("y-m-d", strtotime($_POST['end']));
	$date_image = $_POST['date_image'];
    $email_open_url = generateEmailOpenUrl();

    $_SESSION["campaign_name"] = $campaign_name;
    $_SESSION["start_date"] = $start_date;
    $_SESSION["end_date"] = $end_date;

    $extra = 'addtrackingurls.php';
    if ($_SESSION["campaign_id"] == null) {
        $query = "insert into campaigns";
        $query .="(name,start_date,end_date,email_open_url,date_image)values";
        $query .= "('$campaign_name','$start_date','$end_date','$email_open_url','$date_image')";
        $result = mysql_query($query);
		
		$id = mysql_insert_id();
        $_SESSION["campaign_id"] = $id;
		
		
        if ($result) {
			$generatedURL = generateTrackingUrl();
            $query = "INSERT INTO `links` (`id`, `destination_url`, `source`, `generated_url`, `campaigns_id`, `menu_position`) VALUES ('0', 'http://smallbusiness.verizon.com/', 'page', '" . $generatedURL ."', '" . $id ."', '1');\n";
			$result = mysql_query($query);
			
			$generatedURL = generateTrackingUrl();
            $query = "INSERT INTO `links` (`id`, `destination_url`, `source`, `generated_url`, `campaigns_id`, `menu_position`) VALUES ('0', 'https://business.verizon.com/MyBusinessAccount/one.portal?_nfpb=true&_pageLabel=gb_mycommunity', 'page', '" . $generatedURL ."', '" . $id ."', '2');\n";
			$result = mysql_query($query);
			
			$generatedURL = generateTrackingUrl();
            $query = "INSERT INTO `links` (`id`, `destination_url`, `source`, `generated_url`, `campaigns_id`, `menu_position`) VALUES ('0', 'https://business.verizon.com/MyBusinessAccount/one.portal?_nfpb=true&_pageLabel=gb_myrewards', 'page', '" . $generatedURL ."', '" . $id ."', '3');\n";
			$result = mysql_query($query);
			
			$generatedURL = generateTrackingUrl();
            $query = "INSERT INTO `links` (`id`, `destination_url`, `source`, `generated_url`, `campaigns_id`, `menu_position`) VALUES ('0', 'https://business.verizon.com/MyBusinessAccount/one.portal?_nfpb=true&_pageLabel=gb_webinar', 'page', '" . $generatedURL ."', '" . $id ."', '4');\n";
			$result = mysql_query($query);
			
			$generatedURL = generateTrackingUrl();
            $query = "INSERT INTO `links` (`id`, `destination_url`, `source`, `generated_url`, `campaigns_id`, `menu_position`) VALUES ('0', 'https://business.verizon.com/MyBusinessAccount/one.portal?_nfpb=true&_pageLabel=gb_newsletter&i=0', 'page', '" . $generatedURL ."', '" . $id ."', '5');\n";
			$result = mysql_query($query);
			
			$generatedURL = generateTrackingUrl();
            $query = "INSERT INTO `links` (`id`, `destination_url`, `source`, `generated_url`, `campaigns_id`, `menu_position`) VALUES ('0', 'https://www22.verizon.com/home/verizonglobalhome/ghp_business_signin.aspx', 'page', '" . $generatedURL ."', '" . $id ."', '6');\n";
			$result = mysql_query($query);
			
			$generatedURL = generateTrackingUrl();
            $query = "INSERT INTO `links` (`id`, `destination_url`, `source`, `generated_url`, `campaigns_id`, `menu_position`) VALUES ('0', 'http://www22.verizon.com/support/smallbusiness/homepage.htm', 'page', '" . $generatedURL ."', '" . $id ."', '7');\n";
			$result = mysql_query($query);
			
			$generatedURL = generateTrackingUrl();
            $query = "INSERT INTO `links` (`id`, `destination_url`, `source`, `generated_url`, `campaigns_id`, `menu_position`) VALUES ('0', 'http://forums.verizon.com/t5/Small-Biz-Blog/bg-p/SMBBlog', 'page', '" . $generatedURL ."', '" . $id ."', '8');\n";
			$result = mysql_query($query);
			
			$generatedURL = generateTrackingUrl();
            $query = "INSERT INTO `links` (`id`, `destination_url`, `source`, `generated_url`, `campaigns_id`, `menu_position`) VALUES ('0', 'http://www.facebook.com/VerizonSmallBiz', 'page', '" . $generatedURL ."', '" . $id ."', '9');\n";
			$result = mysql_query($query);
			
			$generatedURL = generateTrackingUrl();
            $query = "INSERT INTO `links` (`id`, `destination_url`, `source`, `generated_url`, `campaigns_id`, `menu_position`) VALUES ('0', 'http://gplus.to/verizonSMB', 'page', '" . $generatedURL ."', '" . $id ."', '10');\n";
			$result = mysql_query($query);
			
			$generatedURL = generateTrackingUrl();
            $query = "INSERT INTO `links` (`id`, `destination_url`, `source`, `generated_url`, `campaigns_id`, `menu_position`) VALUES ('0', 'http://www.linkedin.com/company/verizon-small-business', 'page', '" . $generatedURL ."', '" . $id ."', '11');\n";
			$result = mysql_query($query);
			
			$generatedURL = generateTrackingUrl();
            $query = "INSERT INTO `links` (`id`, `destination_url`, `source`, `generated_url`, `campaigns_id`, `menu_position`) VALUES ('0', 'http://twitter.com/vzsmallbiz', 'page', '" . $generatedURL ."', '" . $id ."', '12');\n";
			$result = mysql_query($query);
			
			$generatedURL = generateTrackingUrl();
            $query = "INSERT INTO `links` (`id`, `destination_url`, `source`, `generated_url`, `campaigns_id`, `menu_position`) VALUES ('0', 'https://wecommerce.verizon.com/', 'page', '" . $generatedURL ."', '" . $id ."', '13');\n";
			$result = mysql_query($query);
			
			if ($result) {
				header("Location: http://$host$uri/$extra");
			}

        } else {
			$extra = 'addcampaign.php';
            $message = "Database error : " . mysql_error();
            $_SESSION["campaign_database_meessage"] = $message;
            header("Location: http://$host$uri/$extra");
        }
    } else {
        header("Location: http://$host$uri/$extra");
    }
} else if (isset($_POST['back'])) {

    $extra = 'index.php';
    header("Location: http://$host$uri/$extra");
}
?>
