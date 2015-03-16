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
$block_1_option_1 = '';
$block_1_option_2 = '';
$block_1_option_3 = '';
$block_1_option_4 = '';
$block_2_option_1 = '';
$block_2_option_2 = '';
$block_2_option_3 = '';
$block_2_option_4 = '';
$block_3_option_1 = '';
$block_3_option_2 = '';
$block_3_option_3 = '';
$block_3_option_4 = '';
$block_4_option_1 = '';
$block_4_option_2 = '';
$block_4_option_3 = '';
$block_4_option_4 = '';

// Edit content block 
if (isset($_SESSION["edit_flag"])) {
    $campaignid = $_SESSION["campaign_id"];
    $query = "select * from contents where campaigns_id =" . $campaignid;
    $result = mysql_query($query);
    if ($result) {
        $contentData = mysql_fetch_row($result);
        $_SESSION["content_id"] = $contentData[0];
        $block_1_option_1 = $contentData[1];
        $block_1_option_2 = $contentData[2];
        $block_1_option_3 = $contentData[3];
        $block_1_option_4 = $contentData[4];
        $block_2_option_1 = $contentData[5];
        $block_2_option_2 = $contentData[6];
        $block_2_option_3 = $contentData[7];
        $block_2_option_4 = $contentData[8];
        $block_3_option_1 = $contentData[9];
        $block_3_option_2 = $contentData[10];
        $block_3_option_3 = $contentData[11];
        $block_3_option_4 = $contentData[12];
        $block_4_option_1 = $contentData[13];
        $block_4_option_2 = $contentData[14];
        $block_4_option_3 = $contentData[15];
        $block_4_option_4 = $contentData[16];

        $_SESSION["block_1_option_1"] = mysql_real_escape_string($block_1_option_1);
        $_SESSION["block_1_option_2"] = mysql_real_escape_string($block_1_option_2);
        $_SESSION["block_1_option_3"] = mysql_real_escape_string($block_1_option_3);
        $_SESSION["block_1_option_4"] = mysql_real_escape_string($block_1_option_4);
        $_SESSION["block_2_option_1"] = mysql_real_escape_string($block_2_option_1);
        $_SESSION["block_2_option_2"] = mysql_real_escape_string($block_2_option_2);
        $_SESSION["block_2_option_3"] = mysql_real_escape_string($block_2_option_3);
        $_SESSION["block_2_option_4"] = mysql_real_escape_string($block_2_option_4);
        $_SESSION["block_3_option_1"] = mysql_real_escape_string($block_3_option_1);
        $_SESSION["block_3_option_2"] = mysql_real_escape_string($block_3_option_2);
        $_SESSION["block_3_option_3"] = mysql_real_escape_string($block_3_option_3);
        $_SESSION["block_3_option_4"] = mysql_real_escape_string($block_3_option_4);
        $_SESSION["block_4_option_1"] = mysql_real_escape_string($block_4_option_1);
        $_SESSION["block_4_option_2"] = mysql_real_escape_string($block_4_option_2);
        $_SESSION["block_4_option_3"] = mysql_real_escape_string($block_4_option_3);
        $_SESSION["block_4_option_4"] = mysql_real_escape_string($block_4_option_4);
    } else {
        $message = "Database error : " . mysql_error();
    }
}

if (isset($_SESSION["block_1_option_1"])) {
    $block_1_option_1 = $_SESSION["block_1_option_1"];
}

if (isset($_SESSION["block_1_option_2"])) {
    $block_1_option_2 = $_SESSION["block_1_option_2"];
}

if (isset($_SESSION["block_1_option_3"])) {
    $block_1_option_3 = $_SESSION["block_1_option_3"];
}

if (isset($_SESSION["block_1_option_4"])) {
    $block_1_option_4 = $_SESSION["block_1_option_4"];
}

if (isset($_SESSION["block_2_option_1"])) {
    $block_2_option_1 = $_SESSION["block_2_option_1"];
}

if (isset($_SESSION["block_2_option_2"])) {
    $block_2_option_2 = $_SESSION["block_2_option_2"];
}

if (isset($_SESSION["block_2_option_3"])) {
    $block_2_option_3 = $_SESSION["block_2_option_3"];
}

if (isset($_SESSION["block_2_option_4"])) {
    $block_2_option_4 = $_SESSION["block_2_option_4"];
}

if (isset($_SESSION["block_3_option_1"])) {
    $block_3_option_1 = $_SESSION["block_3_option_1"];
}

if (isset($_SESSION["block_3_option_2"])) {
    $block_3_option_2 = $_SESSION["block_3_option_2"];
}

if (isset($_SESSION["block_3_option_3"])) {
    $block_3_option_3 = $_SESSION["block_3_option_3"];
}

if (isset($_SESSION["block_3_option_4"])) {
    $block_3_option_4 = $_SESSION["block_3_option_4"];
}

if (isset($_SESSION["block_4_option_1"])) {
    $block_4_option_1 = $_SESSION["block_4_option_1"];
}

if (isset($_SESSION["block_4_option_2"])) {
    $block_4_option_2 = $_SESSION["block_4_option_2"];
}

if (isset($_SESSION["block_4_option_3"])) {
    $block_4_option_3 = $_SESSION["block_4_option_3"];
}

if (isset($_SESSION["block_4_option_4"])) {
    $block_4_option_4 = $_SESSION["block_4_option_4"];
}

if (isset($_SESSION["contentblock_database_message"])) {
    $message = $_SESSION["contentblock_database_message"];
    unset($_SESSION["contentblock_database_message"]);
}

unset($_SESSION["article_data"]);
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
                $("#group_1_option_1").click(function(e) {
                    var attrName = $(this).attr('id');
                    if(($(this).attr('id') != 'back')) {
                        if($(this).attr('id') != 'dashboard'){
                            $( "#dialog-modal-1" ).dialog({
                                modal: true,
                                width:700,
                                height:450
                            });
                            if(("<?php echo $block_1_option_1 ?>") != ''){
                                $("#block_1_option_1_text").val("<?php echo $block_1_option_1; ?>");
                            }
                            $("#block_1_option_1_text").htmlarea();

                        }
                    }

                });

                $("#group_1_option_2").click(function(e) {
                    var attrName = $(this).attr('id');
                    if(($(this).attr('id') != 'back')) {
                        if($(this).attr('id') != 'dashboard'){
                            $( "#dialog-modal-2" ).dialog({
                                modal: true,
                                width:700,
                                height:450
                            });
                            if(("<?php echo $block_1_option_2 ?>") != ''){
                                $("#block_1_option_2_text").val("<?php echo $block_1_option_2; ?>");
                            }
                            $("#block_1_option_2_text").htmlarea();

                        }
                    }

                });

                $("#group_1_option_3").click(function(e) {
                    var attrName = $(this).attr('id');
                    if(($(this).attr('id') != 'back')) {
                        if($(this).attr('id') != 'dashboard'){
                            $( "#dialog-modal-3" ).dialog({
                                modal: true,
                                width:700,
                                height:450
                            });
                            if(("<?php echo $block_1_option_3 ?>") != ''){
                                $("#block_1_option_3_text").val("<?php echo $block_1_option_3; ?>");
                            }
                            $("#block_1_option_3_text").htmlarea();

                        }
                    }

                });

                $("#group_1_option_4").click(function(e) {
                    var attrName = $(this).attr('id');
                    if(($(this).attr('id') != 'back')) {
                        if($(this).attr('id') != 'dashboard'){
                            $( "#dialog-modal-4" ).dialog({
                                modal: true,
                                width:700,
                                height:450
                            });
                            if(("<?php echo $block_1_option_4 ?>") != '') {
                                $("#block_1_option_4_text").val("<?php echo $block_1_option_4; ?>");
                            }
                            $("#block_1_option_4_text").htmlarea();

                        }
                    }

                });

                $("#group_2_option_1").click(function(e) {
                    var attrName = $(this).attr('id');
                    if(($(this).attr('id') != 'back')) {
                        if($(this).attr('id') != 'dashboard'){
                            $( "#dialog-modal-5" ).dialog({
                                modal: true,
                                width:700,
                                height:450
                            });
                            if(("<?php echo $block_2_option_1 ?>") != ''){
                                $("#block_2_option_1_text").val("<?php echo $block_2_option_1; ?>");
                            }
                            $("#block_2_option_1_text").htmlarea();

                        }
                    }

                });

                $("#group_2_option_2").click(function(e) {
                    var attrName = $(this).attr('id');
                    if(($(this).attr('id') != 'back')) {
                        if($(this).attr('id') != 'dashboard'){
                            $( "#dialog-modal-6" ).dialog({
                                modal: true,
                                width:700,
                                height:450
                            });
                            if(("<?php echo $block_2_option_2 ?>") != ''){
                                $("#block_2_option_2_text").val("<?php echo $block_2_option_2; ?>");
                            }
                            $("#block_2_option_2_text").htmlarea();

                        }
                    }

                });

                $("#group_2_option_3").click(function(e) {
                    var attrName = $(this).attr('id');
                    if(($(this).attr('id') != 'back')) {
                        if($(this).attr('id') != 'dashboard'){
                            $( "#dialog-modal-7" ).dialog({
                                modal: true,
                                width:700,
                                height:450
                            });
                            if(("<?php echo $block_2_option_3 ?>") != ''){
                                $("#block_2_option_3_text").val("<?php echo $block_2_option_3; ?>");
                            }
                            $("#block_2_option_3_text").htmlarea();

                        }
                    }

                });

                $("#group_2_option_4").click(function(e) {
                    var attrName = $(this).attr('id');
                    if(($(this).attr('id') != 'back')) {
                        if($(this).attr('id') != 'dashboard'){
                            $( "#dialog-modal-8" ).dialog({
                                modal: true,
                                width:700,
                                height:450
                            });
                            if(("<?php echo $block_2_option_4 ?>") != ''){
                                $("#block_2_option_4_text").val("<?php echo $block_2_option_4; ?>");
                            }
                            $("#block_2_option_4_text").htmlarea();

                        }
                    }

                });

                $("#group_3_option_1").click(function(e) {
                    var attrName = $(this).attr('id');
                    if(($(this).attr('id') != 'back')) {
                        if($(this).attr('id') != 'dashboard'){
                            $( "#dialog-modal-9" ).dialog({
                                modal: true,
                                width:700,
                                height:450
                            });
                            if(("<?php echo $block_3_option_1 ?>") != ''){
                                $("#block_3_option_1_text").val("<?php echo $block_3_option_1; ?>");
                            }
                            $("#block_3_option_1_text").htmlarea();

                        }
                    }

                });

                $("#group_3_option_2").click(function(e) {
                    var attrName = $(this).attr('id');
                    if(($(this).attr('id') != 'back')) {
                        if($(this).attr('id') != 'dashboard'){
                            $( "#dialog-modal-10" ).dialog({
                                modal: true,
                                width:700,
                                height:450
                            });
                            if(("<?php echo $block_3_option_2 ?>") != ''){
                                $("#block_3_option_2_text").val("<?php echo $block_3_option_2; ?>");
                            }
                            $("#block_3_option_2_text").htmlarea();

                        }
                    }

                });

                $("#group_3_option_3").click(function(e) {
                    var attrName = $(this).attr('id');
                    if(($(this).attr('id') != 'back')) {
                        if($(this).attr('id') != 'dashboard'){
                            $( "#dialog-modal-11" ).dialog({
                                modal: true,
                                width:700,
                                height:450
                            });
                            if(("<?php echo $block_3_option_3 ?>") != ''){
                                $("#block_3_option_3_text").val("<?php echo $block_3_option_3; ?>");
                            }
                            $("#block_3_option_3_text").htmlarea();

                        }
                    }

                });

                $("#group_3_option_4").click(function(e) {
                    var attrName = $(this).attr('id');
                    if(($(this).attr('id') != 'back')) {
                        if($(this).attr('id') != 'dashboard'){
                            $( "#dialog-modal-12" ).dialog({
                                modal: true,
                                width:700,
                                height:450
                            });
                            if(("<?php echo $block_3_option_4 ?>") != ''){
                                $("#block_3_option_4_text").val("<?php echo $block_3_option_4; ?>");
                            }
                            $("#block_3_option_4_text").htmlarea();

                        }
                    }

                });

                $("#group_4_option_1").click(function(e) {
                    var attrName = $(this).attr('id');
                    if(($(this).attr('id') != 'back')) {
                        if($(this).attr('id') != 'dashboard'){
                            $( "#dialog-modal-13" ).dialog({
                                modal: true,
                                width:700,
                                height:450
                            });
                            if(("<?php echo $block_4_option_1 ?>") != ''){
                                $("#block_4_option_1_text").val("<?php echo $block_4_option_1; ?>");
                            }
                            $("#block_4_option_1_text").htmlarea();

                        }
                    }

                });

                $("#group_4_option_2").click(function(e) {
                    var attrName = $(this).attr('id');
                    if(($(this).attr('id') != 'back')) {
                        if($(this).attr('id') != 'dashboard'){
                            $( "#dialog-modal-14" ).dialog({
                                modal: true,
                                width:700,
                                height:450
                            });
                            if(("<?php echo $block_4_option_2 ?>") != ''){
                                $("#block_4_option_2_text").val("<?php echo $block_4_option_2; ?>");
                            }
                            $("#block_4_option_2_text").htmlarea();

                        }
                    }

                });

                $("#group_4_option_3").click(function(e) {
                    var attrName = $(this).attr('id');
                    if(($(this).attr('id') != 'back')) {
                        if($(this).attr('id') != 'dashboard'){
                            $( "#dialog-modal-15" ).dialog({
                                modal: true,
                                width:700,
                                height:450
                            });
                            if(("<?php echo $block_4_option_3 ?>") != ''){
                                $("#block_4_option_3_text").val("<?php echo $block_4_option_3; ?>");
                            }
                            $("#block_4_option_3_text").htmlarea();

                        }
                    }

                });

                $("#group_4_option_4").click(function(e) {
                    var attrName = $(this).attr('id');
                    if(($(this).attr('id') != 'back')) {
                        if($(this).attr('id') != 'dashboard'){
                            $( "#dialog-modal-16" ).dialog({
                                modal: true,
                                width:700,
                                height:450
                            });
                            if(("<?php echo $block_4_option_4 ?>") != ''){
                                $("#block_4_option_4_text").val("<?php echo $block_4_option_4; ?>");
                            }
                            $("#block_4_option_4_text").htmlarea();

                        }
                    }

                });

            });
            
            function nextclick() {
                if(document.getElementById("block_1_option_1_text").value != "") {
                    document.getElementById("block_1_option_1").value = document.getElementById("block_1_option_1_text").value;
                }
                if(document.getElementById("block_1_option_2_text").value != "") {
                    document.getElementById("block_1_option_2").value = document.getElementById("block_1_option_2_text").value;
                }
                if(document.getElementById("block_1_option_3_text").value != "") {
                    document.getElementById("block_1_option_3").value = document.getElementById("block_1_option_3_text").value;
                }
                if(document.getElementById("block_1_option_4_text").value != "") {
                    document.getElementById("block_1_option_4").value = document.getElementById("block_1_option_4_text").value;
                }
                if(document.getElementById("block_2_option_1_text").value != "") {
                    document.getElementById("block_2_option_1").value = document.getElementById("block_2_option_1_text").value;
                }
                if(document.getElementById("block_2_option_2_text").value != "") {
                    document.getElementById("block_2_option_2").value = document.getElementById("block_2_option_2_text").value;
                }
                if(document.getElementById("block_2_option_3_text").value != "") {
                    document.getElementById("block_2_option_3").value = document.getElementById("block_2_option_3_text").value;
                }
                if(document.getElementById("block_2_option_4_text").value != "") {
                    document.getElementById("block_2_option_4").value = document.getElementById("block_2_option_4_text").value;
                }
                if(document.getElementById("block_3_option_1_text").value != "") {
                    document.getElementById("block_3_option_1").value = document.getElementById("block_3_option_1_text").value;
                }
                if(document.getElementById("block_3_option_2_text").value != "") {
                    document.getElementById("block_3_option_2").value = document.getElementById("block_3_option_2_text").value;
                }
                if(document.getElementById("block_3_option_3_text").value != "") {
                    document.getElementById("block_3_option_3").value = document.getElementById("block_3_option_3_text").value;
                }
                if(document.getElementById("block_3_option_4_text").value != "") {
                    document.getElementById("block_3_option_4").value = document.getElementById("block_3_option_4_text").value;
                }
                if(document.getElementById("block_4_option_1_text").value != "") {
                    document.getElementById("block_4_option_1").value = document.getElementById("block_4_option_1_text").value;
                }
                if(document.getElementById("block_4_option_2_text").value != "") {
                    document.getElementById("block_4_option_2").value = document.getElementById("block_4_option_2_text").value;
                }
                if(document.getElementById("block_4_option_3_text").value != "") {
                    document.getElementById("block_4_option_3").value = document.getElementById("block_4_option_3_text").value;
                }
                if(document.getElementById("block_4_option_4_text").value != "") {
                    document.getElementById("block_4_option_4").value = document.getElementById("block_4_option_4_text").value;
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
                    <form id="contentblock" name="contentblock" action="insert_content_data.php" method="post">
                        <div class="content-table">
                            <div class="content-header">
                                <?php if (isset($_SESSION['edit_flag'])) { ?>
                                    <h2>Edit Campaign - <?php echo $_SESSION["campaign_name"]; ?></h2>
                                <?php } else { ?>
                                    <h2>Create New Campaign</h2>
                                <?php } ?>
                            </div>
                            <div>
                                <?php addBreadcrumbs(4); ?>
                            </div>
                            <div class="content-table-b">

                                <div style="font-size:14px; padding-bottom: 5px;">
                                    Content Blocks
                                </div>
                                <table id="hor-minimalist-b">
                                    <thead>
                                        <tr>
                                            <th>&nbsp;</th>
                                            <th>Customer / HSI</th>
                                            <th>Customer / FiOS</th>
                                            <th>Prospect / HSI</th>
                                            <th>Prospect / FiOS</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td style="font-size: 14px;">Content Block Group 1</td>
                                            <td><a href="#" id="group_1_option_1">(Add / Edit HTML)</a></td>
                                            <td><a href="#" id="group_1_option_2">(Add / Edit HTML)</a></td>
                                            <td><a href="#" id="group_1_option_3">(Add / Edit HTML)</a></td>
                                            <td><a href="#" id="group_1_option_4">(Add / Edit HTML)</a></td>
                                        </tr>
                                        <tr>
                                            <td style="font-size: 14px;">Content Block Group 2</td>
                                            <td><a href="#" id="group_2_option_1">(Add / Edit HTML)</a></td>
                                            <td><a href="#" id="group_2_option_2">(Add / Edit HTML)</a></td>
                                            <td><a href="#" id="group_2_option_3">(Add / Edit HTML)</a></td>
                                            <td><a href="#" id="group_2_option_4">(Add / Edit HTML)</a></td>
                                        </tr>
                                        <tr>
                                            <td colspan="5"><br /><strong>Options below for Newsletter prior to Feb 2014</strong><br /><br /></td>
                                        </tr>
                                        <tr>
                                            <td style="font-size: 14px;">Content Block Group 3</td>
                                            <td><a href="#" id="group_3_option_1">(Add / Edit HTML)</a></td>
                                            <td><a href="#" id="group_3_option_2">(Add / Edit HTML)</a></td>
                                            <td><a href="#" id="group_3_option_3">(Add / Edit HTML)</a></td>
                                            <td><a href="#" id="group_3_option_4">(Add / Edit HTML)</a></td>
                                        </tr>
                                        <tr>
                                            <td style="font-size: 14px;">Content Block Group 4</td>
                                            <td><a href="#" id="group_4_option_1">(Add / Edit HTML)</a></td>
                                            <td><a href="#" id="group_4_option_2">(Add / Edit HTML)</a></td>
                                            <td><a href="#" id="group_4_option_3">(Add / Edit HTML)</a></td>
                                            <td><a href="#" id="group_4_option_4">(Add / Edit HTML)</a></td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="action">
                                    <ul>
                                        <li>
                                            <button id="next" name="next" class="button white" value="next" onclick="nextclick();">Next &#x00bb;</button>
                                        </li>
                                        <li style="float: right;padding-right: 5px;">
                                            <button id="back" name="back" type="submit" class="button white" value="back" >Back</button>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="block_1_option_1" id="block_1_option_1" value='<?php echo htmlentities($block_1_option_1); ?>' />
                        <input type="hidden" name="block_1_option_2" id="block_1_option_2" value='<?php echo htmlentities($block_1_option_2); ?>' />
                        <input type="hidden" name="block_1_option_3" id="block_1_option_3" value='<?php echo htmlentities($block_1_option_3); ?>' />
                        <input type="hidden" name="block_1_option_4" id="block_1_option_4" value='<?php echo htmlentities($block_1_option_4); ?>' />
                        <input type="hidden" name="block_2_option_1" id="block_2_option_1" value='<?php echo htmlentities($block_2_option_1); ?>' />
                        <input type="hidden" name="block_2_option_2" id="block_2_option_2" value='<?php echo htmlentities($block_2_option_2); ?>' />
                        <input type="hidden" name="block_2_option_3" id="block_2_option_3" value='<?php echo htmlentities($block_2_option_3); ?>' />
                        <input type="hidden" name="block_2_option_4" id="block_2_option_4" value='<?php echo htmlentities($block_2_option_4); ?>' />
                        <input type="hidden" name="block_3_option_1" id="block_3_option_1" value='<?php echo htmlentities($block_3_option_1); ?>' />
                        <input type="hidden" name="block_3_option_2" id="block_3_option_2" value='<?php echo htmlentities($block_3_option_2); ?>' />
                        <input type="hidden" name="block_3_option_3" id="block_3_option_3" value='<?php echo htmlentities($block_3_option_3); ?>' />
                        <input type="hidden" name="block_3_option_4" id="block_3_option_4" value='<?php echo htmlentities($block_3_option_4); ?>' />
                        <input type="hidden" name="block_4_option_1" id="block_4_option_1" value='<?php echo htmlentities($block_4_option_1); ?>' />
                        <input type="hidden" name="block_4_option_2" id="block_4_option_2" value='<?php echo htmlentities($block_4_option_2); ?>' />
                        <input type="hidden" name="block_4_option_3" id="block_4_option_3" value='<?php echo htmlentities($block_4_option_3); ?>' />
                        <input type="hidden" name="block_4_option_4" id="block_4_option_4" value='<?php echo htmlentities($block_4_option_4); ?>' />
                    </form>
                </div>
                <div id="dialog-modal-1" title="Add / Edit HTML" style="display:none;">
                    <textarea id="block_1_option_1_text" rows="20" style="width:664px;height:320px;"></textarea>
                </div>
                <div id="dialog-modal-2" title="Add / Edit HTML" style="display:none;">
                    <textarea id="block_1_option_2_text" rows="20" style="width:664px;height:320px;"></textarea>
                </div>
                <div id="dialog-modal-3" title="Add / Edit HTML" style="display:none;">
                    <textarea id="block_1_option_3_text" rows="20" style="width:664px;height:320px;"></textarea>
                </div>
                <div id="dialog-modal-4" title="Add / Edit HTML" style="display:none;">
                    <textarea id="block_1_option_4_text" rows="20" style="width:664px;height:320px;"></textarea>
                </div>
                <div id="dialog-modal-5" title="Add / Edit HTML" style="display:none;">
                    <textarea id="block_2_option_1_text" rows="20" style="width:664px;height:320px;"></textarea>
                </div>
                <div id="dialog-modal-6" title="Add / Edit HTML" style="display:none;">
                    <textarea id="block_2_option_2_text" rows="20" style="width:664px;height:320px;"></textarea>
                </div>
                <div id="dialog-modal-7" title="Add / Edit HTML" style="display:none;">
                    <textarea id="block_2_option_3_text" rows="20" style="width:664px;height:320px;"></textarea>
                </div>
                <div id="dialog-modal-8" title="Add / Edit HTML" style="display:none;">
                    <textarea id="block_2_option_4_text" rows="20" style="width:664px;height:320px;"></textarea>
                </div>
                <div id="dialog-modal-9" title="Add / Edit HTML" style="display:none;">
                    <textarea id="block_3_option_1_text" rows="20" style="width:664px;height:320px;"></textarea>
                </div>
                <div id="dialog-modal-10" title="Add / Edit HTML" style="display:none;">
                    <textarea id="block_3_option_2_text" rows="20" style="width:664px;height:320px;"></textarea>
                </div>
                <div id="dialog-modal-11" title="Add / Edit HTML" style="display:none;">
                    <textarea id="block_3_option_3_text" rows="20" style="width:664px;height:320px;"></textarea>
                </div>
                <div id="dialog-modal-12" title="Add / Edit HTML" style="display:none;">
                    <textarea id="block_3_option_4_text" rows="20" style="width:664px;height:320px;"></textarea>
                </div>
                <div id="dialog-modal-13" title="Add / Edit HTML" style="display:none;">
                    <textarea id="block_4_option_1_text" rows="20" style="width:664px;height:320px;"></textarea>
                </div>
                <div id="dialog-modal-14" title="Add / Edit HTML" style="display:none;">
                    <textarea id="block_4_option_2_text" rows="20" style="width:664px;height:320px;"></textarea>
                </div>
                <div id="dialog-modal-15" title="Add / Edit HTML" style="display:none;">
                    <textarea id="block_4_option_3_text" rows="20" style="width:664px;height:320px;"></textarea>
                </div>
                <div id="dialog-modal-16" title="Add / Edit HTML" style="display:none;">
                    <textarea id="block_4_option_4_text" rows="20" style="width:664px;height:320px;"></textarea>
                </div>
            </div>
        </div>
    </body>
</html>