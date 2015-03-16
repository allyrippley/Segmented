<?php
include("database_params.php");
include("breadcrumbs.php");
$db_handler = mysql_connect($hostname, $username, $password);
$message = '';
if (mysql_errno()) {
    $message = "Connection failed : " . mysql_error();
}

mysql_select_db($databasename, $db_handler);
session_start();
$campaignName = "";
$displayName = "";
$headerLink = "";
$startDate = "";
$endDate = "";
$dateImage = "";

if (isset($_GET['id']) && isset($_GET['flag'])) {
    $campaignid = $_GET['id'];
    $flag = $_GET['flag'];
}

if ($flag) {
    $_SESSION["campaign_id"] = $campaignid;
    $_SESSION["edit_flag"] = $flag;
    $query = "select * from campaigns where id =" . $campaignid;
    $result = mysql_query($query);
    if ($result) {
        //$camapignData = mysql_fetch_row($result);
		$campaignData = mysql_fetch_array($result);
        $campaignName = $campaignData{'name'};//[1];
        $displayName = $campaignData{'display_name'};//[2]
        $headerLink = $campaignData{'header_link'};//[3];
        $startDate = $campaignData{'start_date'};//[4];
        $endDate = $campaignData{'end_date'};//[5];
	$dateImage = $campaignData{'date_image'};
    } else {
        $message = "Database error : " . mysql_error();
    }
}

if (isset($_SESSION["campaign_name"])) {
    $campaignName = $_SESSION["campaign_name"];
}

if (isset($_SESSION["display_name"])) {
    $displayName = $_SESSION["display_name"];
}

if (isset($_SESSION["header_link"])) {
    $headerLink = $_SESSION["header_link"];
}

if (isset($_SESSION["start_date"])) {
    $startDate = $_SESSION["start_date"];
}

if (isset($_SESSION["end_date"])) {
    $endDate = $_SESSION["end_date"];
}

if (isset($_SESSION["date_image"])) {
    $dateImage = $_SESSION["date_image"];
}


if (isset($_SESSION["campaign_database_meessage"])) {
    $message = $_SESSION["campaign_database_meessage"];
    unset($_SESSION["campaign_database_meessage"]);
}
?>

<!doctype html>
<html>
    <head>
        <title>Campaign Manager</title>
        <link rel="stylesheet" type="text/css" href="css/reset-fonts-grids.css"/>
        <link rel="stylesheet" type="text/css" href="css/style.css">
        <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.0/themes/base/jquery-ui.css" />
        <script src="http://code.jquery.com/jquery-1.8.3.js"></script>
        <script src="http://code.jquery.com/ui/1.10.0/jquery-ui.js"></script>
        <script type="text/javascript">
            $(document).ready(function(){
                $("#start").datepicker({ dateFormat: 'dd M yy' });
                $("#end").datepicker({ dateFormat: 'dd M yy' });
            });
        </script>
        <script type="text/javascript">
            function validateForm(){

                var campaign_name = document.forms['add']['name'].value;

                if(campaign_name == ""){
                    document.getElementById("show_error").innerHTML = "Campaign name should not be empty";
                    return false;
                }

                var startdate = document.forms['add']['start'].value;
                if(startdate == ""){
                    document.getElementById("show_error").innerHTML = "Start date should not be empty";
                    return false;
                }

                var enddate = document.forms['add']['end'].value;
                if(enddate == ""){
                    document.getElementById("show_error").innerHTML = "End date should not be empty";
                    return false;
                }
                if(startdate > enddate){
                    document.getElementById("show_error").innerHTML = "End date should be greater than or equal to Start date.";
                    return false;
                }

            }
        </script>
    </head>
    <body class="yui-skin-sam">

        <div id="doc3" class="yui-t7">
            <div id="hd" role="banner">
                <div class="header-text"><h1>Campaign Management</h1></div>
            </div>
            <div id="bd" role="main">
                <div class="yui-g">
                    <div class="menu"><a href="index.php">View Dashboard</a></div>
                    <div class="error-box">
                        <label style="color: red;text-align: center" id="show_error">
                            <?php
                            if ($message != '') {
                                echo $message;
                            }
                            ?></label>
                    </div>
                    <form id="add" name="add" action="insert_campaign.php" method="post">
                        <div class="content-table">
                            <div class="content-header">
                                <?php if (isset($_SESSION['edit_flag'])) { ?>
                                    <h2>Edit Campaign - <?php echo $campaignName; ?></h2>
                                <?php } else { ?>
                                    <h2>Create New Campaign</h2>
                                <?php } ?>
                            </div>
                            <div>
                                <?php addBreadcrumbs(1); ?>
                            </div>
                            <div class="content-table-b">
                                <div class="content-table-data">
                                    <ul>
                                        <li>
                                            <label>Campaign Name :</label>
                                        </li>
                                        <li>
                                            <?php if ($campaignName != null) {
                                                ?>
                                                <input type="text" id="name" name="name" value="<?php echo $campaignName ?>" readonly="readonly"/>
                                            <?php } else { ?>
                                                <input type="text" id="name" name="name" value=""/>
                                            <?php } ?>
                                        </li>
                                    </ul>
                                    <ul>
                                        <li>
                                            <label>Campaign Display Name :</label>
                                        </li>
                                        <li>
                                            <?php if ($displayName != null) {
                                                ?>
                                                <input type="text" id="display_name" name="display_name" value="<?php echo $displayName ?>" readonly="readonly"/>
                                            <?php } else { ?>
                                                <input type="text" id="display_name" name="display_name" value=""/>
                                            <?php } ?>
                                        </li>
                                    </ul>
                                    <ul>
                                        <li>
                                            <label>Link for Campaign Header :</label>
                                        </li>
                                        <li>
                                            <?php if ($headerLink != null) {
                                                ?>
                                                <input type="text" id="header_link" name="header_link" value="<?php echo $headerLink ?>" readonly="readonly"/>
                                            <?php } else { ?>
                                                <input type="text" id="header_link" name="header_link" value=""/>
                                            <?php } ?>
                                        </li>
                                    </ul>                                    
                                    <ul>
                                        <li>
                                            <label>Start Date :</label>
                                        </li>
                                        <li>
                                            <?php if ($startDate != null) {
                                                ?>
                                                <input type="text" name="start"  value="<?php echo date("d M Y", strtotime($startDate)) ?>" readonly="readonly"/>
                                            <?php } else { ?>
                                                <input type="text" name="start" id="start" value="" readonly="readonly"/>
                                            <?php } ?>
                                        </li>
                                    </ul>
                                    <ul>
                                        <li>
                                            <label>End Date :</label>
                                        </li>
                                        <li>
                                            <?php if ($endDate != null) {
                                                ?>
                                                <input type="text"  name="end"  value="<?php echo date("d M Y", strtotime($endDate)) ?>" readonly="readonly"/>
                                            <?php } else { ?>
                                                <input type="text"  name="end" id="end" value="" readonly="readonly"/>
                                            <?php } ?>
                                        </li>
                                    </ul>
                                    <!--
                                    <ul>
                                        <li>
                                            <label>Date Bug (image) :</label>
                                        </li>
                                        <li>
                                            <?php if ($dateImage != null) {
                                                ?>
                                                <input type="text" id="date_image" name="date_image" value="<?php echo $dateImage ?>" readonly="readonly"/>
                                            <?php } else { ?>
                                                <input type="text" id="date_image" name="date_image" value=""/>
                                            <?php } ?>
                                        </li>
                                    </ul>
                                    -->									
                                </div>
                                <div class="action">
                                    <ul>
                                        <li>
                                            <button class="button white" type="submit" name="submit" id="submit" value="submit" onclick="return validateForm();">Next &#X00bb;</button>
                                        </li>
                                        <li style="float: right;padding-right: 10px;">
                                            <button class="button white" id="back" name="back" type="submit" value="back" >Back</button>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </body>
</html>