<?php
include("database_params.php");

session_start();

$db_handler = mysql_connect($hostname, $username, $password);
if (mysql_errno()) {
    echo "Connection failed :" . mysql_error();
}
mysql_select_db($databasename, $db_handler);



$query = "select url from baseurls where id = 1";
$result = mysql_query($query);
$row = mysql_fetch_row($result);
$baseTrackingUrl = $row[0];

$query = "select url from baseurls where id = 2";
$result = mysql_query($query);
$row = mysql_fetch_row($result);
$landingPageUrl = $row[0];

$query = "select url from baseurls where id = 3";
$result = mysql_query($query);
$row = mysql_fetch_row($result);
$emailOpenUrl = $row[0];

$message = '';
if (isset($_SESSION["definebaseurl_message"])) {
    $message = $_SESSION["definebaseurl_message"];
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
                        <label style="color: red;text-align: center" id="show_error"><?php echo $message ?></label>
                    </div>
                    <form action="insert_definebaseurls.php" method="post" >
                        <div class="content-table">
                            <div class="content-header"><h2>Define Base URLs</h2></div>
                            <div class="content-table-b">
                                <div class="content-table-data">
                                    <ul>
                                        <li>
                                            <label>Base Tracking URL :</label>
                                        </li>
                                        <li>
                                            <?php if ($baseTrackingUrl != null) {
                                                ?>
                                                <input type="text" id="tracking_url" name="tracking_url" value="<?php echo $baseTrackingUrl ?>" />
                                            <?php } else { ?>
                                                <input type="text" id="tracking_url" name="tracking_url" value=""/>
                                            <?php } ?>
                                        </li>
                                    </ul>
                                    <ul>
                                        <li>
                                            <label>Base Landing Page URL :</label>
                                        </li>
                                        <li>
                                            <?php if ($landingPageUrl != null) {
                                                ?>
                                                <input type="text" id="landing_url" name="landing_url" value="<?php echo $landingPageUrl ?>" />
                                            <?php } else { ?>
                                                <input type="text" id="landing_url" name="landing_url" value=""/>
                                            <?php } ?>
                                        </li>
                                    </ul>
                                    <ul>
                                        <li>
                                            <label>Base Email Open URL :</label>
                                        </li>
                                        <li>
                                            <?php if ($emailOpenUrl != null) {
                                                ?>
                                                <input type="text" id="email_url" name="email_url" value="<?php echo $emailOpenUrl ?>" />
                                            <?php } else { ?>
                                                <input type="text" id="email_url" name="email_url" value=""/>
                                            <?php } ?>
                                        </li>
                                    </ul>
                                </div>
                                <div class="action">
                                    <ul>
                                        <li>
                                            <button class="button white" type="submit" name="save" id="save" value="save">Save</button>
                                        </li>
                                        <li style="float: right;margin-top: 10px;padding-right: 10px;">
                                            <button id="back" name="back" type="submit" class="button white" value="back" onclick="gotohome();">Back</button>
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

