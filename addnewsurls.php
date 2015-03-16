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
    $query = "select * from links where `source` LIKE 'news' AND campaigns_id = " . $campaignid;
    $result = mysql_query($query);
    if ($result) {
        $j = 0;
        while ($row = mysql_fetch_array($result)) {
			if (is_null($row{'menu_position'})) {
				$linksArr[$j]['id'] = $row{'id'};
				$linksArr[$j]['url'] = $row{'destination_url'};
				$linksArr[$j]['source'] = $row{'source'};
                                $linksArr[$j]['title'] = $row{'title'};
                                $linksArr[$j]['description'] = $row{'description'};                                
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


if (isset($_SESSION["newsurls_database_message"])) {
    $message = $_SESSION["newsurls_database_message"];
    unset($_SESSION["newsurls_database_message"]);
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
                    <form action="insert_newsurls.php" id="newsurls" name="newsurls" method="post">
                        <div class="content-table">
                            <div class="content-header">
                                <?php if (isset($_SESSION['edit_flag'])) { ?>
                                    <h2>Edit Campaign - <?php echo $_SESSION["campaign_name"]; ?></h2>
                                <?php } else { ?>
                                    <h2>Create New Campaign</h2>
                                <?php } ?>
                            </div>
                            <div>
                                <?php addBreadcrumbs(3); ?>
                            </div>
                            <div class="content-table-b">
                                <div id="link-div">
                                    <?php
                                    if (!empty($linksArr)) {
                                        for ($i = 1; $i <= sizeof($linksArr); $i++) {
                                            ?>
                                            <ul>
                                                <li>
                                                    <label><strong>News Link <?php echo $i ?> :</strong></label>
                                                </li>
                                                <li>
                                                    <input type="text" name="link_<?php echo $i; ?>" id="link_<?php echo $i; ?>" value="<?php echo htmlspecialchars_decode($linksArr[($i - 1)]['url']); ?>"/>
                                                    <input type="hidden" name="source_<?php echo $i; ?>" id="source_<?php echo $i; ?>" value="<?php echo htmlspecialchars_decode($linksArr[($i - 1)]['source']); ?>" />
                                                </li>
                                                <li>
                                                    <label>Title <?php echo $i ?> :</label>
                                                    <input type="text" name="title_<?php echo $i; ?>" id="title_<?php echo $i; ?>" value="<?php echo htmlspecialchars_decode($linksArr[($i - 1)]['title']); ?>"/>
                                                </li>
                                                <li>
                                                    <label>Description <?php echo $i ?> :</label>
                                                    <input type="text" name="description_<?php echo $i; ?>" id="description_<?php echo $i; ?>" value="<?php echo htmlspecialchars_decode($linksArr[($i - 1)]['description']); ?>"/>
                                                </li>
                                                <li>
                                                    <br />
                                                </li>
                                            </ul>
                                            <?php
                                        }
                                    } else {
                                        ?>
                                        <ul>
                                            <li>
                                                <label><strong>News Link 1 :</strong></label>
                                            </li>
                                            <li>
                                                <input type="text" name="link_1" id="link_1"/>
                                                <input type="hidden" name="source_1" id="source_1" value="news" />
                                            </li>	
                                            <li>
                                                <label>Title 1 :</label>
                                                <input type="text" name="title_1" id="title_1" value="" />
                                            </li>
                                            <li>
                                                <label>Description <?php echo $i ?> :</label>
                                                <input type="text" name="description_1" id="description_1" value=""/>
                                            </li> 
                                            <li><br /></li>
                                        </ul>
                                        <ul>
                                            <li>
                                                <label><strong>News Link 2 :</strong></label>
                                            </li>
                                            <li>
                                                <input type="text" name="link_2" id="link_2" />
                                                <input type="hidden" name="source_2" id="source_2" value="news" />
                                            </li>	
                                            <li>
                                                <label>Title 2 :</label>
                                                <input type="text" name="title_2" id="title_2" value="" />
                                            </li>
                                            <li>
                                                <label>Description 2 :</label>
                                                <input type="text" name="description_2" id="description_2" value=""/>
                                            </li> 
                                            <li><br /></li>          	
                                        </ul>
                                        <ul>
                                            <li>
                                                <label><strong>News Link 3 :</strong></label>
                                            </li>
                                            <li>
                                                <input type="text" name="link_3" id="link_3" />
                                                <input type="hidden" name="source_3" id="source_3" value="news" />
                                            </li>	
                                            <li>
                                                <label>Title 3 :</label>
                                                <input type="text" name="title_3" id="title_3" value="" />
                                            </li>
                                            <li>
                                                <label>Description 3 :</label>
                                                <input type="text" name="description_3" id="description_3" value=""/>
                                            </li> 
                                            <li><br /></li>
                                        </ul>
                                        <ul>
                                            <li>
                                                <label><strong>News Link 4 :</strong></label>
                                            </li>
                                            <li>
                                                <input type="text"  name="link_4" id="link_4"/>
                                                <input type="hidden" name="source_4" id="source_4" value="news" />
                                            </li>	
                                            <li>
                                                <label>Title 4 :</label>
                                                <input type="text" name="title_4" id="title_4" value="" />
                                            </li>
                                            <li>
                                                <label>Description 4 :</label>
                                                <input type="text" name="description_4" id="description_4" value=""/>
                                            </li> 
                                            <li><br /></li>
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
                var title_name = 'title_'+(count + 1);
                var description_name = 'description_'+(count + 1);
                var ul = '<li><label>News Link '+(count + 1)+':<label></li> <li style="padding-top: 5px;"><input type="text" name='+link_name+' /><input type="hidden" name="source_' + (count+1) +'" id="source_' + (count+1) + '" value="news"></li><label>Title '+(count + 1)+':<label></li> <li style="padding-top: 5px;"><input type="text" name='+title_name+' /></li><label>Description '+(count + 1)+':<label></li> <li style="padding-top: 5px;"><input type="text" name='+description_name+' /></li><li><br /></li>';
                temp.innerHTML = ul;
                top_level_div.appendChild(temp);
            }
        </script>

    </body>
</html>