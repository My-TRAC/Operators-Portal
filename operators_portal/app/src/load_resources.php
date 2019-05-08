<?php

/** 
 * This is the script where all of the links and resources are called depending
 * on each case
 */

/**
 * The function that contains all the plugins and resources that are needed to run
 * in every page
 */
function load_basic_resources(){
?>  
    <link type="text/css" rel="stylesheet" href="./assets/css/style.css"/>
    <link type="text/css" rel="stylesheet" href="./assets/plugins/bootstrap/bootstrap.css"/>
    <script type="text/javascript" src="./assets/plugins/jquery-3.3.1.min.js"></script>
    <link rel="stylesheet" href="./assets/plugins/fontawesome-5.3.1/css/all.css">
    <script type="text/javascript" src="./assets/js/toolset.js"></script>
<?php
    load_modal_resources();
}

/**
 * The function that contains the javascript regarding the menu 
 */
function load_menu_resources(){
?>
    <script type="text/javascript" src="./assets/js/menu.js"></script>
<?php
}

/**
 * The function that contains the resources about the usage of modals in the platform
 */
function load_modal_resources(){
?>
    <link rel="stylesheet" href="./assets/plugins/bootstrap/bootstrap_modal.css"/>
    <script src="./assets/js/bootstrap.min.js"></script>
<?php
}

function load_upload_data_resources(){
?>
    <script type="text/javascript" src="./assets/js/upload_data.js"></script>
<?php    
}