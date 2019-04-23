<?php

// All the functions that can not be grouped elsewhere are stored in here

/** The following function formats the data that the user inserts, in a way that
 * there will be no extra spaces or special characters.
 * 
 * @param   $data   The data that the user inserts in all the forms of the platform
 * @return  $data   These data get trimmed and they are safe to be inserted to the db
 */
function format_input($data) { 
    $data = trim($data); // Strips unnecessary characters (extra space, tab, newline) from the user input data
    $data = stripslashes($data); // Removes backslashes from the user input data
    $data = htmlspecialchars($data); // When a user tries to submit in a text 
    // field.  The code is now safe to be displayed on a page or inside an e-mail.
    return $data;
}

/**
 * The function breaks down a value to keys used in error_handling class (see 
 * $error_messages variable). The category and the identifier and divided by a 
 * dash (-)
 * 
 * @param string    $value  The value that was previously stored in the cookie 
 * and we need to break down to an error category and an error identifier
 * @return array    Includes the category [0] and the identifier [1] of the error
 */
function desolve_cookie_value($value) {
    // Get the position of the dash
    $substr_position = strpos($value, "-");
    
    // Get the category (which should be first)
    $error_category = substr($value, 0, $substr_position);
    
    // Get the error identifier (which should be second)
    $error_identifier = substr($value, $substr_position + 1);
    
    return array($error_category, $error_identifier);
}