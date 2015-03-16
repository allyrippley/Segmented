<?php
include ("database_params.php");
session_start();
unset($_SESSION["link_data"]);
unset($_SESSION["campaign_name"]);
unset($_SESSION["start_date"]);
unset($_SESSION["end_date"]);
unset($_SESSION["campaign_id"]);
unset($_SESSION["content_id"]);
unset($_SESSION["block_1_option_1"]);
unset($_SESSION["block_1_option_2"]);
unset($_SESSION["block_1_option_3"]);
unset($_SESSION["block_1_option_4"]);
unset($_SESSION["block_2_option_1"]);
unset($_SESSION["block_2_option_2"]);
unset($_SESSION["block_2_option_3"]);
unset($_SESSION["block_2_option_4"]);
unset($_SESSION["block_3_option_1"]);
unset($_SESSION["block_3_option_2"]);
unset($_SESSION["block_3_option_3"]);
unset($_SESSION["block_3_option_4"]);
unset($_SESSION["block_4_option_1"]);
unset($_SESSION["block_4_option_2"]);
unset($_SESSION["block_4_option_3"]);
unset($_SESSION["block_4_option_4"]);

// first get all keys from session
$articleKeys = array_keys($_SESSION);
for($i = 0; $i < count($articleKeys); $i++) {
    if(strncmp($articleKeys[$i], "article_", 8) == 0) {
        unset($_SESSION[$articleKeys[$i]]);
    }

    if(strncmp($articleKeys[$i], "article_id_", 11) == 0) {
        unset($_SESSION[$articleKeys[$i]]);
    }
}

/*if (isset($_SESSION["article_data"])) {
    $articleDataArr = $_SESSION["article_data"];
    $count = count($articleDataArr);
    for ($i = 0; $i < $count; $i++) {
        unset($_SESSION["article_" . ($i + 1)]);
        unset($_SESSION["article_id_" . ($i + 1)]);
    }
}
*/

unset($_SESSION["article_data"]);
unset($_SESSION["definebaseurl_message"]);
unset($_SESSION["article_database_message"]);
unset($_SESSION["campaign_database_meessage"]);
unset($_SESSION["content_block_database_message"]);
unset($_SESSION["trackingurls_database_message"]);
unset($_SESSION["article_flag"]);
unset($_SESSION["edit_flag"]);

$db_handler = mysql_connect($hostname, $username, $password);
$message = '';
if (mysql_errno()) {
    $message = "Connection failed : " . mysql_error();
}

mysql_select_db($databasename, $db_handler);

$result = mysql_query("select * from campaigns");
$campaigns_data = array();
$i = 0;
if (!$result) {
    $message = "Database error : " . mysql_error();
} else {
    while ($row = mysql_fetch_array($result)) {
        $campaigns_data[$i]['id'] = $row{'id'};
        $campaigns_data[$i]['name'] = $row{'name'};
        if ($row{'start_date'} || $row{'end_date'}) {
            $campaigns_data[$i]['start_date'] = date("d M Y", strtotime($row{'start_date'})); //$row{'start_date'};
            $campaigns_data[$i]['end_date'] = date("d M Y", strtotime($row{'end_date'}));
        }

        $i++;
    }
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
                    <div class="menu"><a href="index.php">View Dashboard</a>&nbsp;|&nbsp;<a href="definebaseurls.php">Define Base URLs</a></div>
                    <div class="error-box">
                        <label style="color: red;text-align: center" id="show_error">
                            <?php
                            if ($message != '') {
                                echo $message;
                            }
                            ?></label>
                    </div>
                    <div class="content-table">
                        <form  action="addcampaign.php" method="post">

                            <div class="table-header"><h2>Campaigns List</h2></div>
                            <table id="hor-minimalist-b" summary="Campaigns List">
                                <thead>
                                    <tr>
                                        <th scope="col">Campaign Name</th>
                                        <th scope="col">Start Date</th>
                                        <th scope="col">End Date</th>
                                        <th scope="col">View Campaign</th>
                                        <th scope="col">Edit Campaign</th>
                                        <th scope="col">Process Email</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (empty($campaigns_data)) {
                                        ?>
                                        <tr>
                                            <td colspan="5" style="text-align: center; font-size: 14px;">
                                                No campaigns present.
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                    foreach ($campaigns_data as $data) {
                                        $id = $data['id'];
                                        ?>
                                        <tr>
                                            <td><a href="performancereport.php?id=<?php echo $id; ?>" id="campaignName"><?php echo $data['name'] ?></a></td>
                                            <td><?php echo $data['start_date'] ?></td>
                                            <td><?php echo $data['end_date'] ?></td>
                                            <td><a href="campaignview.php?<?php echo $id; ?>">View</a></td>
                                            <td><a href="addcampaign.php?id=<?php echo $id; ?>&flag=1" id="report">Edit</a></td>
                                            <td><a href="generate_email_urls.php?id=<?php echo $id; ?>" id="emailprocess">Process</a></td>
                                        </tr>
                                    <?php } ?>

                                </tbody>
                            </table>
                            <div class="action"><button class="button white">Add New Campaign</button></div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>