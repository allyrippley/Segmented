<?php
include("database_params.php");
include("breadcrumbs.php");
$db_handler = mysql_connect($hostname, $username, $password);
$message = '';
if (mysql_errno()) {
    $message = "Connection failed :" . mysql_error();
}

mysql_select_db($databasename, $db_handler);
session_start();

$linksArr = array();
if (isset($_SESSION["edit_flag"])) {
    $campaignid = $_SESSION["campaign_id"];
    $query = "select * from links where `source` NOT LIKE 'news' AND campaigns_id = " . $campaignid;
    $result = mysql_query($query);
    if ($result) {
        $j = 0;
        while ($row = mysql_fetch_array($result)) {
			if (is_null($row{'menu_position'})) {
				$linksArr[$j]['id'] = $row{'id'};
				$linksArr[$j]['url'] = $row{'destination_url'};
				$linksArr[$j]['source'] = $row{'source'};
                                //$linksArr[$j]['title'] = $row{'title'};
				$j++;
			}
        }
    } else {
        $message = "Database error : " . mysql_error();
    }
    $_SESSION["link_data"] = $linksArr;
}


if (!empty($_SESSION["link_data"])) {
    $linksArr = $_SESSION["link_data"];
}


if (isset($_SESSION["trackingurls_database_message"])) {
    $message = $_SESSION["trackingurls_database_message"];
    unset($_SESSION["trackingurls_database_message"]);
}
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
                            ?>
                        </label>
                    </div>
                    <form action="insert_trackingurls.php" id="trackingurl" name="trackingurl" method="post">
                        <div class="content-table">
                            <div class="content-header">
                                <?php if (isset($_SESSION['edit_flag'])) { ?>
                                    <h2>Edit Campaign - <?php echo $_SESSION["campaign_name"]; ?></h2>
                                <?php } else { ?>
                                    <h2>Create New Campaign</h2>
                                <?php } ?>
                            </div>
                            <div>
                                <?php addBreadcrumbs(2); ?>
                            </div>
                            <div class="content-table-b">
                                <div id="link-div">
                                    <?php
                                    if (!empty($linksArr)) {
                                        for ($i = 1; $i <= sizeof($linksArr); $i++) {
                                            ?>
                                            <ul>
                                                <li>
                                                    <label>External Link <?php echo $i ?> :</label>
                                                </li>
                                                <li>
                                                    <input type="text" name="link_<?php echo $i; ?>" id="link_<?php echo $i; ?>" value="<?php echo htmlspecialchars_decode($linksArr[($i - 1)]['url']); ?>"/>
                                                </li>
                                               												<li>
													<select name="source_<?php echo $i; ?>" id="source_<?php echo $i; ?>"/>
														<option value="page"<?php if ($linksArr[($i - 1)]['source'] == 'page') { echo ' selected="selected"'; }; ?>>Landing Page</option>
														<option value="email"<?php if ($linksArr[($i - 1)]['source'] == 'email') { echo ' selected="selected"'; }; ?>>Email</option>
													</select>												
												</li>
                                            </ul>
                                            <?php
                                        }
                                    } else {
                                        ?>
                                        <ul>
                                            <li>
                                                <label>External Link 1 :</label>
                                            </li>
                                            <li>
                                                <input type="text" name="link_1" id="link_1"/>
                                            </li>
                                            <li>
                                                <select name="source_1" id="source_1"/>
													<option value="page">Landing Page</option>
													<option value="email">Email</option>
												</select>
                                            </li>											
                                        </ul>
                                        <ul>
                                            <li>
                                                <label>External Link 2 :</label>
                                            </li>
                                            <li>
                                                <input type="text" name="link_2" id="link_2" />
                                            </li>
                                            <li>
                                                <select name="source_2" id="source_2"/>
													<option value="page">Landing Page</option>
													<option value="email">Email</option>
												</select>
                                            </li>	
                                        </ul>
                                        <ul>
                                            <li>
                                                <label>External Link 3 :</label>
                                            </li>
                                            <li>
                                                <input type="text" name="link_3" id="link_3" />
                                            </li>
                                            <li>
                                                <select name="source_3" id="source_3"/>
													<option value="page">Landing Page</option>
													<option value="email">Email</option>
												</select>
                                            </li>	
                                        </ul>
                                        <ul>
                                            <li>
                                                <label>External Link 4 :</label>
                                            </li>
                                            <li>
                                                <input type="text"  name="link_4" id="link_4"/>
                                            </li>
                                            <li>
                                                <select name="source_4" id="source_4"/>
													<option value="page">Landing Page</option>
													<option value="email">Email</option>
												</select>
                                            </li>												
                                        </ul>

                                    <?php }
                                    ?>

                                </div>
                                <div class="action">
                                    <ul>
                                        <li >
                                            <a class="button white" onclick="onAddLinkClick();" style="width:auto">Add More Links</a>
                                        </li>
                                        <li >
                                            <button class="button white" style="width: auto" name="submit" value="submit" >Next &#X00bb;</button>
                                        </li>
                                        <li style="float: right;padding-right: 10px;">
                                            <button class="button white" id="back" name="back" type="submit" value="back">Back</button>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <script type="text/javascript">

            function onAddLinkClick(){
                var top_level_div = document.getElementById('link-div');
                var count = top_level_div.getElementsByTagName('ul').length;
                var temp = document.createElement("ul");
                var link_name = 'link_'+(count + 1);
				var source_name = 'source_'+(count + 1);
                var ul = '<li><label>External Link '+(count + 1)+':<label></li> <li style="padding-top: 5px;"><input type="text" name='+link_name+' /></li><li><select name="'+source_name+'" /><option value="page">Landing Page</option><option value="email">Email</option><option value="news">News</option></select></li>';
                temp.innerHTML = ul;
                top_level_div.appendChild(temp);
            }
        </script>

    </body>
</html>