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
$articleSessionDataArr = array();
if (isset($_POST['addArticle'])) {
    if (isset($_SESSION["article_data"])) {
        $articleSessionDataArr = $_SESSION["article_data"];
        unset($_SESSION["article_data"]);
    }
    $articlePageId = $_POST['articlePageId'];
    $data = $_POST['articlePara'];
	$articleTitle = $_POST['article_title'];
	$articleSummary = $_POST['article_summary'];
	$articleFooter = $_POST['articleFooter'];
    $articleData = array();
    $articleData['pageid'] = $articlePageId;
    $articleData['pagedata'] = mysql_real_escape_string(stripslashes($data));
	$articleData['pagetitle'] = stripslashes($articleTitle);
	$articleData['pagesummary'] = stripslashes($articleSummary);
	$articleData['pagefooter'] = stripslashes($articleFooter);
    array_push($articleSessionDataArr, $articleData);
    $_SESSION["article_data"] = $articleSessionDataArr;
    $_SESSION["article_" . $articlePageId] = mysql_real_escape_string(stripslashes($data));
	$_SESSION["article_title_" . $articlePageId] = stripslashes($_POST['article_title']);
	$_SESSION["article_summary_" . $articlePageId] = stripslashes($_POST['article_summary']);
	$_SESSION["article_footer_" . $articlePageId] = stripslashes($_POST['articleFooter']);
    $articlePageId = ($articlePageId + 1);
    $extra = "addarticle.php?id=$articlePageId";
    header("Location: http://$host$uri/$extra");
}
if (isset($_POST['saveCampaign'])) {

    // write current page data into the session
    if (isset($_SESSION["article_data"])) {
        $articleSessionDataArr = $_SESSION["article_data"];
        unset($_SESSION["article_data"]);
    }
    $articlePageId = $_POST['articlePageId'];
    $data = $_POST['articlePara'];
	$articleTitle = $_POST['article_title'];
	$articleSummary = $_POST['article_summary'];
	$articleFooter = $_POST['articleFooter'];
    $articleData = array();
    $articleData['pageid'] = $articlePageId;
    $articleData['pagedata'] = mysql_real_escape_string(stripslashes($data));
	$articleData['pagetitle'] = stripslashes($articleTitle);
	$articleData['pagesummary'] = stripslashes($articleSummary);
	$articleData['pagefooter'] = stripslashes($articleFooter);
    array_push($articleSessionDataArr, $articleData);
    $_SESSION["article_data"] = $articleSessionDataArr;
    $_SESSION["article_" . $articlePageId] = mysql_real_escape_string(stripslashes($data));
	$_SESSION["article_title_" . $articlePageId] = stripslashes($_POST['article_title']);
	$_SESSION["article_summary_" . $articlePageId] = stripslashes($_POST['article_summary']);
	$_SESSION["article_footer_" . $articlePageId] = stripslashes($_POST['articleFooter']);


    // get session data and write it into database
    $articleDataArr = array();
    if (isset($_SESSION["article_data"])) {
        $articleDataArr = $_SESSION["article_data"];
        $count = count($articleDataArr);
        for ($i = 0; $i < $count; $i++) {
            $articledata = mysql_real_escape_string(stripslashes($articleDataArr[$i]['pagedata']));
			$articleTitle = stripslashes($articleDataArr[$i]['pagetitle']);
			$articleSummary = mysql_real_escape_string(stripslashes($articleDataArr[$i]['pagesummary']));
            $articlepageid = $articleDataArr[$i]['pageid'];
			$articleData['pagefooter'] = stripslashes($articleFooter);
            $landing_page_url = generateLandingPageUrl();
            if (isset($_SESSION["article_id_" . ($i + 1)])) {
                $id = $_SESSION["article_id_" . ($i + 1)];
                //$query = "update articles set article_data = " . " '$articledata' " . "where id = " . $id;
				$query = "update articles set article_data = " . " '$articledata', summary = " . " '$articleSummary', title = '$articleTitle', footer = '$articleFooter' " . "where id = " . $id;
                $result = mysql_query($query);
                if ($result) {
                    // successfully updated;
                } else {
                    $message = "Database error  : " . mysql_error();
                    $_SESSION["article_database_message"] = $message;
                    $extra = "addarticle.php?id=$articlepageid";
                    header("Location: http://$host$uri/$extra");
                }
            } else {
                //$query = "insert into articles (article_data,landing_page_url,campaigns_id)";
                //$query .= "values ('$articledata','$landing_page_url','$campaignId')";
                $query = "insert into articles (article_data,landing_page_url,campaigns_id,summary,title,footer)";
                $query .= "values ('$articledata','$landing_page_url','$campaignId','$articleSummary','$articleTitle','$articleFooter')";
				$result = mysql_query($query);
                $articleId = mysql_insert_id();
                if (mysql_errno()) {
                    $message = "Database error  : " . mysql_error();
                    $_SESSION["article_database_message"] = $message;
                    $extra = "addarticle.php?id=$articlepageid";
                    header("Location: http://$host$uri/$extra");
                }
            }
        }
    }
    
    // Parse the article for all links, and if they aren't in the database, create tracking URLs
    $query = "select url from baseurls where id = 2";
    $result = mysql_query($query);
    $baseTrackingUrl = mysql_fetch_row($result);
    
    $link_source = "page";
    $html =  str_get_html($_POST['articlePara']);
    if (strlen($html) > 0) {
        foreach($html->find('a') as $element)  {
            if (stripos($element, 'mailto:') == false) {
                if (stripos($element->href, '://') == false) { $element->href = $baseTrackingUrl[0] . $element->href; }
                //echo $element->href . '<br>';
                $query = "SELECT *  FROM `links` WHERE `destination_url` LIKE '" . $element->href . "' AND `campaigns_id` = '$campaignId'";
				echo $query . "<br />";
                $result = mysql_query($query);
                $row = mysql_fetch_row($result);

                // If the URL is not in the database then add the tracking URL
                if (empty($row)) {
                    $generatedurl = generateTrackingUrl($element->href, $campaignId, 1);
                    $query = "insert into links";
                    $query .="(destination_url,generated_url,campaigns_id,source)values";
                    $query .= "('$element->href','$generatedurl','$campaignId','$link_source')";
					echo $query . "<br />";
                    $result = mysql_query($query);
                }
            }
        }
    }
    
    $extra = "campaignview.php?$campaignId";
    header("Location: http://$host$uri/$extra");
}

if (isset($_POST['back'])) {
    $host = $_SERVER[SERVER_NAME];
    $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
    $articleData = array();
    if (isset($_SESSION["article_data"])) {
        $articleDataArr = $_SESSION["article_data"];
        if (count($articleDataArr) > 0) {
            $articleData = array_pop($articleDataArr);
            $pageid = $articleData['pageid'];
            $pagedata = $articleData['pagedata'];
			$pagetitle = $articleData['pagetitle'];
			$pagesummary = $articleData['pagesummary'];
			$articleFooter = $_POST['articleFooter'];
            $_SESSION["article_page_id"] = $pageid;
            $_SESSION["article_page_data"] = $pagedata;
			$_SESSION["article_page_title"] = $pagetitle;
			$_SESSION["article_page_summary"] = $pagesummary;
			$_SESSION["article_footer_" . $articlePageId] = stripslashes($_POST['articleFooter']);			
            $extra = "addarticle.php?id=$pageid";
            unset($_SESSION["article_data"]);
            $_SESSION["article_data"] = $articleDataArr;
            header("Location: http://$host$uri/$extra");
        } else {
            $extra = "contentblock.php";
            header("Location: http://$host$uri/$extra");
        }
    } else {
        $extra = "contentblock.php";
        header("Location: http://$host$uri/$extra");
    }
}
?>
