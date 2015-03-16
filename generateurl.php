<?php

function generateEmailOpenUrl() {
    // get url of id 3
    $query = "select url from baseurls where id = 3";
    $result = mysql_query($query);
    $emailOpenUrl = mysql_fetch_row($result);

    $url = $emailOpenUrl[0] . "emailopen.php?key=" . generateRandomString();
    return $url;
}

function generateTrackingUrl() {
    // get url of id 1
    $query = "select url from baseurls where id = 1";
    $result = mysql_query($query);
    $baseTrackingUrl = mysql_fetch_row($result);

    $url = $baseTrackingUrl[0] . "redirect.php?key=" . generateRandomString();
    return $url;
}

function generateLandingPageUrl() {
    // get url of id 2
    $query = "select url from baseurls where id = 2";
    $result = mysql_query($query);
    $landingPageUrl = mysql_fetch_row($result);

    $url = $landingPageUrl[0] . "home.php?key=" . generateRandomString();
    return $url;
}

function matchURL($ranStr) {

	// Confirm that the generated URL is not in the existing URLS:
	//		TABLE:		COLUMN
	// 		campaigns: 	email_open_url
	//		articles: 	landing_page_url
	//		links:		generated_url
	
	$URLMatch = false;
	
	$query = "SELECT *  FROM `campaigns` WHERE `email_open_url` LIKE '" . $ranStr . "'";
	$result = mysql_query($query);
	if (mysql_num_rows($result) > 0) { $URLMatch = true; }
	
	$query = "SELECT *  FROM `articles` WHERE `landing_page_url` LIKE '" . $ranStr . "'";
	$result = mysql_query($query);
	if (mysql_num_rows($result) > 0) { $URLMatch = true; }
	
	$query = "SELECT *  FROM `links` WHERE `generated_url` LIKE '" . $ranStr . "'";
	$result = mysql_query($query);
	if (mysql_num_rows($result) > 0) { $URLMatch = true; }
	
	return $URLMatch;

}

function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    
	do {
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, strlen($characters) - 1)];
		}
	} while (matchURL($randomString)=='1');
    return $randomString;
}

?>
