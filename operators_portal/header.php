<?php
/**
 * This is the head of every page of the site.
 */
function oper_header($page_title) {
?>
    <head>     
        <title><?php echo ucwords($page_title); ?></title>
    </head>
<?php

    // We load basic resources and MDbootstrap in the header
    load_basic_resources();
}