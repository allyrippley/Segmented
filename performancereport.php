<?php
include ("database_params.php");
$db_handler = mysql_connect($hostname, $username, $password);
$message = '';
if (mysql_errno()) {
    $message = "Connection failed :" . mysql_error();
}

mysql_select_db($databasename, $db_handler);

//$campaignId = explode("?", $_SERVER['REQUEST_URI']);
$campaignId = $_GET['id'];

$toecho = '';

$campaign_table_query = "select * from campaigns where id =" . $campaignId;
$campaign_table_result = mysql_query($campaign_table_query);
$campaignData = mysql_fetch_row($campaign_table_result);

$customer_table_query = "select count(*) from campaign_assignments where campaign_id =" . $campaignId;
$customers_table_result = mysql_query($customer_table_query);
$customercount = null;
if (!$customers_table_result) {
    $message = "Database error : " . mysql_error();
} else {
    $customercount = mysql_fetch_row($customers_table_result);
    if ($customercount[0] == 0) {
        $customercount[0] = 1;
    }
}

$reports_table_query = "select count(*) from openedemailsreport where campaign_id = '" . $campaignId . "'";
$reports_table_result = mysql_query($reports_table_query);
$openEmailCount = null;
if (!$reports_table_result) {
    $message = "Database error : " . mysql_error();
} else {
    $openEmailCount = mysql_fetch_row($reports_table_result);
}


$bucket = array(1 => 'A01', 2 => 'A02', 3 => 'A03', 4 => 'A04');
$matrixArray = array('A01' => array('segment' => 'customer', 'product' => 'HSI'),
    'A02' => array('segment' => 'customer', 'product' => 'FiOS'),
    'A03' => array('segment' => 'prospect', 'product' => 'HSI'),
    'A04' => array('segment' => 'prospect', 'product' => 'FiOS')
);
$emailOpenReports = array();
$matrixArrayKeys = array_keys($matrixArray);
foreach ($matrixArrayKeys as $key) {
    $emailOpenReports[$key]['sent'] = 0;
    $emailOpenReports[$key]['open'] = 0;
    $emailOpenReports[$key]['openrate'] = 0;
}
    $emailOpenReport = array();
    // email sent query
    //campaign_assignments
	//$query = "select count(*) from customers where segment='" . $matrixArray[$key]['segment'] . "' AND product='" . $matrixArray[$key]['product'] . "'";
	//$query = "select count(*) from campaign_assignments where matrix='$key' AND campaign_id='" . $campaignId . "'";
    $query = "select matrix, count(*) from campaign_assignments where campaign_id='" . $campaignId . "' group by `matrix`";
    $result = mysql_query($query);

    while ($row = mysql_fetch_array($result)) {
        if (array_key_exists($row['matrix'], $matrixArray)) {
            
            $emailOpenReports[$row['matrix']]['sent'] = $row['count(*)'];
        }
    }
    //$emailOpenReport['sent'] = $sent;
    
    //}

    // email open query
    //$query = "select count(*) from openedemailsreport where campaign_id='" . $campaignId . "' AND matrix='$key'";
    $query = "select matrix, count(*) from openedemailsreport where campaign_id='" . $campaignId . "' group by `matrix`";
    $result = mysql_query($query);
    while ($row = mysql_fetch_array($result)) {
        if (array_key_exists($row['matrix'], $matrixArray)) {
            $emailOpenReports[$row['matrix']]['open'] = $row['count(*)'];
            $emailOpenReports[$row['matrix']]['openrate'] = round(($row['count(*)'] / $emailOpenReports[$row['matrix']]['sent']) * 100, 2);  //$row['count(*)']
        }
    }

$articleIds = array();
$query = "select id from articles where campaigns_id='" . $campaignId . "'";
$result = mysql_query($query);
while ($row = mysql_fetch_array($result)) {
    array_push($articleIds, $row['id']);
}

$allArticleReports = array();
$query = "SELECT articles.id, articles.title, openedarticlesreport.matrix, Count(openedarticlesreport.uid) AS CountOfuid " .
    "FROM articles INNER JOIN openedarticlesreport ON articles.id = openedarticlesreport.article_id " .
    "GROUP BY articles.campaigns_id, articles.id, articles.title, openedarticlesreport.matrix " .
    "HAVING (((articles.campaigns_id)=" . $campaignId . "));";

$result = mysql_query($query);
$totalArticleClicks = 0;
$totalEmailClicks = 0;
$curArticle = -1;
while ($row = mysql_fetch_array($result)) {
    if ($curArticle == -1) {
        $curArticle = $row['id'];
        //echo $curArticle . " : ";
    }
    if ($curArticle !== $row['id']) {
        $allArticleReports[$curArticle] = $articleOpenReports;
        unset($articleOpenReports);
        $curArticle = $row['id'];
        //echo "<br />". $curArticle . " : ";
    }
    $articleOpenReport['sent'] = $emailOpenReports[$row['matrix']]['sent'];
    $articleOpenReport['open'] = $row['CountOfuid'];
    $articleOpenReport['openrate'] = round(($row['CountOfuid'] / $emailOpenReports[$row['matrix']]['sent']) * 100, 2);
    $articleOpenReports[$row['matrix']] = $articleOpenReport;
    $totalArticleClicks += $row['CountOfuid'];
    $totalEmailClicks += $row['CountOfuid'];
    //echo $row['matrix'] . ", ";
}
$allArticleReports[$curArticle] = $articleOpenReports;

// external links report
$query = "SELECT links.source, links.id, links.destination_url, openedlinksreport.matrix, Count(openedlinksreport.uid) AS CountOfuid " .
    "FROM links INNER JOIN openedlinksreport ON links.id = openedlinksreport.link_id " .
    "GROUP BY links.campaigns_id, links.source, links.id, openedlinksreport.matrix " .
    "HAVING (((links.campaigns_id)=" . $campaignId . ")) ORDER BY links.source;";
$result = mysql_query($query);
//$query = "select id, destination_url from links where campaigns_id = '" . $campaignId . "'";
//$result = mysql_query($query);
//$pageLinksReports = array();
//$emailLinksReports = array();
$externalLinkClickTotalCount = 0;
$emailClicks = 0;
$pageClicks = 0;
$curLink = -1;
$totalClicks = 0;
while ($row = mysql_fetch_array($result)) {
    if ($curLink == -1) {
        $curLink = $row['id'];
        //echo $curArticle . " : ";
    }
    if ($curLink !== $row['id']) {
//        $allLinkReports[$curLink] = $linkReports;
//        unset($alinkReports);
        $curLink = $row['id'];
        //echo "<br />". $curArticle . " : ";
    }
//    $linkReport['url'] = $row['destination_url'];
//    $linkReport['source'] = $row['source'];
//    $linkReport['clicks'] = $row['CountOfuid'];
//    $linkReports[$row['matrix']] = $linkReport;

    if ($row['source']=='email') {
        //echo $row['destination_url'] . "<br />";
        $emailLinksReports[$row['destination_url']][$row['matrix']] = $row['CountOfuid'];
        $emailClicks = $emailClicks + $row['CountOfuid'];
    }
    elseif ($row['source']=='page') {
        //echo $row['destination_url'] . "<br />";
        $pageLinksReports[$row['destination_url']][$row['matrix']] = $row['CountOfuid'];
        $pageClicks = $pageClicks + $row['CountOfuid'];
    }
    $totalClicks += $row['CountOfuid'];
 
//        $externalLinksReport[$row['id']][$key]['openrate'] = $openRate;
//    }
}
$totalEmailClicks += $emailClicks;

$query = "SELECT articles.campaigns_id, openedarticlesreport.uid, Count(openedarticlesreport.article_id) AS CountOfarticle_id " .
	"FROM openedarticlesreport INNER JOIN articles ON openedarticlesreport.article_id = articles.id " .
	"GROUP BY articles.campaigns_id, openedarticlesreport.uid " .
	"HAVING (((articles.campaigns_id)=" . $campaignId . "));";
$result = mysql_query($query);
$uniqueArticleClicks = mysql_num_rows($result);

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
                    <div class="menu"><a href="index.php" id="dashboard">View Dashboard</a></div>
                    <div class="error-box">
                        <label style="color: red;text-align: center" id="show_error">
                            <?php
                            if ($message != '') {
                                echo $message;
                            }
                            ?>
                        </label>
                        <form action="index.php" method="post">
                            <div class="content-table">
                                <div class="content-header"><h2>Performance Report - <?php echo $campaignData[1] ?></h2></div>
								<?php echo $toecho; ?>
                                <div class="content-table-b">
                                    <div class="report-date-div">
                                        <ul style="width:100%;">
                                            <li style="width:49%;">
                                                <b>Start Date :  </b><?php echo date("d M Y", strtotime($campaignData[2])); ?>
                                            </li>
                                            <li style="width:50%; text-align:right;">
                                                <b>End Date :  </b><?php echo date("d M Y", strtotime($campaignData[3])); ?>
                                            </li>
                                        </ul>
                                    </div>
                                    <table id="hor-minimalist-b" summary="Campaigns List">
                                        <thead>
                                            <tr>
                                                <th>Matrix</th>
                                                <th>Segment</th>
                                                <th>HSI/FiOS</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>A01</td>
                                                <td>Customer</td>
                                                <td>HSI</td>
                                            </tr>
                                            <tr>
                                                <td>A02</td>
                                                <td>Customer</td>
                                                <td>FiOS</td>
                                            </tr>
                                            <tr>
                                                <td>A03</td>
                                                <td>Prospect</td>
                                                <td>HSI</td>
                                            </tr>
                                            <tr>
                                                <td>A04</td>
                                                <td>Prospect</td>
                                                <td>FiOS</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <div class="report-date-div">
                                        <ul>
                                            <li><label><b>Emails Sent : </b></label></li>
                                            <li><?php echo $customercount[0] ?></li>
                                        </ul>
                                        <ul>
                                            <li><label><b>Opens : </b></label></li>
                                            <li> <?php echo $openEmailCount[0] ?> </li>
                                            <li> <?php
                            $percentage = ($openEmailCount[0] / $customercount[0]);
                            $percentageInAgreegate = $percentage * 100;
                            echo round($percentageInAgreegate,2) . "%";
                            ?> </li>
                                        </ul>
                                        <ul>
                                            <li><label><b>Click-Thrus : </b></label></li>
                                            <li><?php echo $totalEmailClicks; ?></li>
                                            <li> <?php echo round(($totalEmailClicks / $openEmailCount[0])*100,2) . "%"; ?> </li>
                                        </ul>
                                    </div>
                                    <div class="table-header"><h2><br />Email Open Report</h2></div>
                                    <table id="hor-minimalist-b" summary="Campaigns List">
                                        <thead>
                                            <tr>
                                                <th>Matrix</th>
                                                <th>Sent</th>
                                                <th>Opens</th>
                                                <th>Open Rate</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $totalOpens = 0;
                                            $totalSent = 0;
                                            foreach ($matrixArrayKeys as $key) {
                                                echo "<tr>";
                                                echo "<td>$key</td>";
                                                echo "<td>" . $emailOpenReports[$key]['sent'] . "</td>";
                                                echo "<td>" . $emailOpenReports[$key]['open'] . "</td>";
                                                echo "<td>" . $emailOpenReports[$key]['openrate'] . "%</td>";
                                                echo "</tr>";
                                                $totalSent += $emailOpenReports[$key]['sent'];
                                                $totalOpens += $emailOpenReports[$key]['open']; 
                                            }
                                            echo "<tr style='border-top: 2px solid #000'><td>Total</td><td>$totalSent</td><td>$totalOpens</td><td>";
                                            echo round(($totalOpens / $totalSent) * 100, 2);
                                            echo "%</td></tr>";
                                            ?>
                                        </tbody>
                                    </table>
                                    <div class="table-header" style="margin-top: 10px;"><h2><br />Article Click Report</h2></div>
                                    <?php
                                    $index = 1;
                                    foreach ($allArticleReports as $articleReports) {
                                        ?>
                                        <div style="padding-bottom: 10px;">
                                            <table id="hor-minimalist-b" summary="Campaigns List">
                                                <thead>
                                                    <tr>
                                                        <th>Matrix</th>
                                                        <th>Sent</th>
                                                        <th>Article <?php echo $index; ?> Opens</th>
                                                        <th>Article <?php echo $index; ?> Open Rate</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    foreach ($articleReports as $key => $data) {
                                                        ?>
                                                        <tr>
                                                            <td><?php echo $key; ?></td>
                                                            <td><?php echo $data['sent']; ?></td>
                                                            <td><?php echo $data['open']; ?></td>
                                                            <td><?php echo $data['openrate'] . "%"; ?></td>
                                                        </tr>


                                                    <?php }
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                        <?php
                                        $index++;
                                    }
                                    ?>


                                    <div class="table-header" style="padding-top: 20px;"><h2><br />Email Click Report</h2></div>

                                    <?php
                                    if (empty($emailLinksReports)) {
                                        ?>
                                        <table id="hor-minimalist-b" summary="Campaigns List">
                                            <thead>
                                                <tr>
                                                    <th colspan="2">Link</th>
                                                    <th colspan="2">Clicks</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td colspan="4">No links present</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <?php
                                    } else {
                                        ?>
                                        <div style="padding-bottom: 10px;">
                                                <table id="hor-minimalist-b" summary="Campaigns List">
                                                    <thead>
                                                        <tr>
                                                            <th>Link</th>
                                                            <th>A01</th>
                                                            <th>A02</th>
                                                            <th>A03</th>
                                                            <th>A04</th>
                                                            <th>Total Clicks</th>
                                                       </tr>
                                        <?php
                                        
                                        $totalEmailClicks = 0;
                                        
                                        foreach (array_keys($emailLinksReports) as $url) {
                                            //echo "URL: " . $url . "<br />";
                                            $totalClicks = 0;
                                            echo "<tr><td>" . $url . "</td>";
                                            foreach ($bucket as $matrixID) {
                                                echo "<td>" . $emailLinksReports[$url][$matrixID] . "</td>";
                                                $totalEmailClicks += $emailLinksReports[$url][$matrixID];
                                                $totalClicks += $emailLinksReports[$url][$matrixID];
                                            }
                                            echo "<td>" . $totalClicks . "</tr>";
                                        }
                                        $ctr = round((($totalEmailClicks + $totalArticleClicks)/$openEmailCount[0])*100,2);
                                        echo "<tr><td colspan='5' align='right'><strong>Outbound Clicks</strong></td><td>" . $totalEmailClicks . "</td></tr>";
                                        //echo "<tr><td colspan='5' align='right'><strong>Article Clicks</strong></td><td>" . $totalArticleClicks . "</td></tr>";
										echo "<tr><td colspan='5' align='right'><strong>Article Clicks</strong></td><td>" . $uniqueArticleClicks . "</td></tr>";
                                        echo "<tr><td colspan='5' align='right'><strong>CTR</strong></td><td>" . $ctr . "%</td></tr>";
                                        echo "</table></div>";
                                        
                                        
                                    }
                                    ?>
                                                       
                                    <div class="table-header" style="padding-top: 20px;"><h2><br />Page Click Report</h2></div>

                                    <?php
                                    if (empty($pageLinksReports) AND empty($emailLinksReports)) {
                                        ?>
                                        <table id="hor-minimalist-b" summary="Campaigns List">
                                            <thead>
                                                <tr>
                                                    <th colspan="2">Link</th>
                                                    <th colspan="2">Clicks</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td colspan="4">No links present</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <?php
                                    } else {
                                        ?>
                                        <div style="padding-bottom: 10px;">
                                                <table id="hor-minimalist-b" summary="Campaigns List">
                                                    <thead>
                                                        <tr>
                                                            <th>Link</th>
                                                            <th>A01</th>
                                                            <th>A02</th>
                                                            <th>A03</th>
                                                            <th>A04</th>
                                                            <th>Total Clicks</th>
                                                       </tr>
                                        <?php
                                        $totalPageClicks = 0;
                                        foreach (array_keys($pageLinksReports) as $url) {
                                            //echo "URL: " . $url . "<br />";
                                            $totalClicks = 0;
                                            echo "<tr><td>" . $url . "</td>";
                                            foreach ($bucket as $matrixID) {
                                                echo "<td>" . $pageLinksReports[$url][$matrixID] . "</td>";
                                                $totalClicks += $pageLinksReports[$url][$matrixID]; 
                                            }
                                            echo "<td>" . $totalClicks . "</tr>";
                                            $totalPageClicks += $totalClicks; 
                                        }
                                        echo "<tr><td colspan='5' align='right'><strong>Total Clicks</strong></td><td>" . $totalPageClicks . "</td></tr>";
                                        echo "</table></div>";
                                        
                                    }
                                    ?>
                                    <div class="action">
                                        <button id="back" name="back" class="button white" type="submit" value="submit" style="float: right">Back</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            

    </body>
</html>