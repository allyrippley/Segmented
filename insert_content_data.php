<?php

include ("database_params.php");
include ("generateurl.php");
include ("simple_html_dom.php");

// Get verbose errors for troubleshooting
if (!ini_get('display_errors')) {
    ini_set('display_errors', '1');
}

session_start();
$db_handler = mysql_connect($hostname, $username, $password);
$message = '';
$host = $_SERVER['SERVER_NAME'];
$uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');

if (mysql_errno()) {
    $message = "Connection failed : " . mysql_error();
    $_SESSION["content_block_database_message"] = $message;
    $extra = "contentblock.php";
    header("Location: http://$host$uri/$extra");
}

mysql_select_db($databasename, $db_handler);

if (isset($_POST['next'])) {
    $result = 0;
    $campaignId = $_SESSION["campaign_id"];
    $articleId = 1;
    $extra = "addarticle.php?id=$articleId";
    
    $allContent = '';
    $allContent = $allContent . stripslashes($_SESSION["block_1_option_1"]);
    $allContent = $allContent . stripslashes($_SESSION["block_1_option_2"]);
    $allContent = $allContent . stripslashes($_SESSION["block_1_option_3"]);
    $allContent = $allContent . stripslashes($_SESSION["block_1_option_4"]);
    $allContent = $allContent . stripslashes($_SESSION["block_2_option_1"]);
    $allContent = $allContent . stripslashes($_SESSION["block_2_option_2"]);
    $allContent = $allContent . stripslashes($_SESSION["block_2_option_3"]);
    $allContent = $allContent . stripslashes($_SESSION["block_2_option_4"]);
    $allContent = $allContent . stripslashes($_SESSION["block_3_option_1"]);
    $allContent = $allContent . stripslashes($_SESSION["block_3_option_2"]);
    $allContent = $allContent . stripslashes($_SESSION["block_3_option_3"]);
    $allContent = $allContent . stripslashes($_SESSION["block_3_option_4"]);
    $allContent = $allContent . stripslashes($_SESSION["block_4_option_1"]);
    $allContent = $allContent . stripslashes($_SESSION["block_4_option_2"]);
    $allContent = $allContent . stripslashes($_SESSION["block_4_option_3"]);
    $allContent = $allContent . stripslashes($_SESSION["block_4_option_4"]);


    $block_1_option_1_data = '';
    if (isset($_POST['block_1_option_1'])) {
        
        $block_1_option_1_data = mysql_real_escape_string(stripslashes($_POST['block_1_option_1'])); //$_POST['block_1_option_1'];
    } else {
        if (isset($_SESSION["block_1_option_1"])) {
            $block_1_option_1_data = $_SESSION["block_1_option_1"]; //$_SESSION["block_1_option_1"];
        } else {
            $block_1_option_1_data = '';
        }
    }

    $block_1_option_2_data = '';
    if (isset($_POST['block_1_option_2'])) {
        $block_1_option_2_data = mysql_real_escape_string(stripslashes($_POST['block_1_option_2'])); //$_POST['block_1_option_2'];
    } else {
        if (isset($_SESSION["block_1_option_2"])) {
            $block_1_option_2_data = $_SESSION["block_1_option_2"]; //$_SESSION["block_1_option_2"];
        } else {
            $block_1_option_2_data = '';
        }
    }

    $block_1_option_3_data = '';
    if (isset($_POST['block_1_option_3'])) {
        $block_1_option_3_data = mysql_real_escape_string(stripslashes($_POST['block_1_option_3'])); //$_POST['block_1_option_3'];
    } else {
        if (isset($_SESSION["block_1_option_3"])) {
            $block_1_option_3_data = $_SESSION["block_1_option_3"]; //$_SESSION["block_1_option_3"];
        } else {
            $block_1_option_3_data = '';
        }
    }

    $block_1_option_4_data = '';
    if (isset($_POST['block_1_option_4'])) {
        $block_1_option_4_data = mysql_real_escape_string(stripslashes($_POST['block_1_option_4'])); //$_POST['block_1_option_4'];
    } else {
        if (isset($_SESSION["block_1_option_4"])) {
            $block_1_option_4_data = $_SESSION["block_1_option_4"]; //$_SESSION["block_1_option_4"];
        } else {
            $block_1_option_4_data = '';
        }
    }

    $block_2_option_1_data = '';
    if (isset($_POST['block_2_option_1'])) {
        $block_2_option_1_data = mysql_real_escape_string(stripslashes($_POST['block_2_option_1'])); // $_POST['block_2_option_1'];
    } else {
        if (isset($_SESSION["block_2_option_1"])) {
            $block_2_option_1_data = $_SESSION["block_2_option_1"]; //$_SESSION["block_2_option_1"];
        } else {
            $block_2_option_1_data = '';
        }
    }

    $block_2_option_2_data = '';
    if (isset($_POST['block_2_option_2'])) {
        $block_2_option_2_data = mysql_real_escape_string(stripslashes($_POST['block_2_option_2'])); // $_POST['block_2_option_2'];
    } else {
        if (isset($_SESSION["block_2_option_2"])) {
            $block_2_option_2_data = $_SESSION["block_2_option_2"]; //$_SESSION["block_2_option_2"];
        } else {
            $block_2_option_2_data = '';
        }
    }

    $block_2_option_3_data = '';
    if (isset($_POST['block_2_option_3'])) {
        $block_2_option_3_data = mysql_real_escape_string(stripslashes($_POST['block_2_option_3'])); //$_POST['block_2_option_3'];
    } else {
        if (isset($_SESSION["block_2_option_3"])) {
            $block_2_option_3_data = $_SESSION["block_2_option_3"]; //$_SESSION["block_2_option_3"];
        } else {
            $block_2_option_3_data = '';
        }
    }

    $block_2_option_4_data = '';
    if (isset($_POST['block_2_option_4'])) {
        
        $block_2_option_4_data = mysql_real_escape_string(stripslashes($_POST['block_2_option_4'])); //$_POST['block_2_option_4'];
    } else {
        if (isset($_SESSION["block_2_option_4"])) {
            $block_2_option_4_data = $_SESSION["block_2_option_4"]; //$_SESSION["block_2_option_4"];
        } else {
            $block_2_option_4_data = '';
        }
    }

    $block_3_option_1_data = '';
    if (isset($_POST['block_3_option_1'])) {
        $block_3_option_1_data = mysql_real_escape_string(stripslashes($_POST['block_3_option_1'])); //$_POST['block_3_option_1'];
    } else {
        if (isset($_SESSION["block_3_option_1"])) {
            $block_3_option_1_data = $_SESSION["block_3_option_1"]; //$_SESSION["block_3_option_1"];
        } else {
            $block_3_option_1_data = '';
        }
    }

    $block_3_option_2_data = '';
    if (isset($_POST['block_3_option_2'])) {
        $block_3_option_2_data = mysql_real_escape_string(stripslashes($_POST['block_3_option_2'])); //$_POST['block_3_option_2'];
    } else {
        if (isset($_SESSION["block_3_option_2"])) {
            $block_3_option_2_data = $_SESSION["block_3_option_2"]; //$_SESSION["block_3_option_2"];
        } else {
            $block_3_option_2_data = '';
        }
    }

    $block_3_option_3_data = '';
    if (isset($_POST['block_3_option_3'])) {
        $block_3_option_3_data = mysql_real_escape_string(stripslashes($_POST['block_3_option_3'])); //$_POST['block_3_option_3'];
    } else {
        if (isset($_SESSION["block_3_option_3"])) {
            $block_3_option_3_data = $_SESSION["block_3_option_3"]; //$_SESSION["block_3_option_3"];
        } else {
            $block_3_option_3_data = '';
        }
    }


    $block_3_option_4_data = '';
    if (isset($_POST['block_3_option_4'])) {
        $block_3_option_4_data = mysql_real_escape_string(stripslashes($_POST['block_3_option_4'])); //$_POST['block_3_option_4'];
    } else {
        if (isset($_SESSION["block_3_option_4"])) {
            $block_3_option_4_data = $_SESSION["block_3_option_4"]; //$_SESSION["block_3_option_4"];
        } else {
            $block_3_option_4_data = '';
        }
    }

    $block_4_option_1_data = '';
    if (isset($_POST['block_4_option_1'])) {
        $block_4_option_1_data = mysql_real_escape_string(stripslashes($_POST['block_4_option_1'])); //$_POST['block_4_option_1'];
    } else {
        if (isset($_SESSION["block_4_option_1"])) {
            $block_4_option_1_data = $_SESSION["block_4_option_1"]; //$_SESSION["block_4_option_1"];
        } else {
            $block_4_option_1_data = '';
        }
    }

    $block_4_option_2_data = '';
    if (isset($_POST['block_4_option_2'])) {
        $block_4_option_2_data = mysql_real_escape_string(stripslashes($_POST['block_4_option_2'])); //$_POST['block_4_option_2'];
    } else {
        if (isset($_SESSION["block_4_option_2"])) {
            $block_4_option_2_data = $_SESSION["block_4_option_2"]; //$_SESSION["block_4_option_2"];
        } else {
            $block_4_option_2_data = '';
        }
    }

    $block_4_option_3_data = '';
    if (isset($_POST['block_4_option_3'])) {
        $block_4_option_3_data = mysql_real_escape_string(stripslashes($_POST['block_4_option_3'])); //$_POST['block_4_option_3'];
    } else {
        if (isset($_SESSION["block_4_option_3"])) {
            $block_4_option_3_data = $_SESSION["block_4_option_3"]; //$_SESSION["block_4_option_3"];
        } else {
            $block_4_option_3_data = '';
        }
    }

    $block_4_option_4_data = '';
    if (isset($_POST['block_4_option_4'])) {
        $block_4_option_4_data = mysql_real_escape_string(stripslashes($_POST['block_4_option_4'])); //$_POST['block_4_option_4'];
    } else {
        if (isset($_SESSION["block_4_option_4"])) {
            $block_4_option_4_data = $_SESSION["block_4_option_4"]; //$_SESSION["block_4_option_4"];
        } else {
            $block_4_option_4_data = '';
        }
    }

    // Parse all content for links, and if they aren't in the database, create tracking URLs
    $query = "select url from baseurls where id = 2";
    $result = mysql_query($query);
    $baseTrackingUrl = mysql_fetch_row($result);
    
    
    $link_source = "page";
    $html = str_get_html($allContent);
    
    //echo $html;
    
    if (strlen($html) > 0) {
        foreach($html->find('a') as $element)  {
            if (((stripos($element, 'mailto:') == false)) AND ($element->href !== 'http://') AND ($element->href !== 'https://')) {
                if (stripos($element->href, '://') == false) { $element->href = $baseTrackingUrl[0] . $element->href; }
                //echo '*' . $element->href . '*<br />';
                $query = "SELECT *  FROM `links` WHERE `destination_url` LIKE '" . $element->href . "' AND `campaigns_id` = '$campaignId'";
                //echo $query . "<br /> ---";
                $result = mysql_query($query);
                $row = mysql_fetch_row($result);

                // If the URL is not in the database then add the tracking URL
                if (empty($row)) {
                    $generatedurl = generateTrackingUrl($element->href, $campaignId, 1);
                    $query = "insert into links";
                    $query .="(destination_url,generated_url,campaigns_id,source)values";
                    $query .= "('$element->href','$generatedurl','$campaignId','$link_source')";
                    //echo $query . "<br /><br />";
                    $result = mysql_query($query);
                }
            }
        }
    }

    $contentId = '';
    if (isset($_SESSION["content_id"])) {
        $contentId = $_SESSION["content_id"];
        $query = "update contents set block_1_option_1 =" . " '$block_1_option_1_data' " . "," . "block_1_option_2 =" . " '$block_1_option_2_data' " . "," . "block_1_option_3 =" . " '$block_1_option_3_data' " . "," . "block_1_option_4 =" . " '$block_1_option_4_data' ";
        $query .= "," . "block_2_option_1 =" . " '$block_2_option_1_data' " . "," . "block_2_option_2 =" . " '$block_2_option_2_data' " . "," . "block_2_option_3 =" . " '$block_2_option_3_data' " . "," . "block_2_option_4 =" . " '$block_2_option_4_data' ";
        $query .= "," . "block_3_option_1 =" . " '$block_3_option_1_data' " . "," . "block_3_option_2 =" . " '$block_3_option_2_data' " . "," . "block_3_option_3 =" . " '$block_3_option_3_data' " . "," . "block_3_option_4 =" . " '$block_3_option_4_data' ";
        $query .= "," . "block_4_option_1 =" . " '$block_4_option_1_data' " . "," . "block_4_option_2 =" . " '$block_4_option_2_data' " . "," . "block_4_option_3 =" . " '$block_4_option_3_data' " . "," . "block_4_option_4 =" . " '$block_4_option_4_data' ";
        $query .= "," . "campaigns_id =" . " '$campaignId' " . "where id =" . $contentId;
        $result = mysql_query($query);
        if (mysql_errno()) {
            $message = "Database error : " . mysql_error();
            $_SESSION["content_block_database_message"] = $message;
            $extra = "contentblock.php";
            header("Location: http://$host$uri/$extra");
        }
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
    } else {
        $query = "insert into contents (block_1_option_1,block_1_option_2,block_1_option_3,block_1_option_4,block_2_option_1,block_2_option_2,block_2_option_3,block_2_option_4,block_3_option_1,block_3_option_2,block_3_option_3,block_3_option_4,block_4_option_1,block_4_option_2,block_4_option_3,block_4_option_4,campaigns_id)";
        $query .= "values ('$block_1_option_1_data','$block_1_option_2_data','$block_1_option_3_data','$block_1_option_4_data','$block_2_option_1_data','$block_2_option_2_data','$block_2_option_3_data','$block_2_option_4_data','$block_3_option_1_data','$block_3_option_2_data','$block_3_option_3_data','$block_3_option_4_data','$block_4_option_1_data','$block_4_option_2_data','$block_4_option_3_data','$block_4_option_4_data','$campaignId')";
        $result = mysql_query($query);
        $contentId = mysql_insert_id();
        if (mysql_errno()) {
            $message = "Database error : " . mysql_error();
            $_SESSION["content_block_database_message"] = $message;
            $extra = "contentblock.php";
            header("Location: http://$host$uri/$extra");
        }
    }

    $_SESSION["content_id"] = $contentId;
    $_SESSION["block_1_option_1"] = $block_1_option_1_data;
    $_SESSION["block_1_option_2"] = $block_1_option_2_data;
    $_SESSION["block_1_option_3"] = $block_1_option_3_data;
    $_SESSION["block_1_option_4"] = $block_1_option_4_data;
    $_SESSION["block_2_option_1"] = $block_2_option_1_data;
    $_SESSION["block_2_option_2"] = $block_2_option_2_data;
    $_SESSION["block_2_option_3"] = $block_2_option_3_data;
    $_SESSION["block_2_option_4"] = $block_2_option_4_data;
    $_SESSION["block_3_option_1"] = $block_3_option_1_data;
    $_SESSION["block_3_option_2"] = $block_3_option_2_data;
    $_SESSION["block_3_option_3"] = $block_3_option_3_data;
    $_SESSION["block_3_option_4"] = $block_3_option_4_data;
    $_SESSION["block_4_option_1"] = $block_4_option_1_data;
    $_SESSION["block_4_option_2"] = $block_4_option_2_data;
    $_SESSION["block_4_option_3"] = $block_4_option_3_data;
    $_SESSION["block_4_option_4"] = $block_4_option_4_data;



    $_SESSION["contentblock_database_message"] = $message;
    if ($result) {
        header("Location: http://$host$uri/$extra");
    }
} else if (isset($_POST['back'])) {
    $extra = "addnewsurls.php";
    header("Location: http://$host$uri/$extra");
}
?>