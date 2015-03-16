<?php
include("database_params.php");

session_start();

$campaignid = $_GET['id'];
unset($_SESSION["campaign_id"]);
$_SESSION["campaign_id"] = $campaignid;

$db_handler = mysql_connect($hostname, $username, $password);
if (mysql_errno()) {
    echo "Connection failed :" . mysql_error();
}
mysql_select_db($databasename, $db_handler);



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
                    <form action="insert_email_urls.php" method="post" >
                        <input type="hidden" name="id" value="<?php echo $campaignid; ?>">
                        <div class="content-table">
                            <div class="content-header"><h2>Generate Email Code</h2></div>
                            <div class="content-table-b">
                                <div class="content-table-data">
                                    <ul>
                                        <li>
                                            <label>Email Content (***Article Pages must be coded already***) :</label>
                                        </li>
                                        <li>
                                            <textarea id="emailContent" name="emailContent" rows="20" style="width:664px;height:320px;"></textarea><br />
                                        </li>
                                    </ul>
                                </div>
                                <div class="action">
                                    <ul>
                                        <li>
                                            <button class="button white" type="submit" name="save" id="save" value="save">Generate Email</button>
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

