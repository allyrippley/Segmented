<?php
include ("database_params.php");

$db_handler = mysql_connect($hostname, $username, $password);
$message = '';
if (mysql_errno()) {
    $message = "Connection failed :" . mysql_error();
}

mysql_select_db($databasename, $db_handler);

session_start();
$campaignId = explode("?", $_SERVER['REQUEST_URI']);
$query = 'select url from baseurls where id = 1';
$result = mysql_query($query);
$baseTrackingUrl = mysql_fetch_row($result);

// Get email tracking URL
$query = 'select url from baseurls where id = 3';
$result = mysql_query($query);
$baseEmailUrl = mysql_fetch_row($result);

$links_table_query = "select * from links where campaigns_id =" . $campaignId[1];
$links_table_result = mysql_query($links_table_query);
$links_data = array();
$i = 0;
if ($links_table_result) {
    while ($row = mysql_fetch_array($links_table_result)) {
        $links_data[$i]['destination_url'] = $row{'destination_url'};
        $links_data[$i]['generated_url'] = $row{'generated_url'};
        $i++;
    }
} else {
    $message = "Database error :" . mysql_error();
}


$campaign_table_query = "select email_open_url,name from campaigns where id =" . $campaignId[1];
$campaign_table_result = mysql_query($campaign_table_query);
$email_url = mysql_fetch_row($campaign_table_result);

$articles_table_query = "select landing_page_url from articles where campaigns_id = " . $campaignId[1];
$articles_table_result = mysql_query($articles_table_query);
$articles_url_data = array();
$j = 0;
if ($articles_table_result) {
    while ($row = mysql_fetch_array($articles_table_result)) {
        $articles_url_data[$j]['landing_page_url'] = $row{'landing_page_url'};
        $j++;
    }
} else {
    $message = "Database error :" . mysql_error();
}

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

$articleKeys = array_keys($_SESSION);
for($i = 0; $i < count($articleKeys); $i++) {
    if(strncmp($articleKeys[$i], "article_", 8) == 0) {
        unset($_SESSION[$articleKeys[$i]]);
    }

    if(strncmp($articleKeys[$i], "article_id_", 11) == 0) {
        unset($_SESSION[$articleKeys[$i]]);
    }
}


/*
if (isset($_SESSION["article_data"])) {
    $articleDataArr = $_SESSION["article_data"];
    $count = count($articleDataArr);
    for ($i = 0; $i < $count; $i++) {
        unset($_SESSION["article_" . ($i + 1)]);
        unset($_SESSION["article_id_" . ($i + 1)]);
    }
}*/
unset($_SESSION["article_data"]);
unset($_SESSION["definebaseurl_message"]);
unset($_SESSION["article_database_message"]);
unset($_SESSION["campaign_database_meessage"]);
unset($_SESSION["content_block_database_message"]);
unset($_SESSION["trackingurls_database_message"]);
unset($_SESSION["article_flag"]);
unset($_SESSION["edit_flag"]);
?>
<!doctype html>
<html>
    <head>
        <title>Campaign Manager</title>
        <link rel="stylesheet" type="text/css" href="css/reset-fonts-grids.css"/>
        <link rel="stylesheet" type="text/css" href="css/style.css">
        <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
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
                    <form action="index.php">
                        <div class="content-table">
                            <div class="content-header"><h2>View Campaign - <?php echo $email_url[1] ?></h2></div>
                            <div class="content-table-b">
                                <div class="table-header"><h3>Generated tracking page urls</h3></div>
                                <table id="hor-minimalist-b" summary="Campaigns List">
                                    <thead>
                                        <tr>
                                            <th>External Link</th>
                                            <th>Tracking url</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (empty($links_data)) {
                                            ?>
                                            <tr>
                                                <td colspan="2">
                                                    No generated tracking page urls present.
                                                </td>
                                            </tr>
                                            <?php
                                        } else {
                                            foreach ($links_data as $data) {
                                                ?>
                                                <tr>
                                                    <td>
                                                        <?php echo htmlspecialchars_decode($data['destination_url']); ?>
                                                    </td>
                                                    <td>
                                                        <a href="<?php echo $data['generated_url']; ?>"><?php echo $data['generated_url']; ?></a>
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                                <div class="table-header" style="padding-top: 25px;"><h3>Generated landing page urls </h3></div>
                                <table id="hor-minimalist-b" summary="Campaigns List">
                                    <tbody>
                                        <?php if (empty($articles_url_data)) {
                                            ?>
                                            <tr>
                                                <td>
                                                    No generated landing page urls present.
                                                </td>
                                            </tr>
                                            <?php
                                        } else {
                                            foreach ($articles_url_data as $data) {
                                                ?>
                                                <tr>
                                                    <td>
                                                        <?php echo "<a target='_blank' href=" . $data['landing_page_url'] . ">" . $data['landing_page_url'] . "</a>"; ?>
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                        }
                                        ?>

                                    </tbody>
                                </table>
                                <div class="table-header" style="padding-top: 25px;"><h3>Email open urls</h3></div>
                                <table id="hor-minimalist-b" summary="Campaigns List">
                                    <tbody>
                                        <?php if (empty($email_url)) {
                                            ?>
                                            <tr>
                                                <td>
                                                    No Email open urls present.
                                                </td>
                                            </tr>
                                        <?php } else {
                                            ?>
                                            <tr>
                                                <td>
                                                    <a href="<?php echo $email_url[0] ?>"><?php echo $email_url[0] ?></a>
                                                </td>
                                            </tr>
                                        <?php }
                                        ?>
                                    </tbody>
                                </table>
                                <div class="action">
                                    <button id="back" name="back" class="button white" value="back" style="float: right">Back</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>