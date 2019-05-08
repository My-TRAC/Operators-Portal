<?php 
// Initialize the session
session_start();//Session variables hold information about one single user, and are available to all pages in one application.
// By default, session variables last until the user closes the browser
// Most sessions set a user-key on the user's computer that looks something like this: 765487cf34ert8dede5a562e4f3a7e12.
// Then, when a session is opened on another page, it scans the computer for a user-key. If there is a match, it accesses that session, if not, it starts a new session.

require_once('functions.php');
require_once('header.php');
require_once('footer.php');
require_once('class.db_com.php');
require_once('class.user_data.php');
require_once('class.site_directions.php');
require_once('class.error_handling.php');
require_once('class.cookies_handling.php');
require_once('load_resources.php');
require_once('menu.php');

// Instantiate the classes
$cookies_handler = new cookies_handling_class();
$database_handler = new db_com_class($cookies_handler);
$database_handler = null;
$user_data_handler = new user_data_class($database_handler, $cookies_handler);
$error_handler = new error_handling_class($cookies_handler);
$site_directions = new site_directions_class($database_handler, $error_handler, $cookies_handler, $user_data_handler);

// Check if the user has submitted a form and perform actions about it (tests and database communication)
if ($site_directions->is_post_request()) {
    $site_directions->send_post_request();
}

?>

<!DOCTYPE html>
<html>
<?php
    // turns on output buffering
    ob_start();
    // Determine the title of the page
    $page_title = $site_directions->get_page_title();
    
    // Load the header of the page
    oper_header($page_title);
?>
    <body>
<?php
        oper_menu($site_directions);
?>
        <div id="contents-container" class="col-xs-10 offset-1">
<?php
            // Check if we have a forced redirect
            if ($site_directions->force_redirect != '') {
                $site_directions->set_header($site_directions->force_redirect);
                $site_directions->force_redirect = '';
            } else {
                $site_directions->load_page($page_title);
                $site_directions->redirecting = false;

                // Since we are not redirecting, we read the cookies and perform actions if necessary
                if (strlen($cookies_handler->error_category_identifier) > 0) {
                    $error_information = desolve_cookie_value($cookies_handler->error_category_identifier);

                    $error_handler->generate_error_msg($error_information[0], $error_information[1]);

                    // Finally delete the cookie
                    $cookies_handler->error_category_identifier = '';
                    $cookies_handler->destroy_cookie("user-error");
                }
            }

?>
        </div>
<?php
        // ... and delete output buffer
        $output = ob_get_clean();
        echo $output;  // send to output stream / Browser
        // Load the footer of the page
        oper_footer();
