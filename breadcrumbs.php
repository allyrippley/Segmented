<?php

function addBreadcrumbs($currentStep) {
    for ($i = 1; $i <= 5; $i++ ) {
        if($i == $currentStep) {
            echo "<b>";
        }
        if (isset($_SESSION['edit_flag'])) {
            switch($i) {
                case 1: echo "Campaign Name"; break;
                case 2: echo " &#x00bb; Edit External Links"; break;
                case 3: echo " &#x00bb; Edit News Links"; break;
                case 4: echo " &#x00bb; Edit Content Block"; break;
                case 5: echo " &#x00bb; Edit Articles"; break;
            }
        }
        else {
            switch($i) {
                case 1: echo "Campaign Name"; break;
                case 2: echo "&#x00bb; External Links"; break;
                case 3: echo " &#x00bb; News Links"; break;
                case 4: echo " &#x00bb; Content Block"; break;
                case 5: echo " &#x00bb; Articles"; break;
            }
        }
        if($i == $currentStep) { 
            echo "</b>"; 
        }
    }
    return true;
}                                    
?>
