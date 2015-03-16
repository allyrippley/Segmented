<?php
include("database_params.php");
$db_handler = mysql_connect($hostname, $username, $password);
$message = '';
if (mysql_errno()) {
    $message = "Connection failed :" . mysql_error();
}

mysql_select_db($databasename, $db_handler);
session_start();
$campaignId = $_SESSION["campaign_id"];
$links_table_query = "select * from links where campaigns_id =" . $campaignId . " AND `source` LIKE  'page'";
$links_table_result = mysql_query($links_table_query);
$links_data = array();
$i = 0;
if ($links_table_result) {
    while ($row = mysql_fetch_array($links_table_result)) {
		if (is_null($row{'menu_position'})) {
			$links_data[$i]['destination_url'] = $row{'destination_url'};
			$links_data[$i]['generated_url'] = $row{'generated_url'};
			$i++;
		}
    }
} else {
    $message = "Database error : " . mysql_error();
}

$articleCount = 0;
if (isset($_SESSION['edit_flag'])) {
    $query = "select count(*) from articles where campaigns_id='" . $campaignId . "'";
    $result = mysql_query($query);
    $row = mysql_fetch_row($result);
    $articleCount = $row[0];
}

$articlePageId = explode('=', $_SERVER['REQUEST_URI']);
$pageId = '';
$pageTitle = '';
$pageSummary = '';
$pageData = '';
if (isset($_SESSION["article_page_id"])) {
    $pageId = $_SESSION["article_page_id"];
    unset($_SESSION["article_page_id"]);
}

if (isset($_SESSION["article_page_data"])) {
    $pageData = $_SESSION["article_page_data"];
    $pageData = mysql_real_escape_string(stripslashes($pageData));
    unset($_SESSION["article_page_data"]);
}

if (isset($_SESSION["article_title"])) {
    $pageTitle = $_SESSION["article_title"];
    unset($_SESSION["article_title"]);
}

if (isset($_SESSION["article_summary"])) {
    $pageSummary = $_SESSION["article_summary"];
    unset($_SESSION["article_summary"]);
}

if (isset($_SESSION["article_footer"])) {
    $pageFooter = $_SESSION["article_footer"];
    unset($_SESSION["article_footer"]);
}


if (isset($_SESSION["edit_flag"]) && !isset($_SESSION["article_flag"])) {
    $campaignid = $_SESSION["campaign_id"];
    $query = "select * from articles where campaigns_id =" . $campaignid;
    $result = mysql_query($query);
    if ($result) {
        $j = 0;
        while ($row = mysql_fetch_array($result)) {
            $_SESSION["article_" . ($j + 1)] = $row{'article_data'};
            $_SESSION["article_title_" . ($j + 1)] = $row{'title'};
			$_SESSION["article_summary_" . ($j + 1)] = $row{'summary'};
			$_SESSION["article_footer_" . ($j + 1)] = $row{'footer'};
			$_SESSION["article_id_" . ($j + 1)] = $row{'id'};
            $j++;
        }
    } else {
        $message = "Database error : " . mysql_error();
    }
    $_SESSION["article_flag"] = 1;
}

if (isset($_SESSION["article_" . $articlePageId[1]])) {
    $pageData = $_SESSION["article_" . $articlePageId[1]];
    $pageData = mysql_real_escape_string(stripslashes($pageData));
}

if (isset($_SESSION["article_footer_" . $articlePageId[1]])) {
    $pageFooter = $_SESSION["article_footer_" . $articlePageId[1]];
    $pageFooter = mysql_real_escape_string(stripslashes($pageFooter));
}

if (isset($_SESSION["article_title_" . $articlePageId[1]])) {
    $pageTitle = $_SESSION["article_title_" . $articlePageId[1]];
    $pageTitle = mysql_real_escape_string(stripslashes($pageTitle));
	//unset($_SESSION["article_title_" . $articlePageId[1]]);
}

if (isset($_SESSION["article_summary_" . $articlePageId[1]])) {
    $pageSummary = $_SESSION["article_summary_" . $articlePageId[1]];
    $pageSummary = mysql_real_escape_string(stripslashes($pageSummary));
	//unset($_SESSION["article_summary_" . $articlePageId[1]]);
}

if (isset($_SESSION["article_database_message"])) {
    $message = $_SESSION["article_database_message"];
    unset($_SESSION["article_database_message"]);
}
?>
<!doctype html>
<html>
    <head>
        <title>Campaign Manager</title>
        <link rel="stylesheet" type="text/css" href="css/reset-fonts-grids.css"/>
        <link rel="stylesheet" type="text/css" href="css/style.css">
        <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
        <script src="http://code.jquery.com/jquery-1.8.3.js"></script>
        <script src="http://code.jquery.com/ui/1.10.0/jquery-ui.js"></script>
        <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.0/themes/base/jquery-ui.css" />
        <script type="text/javascript" src="scripts/jHtmlArea-0.7.5.js"></script>
        <link rel="Stylesheet" type="text/css" href="style/jHtmlArea.css" />
        <style type="text/css">
            /* body { background: #ccc;} */
            div.jHtmlArea .ToolBar ul li a.custom_disk_button
            {
                background: url(images/disk.png) no-repeat;
                background-position: 0 0;
            }
            div.jHtmlArea iframe { background-color: #fff;}
            div.jHtmlArea { border: solid 1px #ccc; }
        </style>
        <script type="text/javascript">
            $(document).ready(function () {
                if(("<?php echo $pageData != '' ?>")){
                    $("textarea").val("<?php echo $pageData ?>");
                }
                $("textarea").htmlarea();
            });

        </script>

    </head>
    <body class="yui-skin-sam">
        <div id="doc3" class="yui-t7">
            <div id="hd" role="banner">
                <div class="header-text"><h1>Campaign Management</h1></div>
            </div>
            <div id="bd" role="main">
                <div class="yui-g">
                    <div class="menu"><a href="index.php" id="dashboard">View Dashboard</a></div>
                    <div class="error-box">
                        <label style="color: red;text-align: center" id="show_error">
                            <?php
                            if ($message != '') {
                                echo $message;
                            }
                            ?>
                        </label>
                    </div>
                    <form id="article" name="article" action="insert_article.php" method="post">
                        <div class="content-table">
                            <div class="content-header">
                                <?php if (isset($_SESSION['edit_flag'])) { ?>
                                    <h2>Edit Campaign - <?php echo $_SESSION["campaign_name"]; ?></h2>
                                <?php } else { ?>
                                    <h2>Create New Campaign</h2>
                                <?php } ?>
                            </div>
                            <div>
                                <?php if (isset($_SESSION['edit_flag'])) { ?>
                                    Campaign Name &#x00bb; Edit External Links &#x00bb; Edit Content Block &#x00bb;<b>Edit Articles</b>
                                <?php } else { ?>
                                    Campaign Name &#x00bb; Enter External Links &#x00bb; Content Block &#x00bb;<b>Add Articles</b>
                                <?php } ?>
                            </div>
                            <div class="content-table-b">
                                <div class="table-header"><h2 style="border-bottom: 1px solid #aaa;padding: 10px;">Generated tracking page urls</h2></div>
                                <div class="article-table-div">
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
                                                        Generated tracking page urls not present.
                                                    </td>
                                                </tr>
                                                <?php
                                            } else {
                                                foreach ($links_data as $data) {
                                                    ?>
                                                    <tr>
                                                        <td>
                                                            <?php echo $data['destination_url']; ?>
                                                        </td>
                                                        <td>
                                                            <?php echo $data['generated_url']; ?>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                                <h2 style="border-bottom: 1px solid #aaa;padding: 10px;">Article <?php echo $articlePageId[1] ?></h2><br/>
								<div><ul>
									<li><div style="font-size:14px;">Article Title</div></li>
									<li><input type="text" name="article_title" id="article_title" value="<?php echo $pageTitle; ?>"><br /><br /></li>
								</ul></div>
								<div><ul>
									<li><div style="font-size:14px;">Article Summary</div></li>
									<li><input type="text" name="article_summary" id="article_summary" value="<?php echo $pageSummary; ?>"><br /><br /></li>
								</ul></div>
								
                                <div style="font-size:14px;">
                                    Article Content
                                </div>								
                                <div>
                                    <input type="text" name="articlePageId" value="<?php echo $articlePageId[1] ?>" hidden="hidden"/>
                                    <textarea id="articlePara" name="articlePara" rows="20" style="width:664px;height:320px;"></textarea><br />
                                </div>
								
								<div style="visibility:hidden;"><ul>
									<li><div style="font-size:14px;">Article Footer</div></li>
                                    <li><input type="text" id="articleFooter" name="articleFooter" rows="6" value="<?php echo stripslashes($pageFooter); ?>"><br /><br /></li>
								</ul></div>										
						
								
                                <div class="action">
                                    <ul>
                                        <li>
                                            <?php
                                            if (isset($_SESSION['edit_flag'])) {
                                                if ($_GET['id'] < $articleCount) {
                                                    ?>
                                                    <button id="addArticle"  name="addArticle" class="button white" value="addArticle">Edit Articles &#x00bb;</button>
                                                <?php } else { ?>
                                                    <button id="addArticle"  name="addArticle" class="button white" value="addArticle">Add More Articles &#x00bb;</button>
                                                <?php } ?>
                                            <?php } else { ?>
                                                <button id="addArticle"  name="addArticle" class="button white" value="addArticle">Add More Articles &#x00bb;</button>
                                            <?php } ?>
                                        </li>
                                        <li>
                                            <button id="saveCampaign" name="saveCampaign" class="button white" value="saveCampaign">Save Campaign</button>
                                        </li>

                                        <li style="float: right;padding-right: 5px;">
                                            <button id="back" name="back" type="submit" class="button white" value="back">Back</button>
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