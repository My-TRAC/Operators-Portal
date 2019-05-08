 <?php
 
function oper_login() {
?>
    <div class="container">
        <div class="header">
            <h1>Log in</h1>
        </div>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <form method="post" action="">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" style="width: 250px" class="form-control" name="username"/>
            </div>
            <div class="form-group">
                <label for="Password">Password</label>
                <input type="password" style="width: 250px" class="form-control" name="pass"/>
            </div>
            <br>
                <button type="submit" class="btn btn-info btn-lg" name="user_login">Login</button>
        </form>
        <p>
            Not yet a member? <a href="index.php?page_title=signup">Sign up</a>
        </p>
    </div>
<?php
}

/**
 * Retrieve user input from register form, perform checks and log in the user by
 * adding a cookie
 */
function user_login_master($user_data_handler, $cookies_handler) {

    // Get user inputs and format them
    $username = format_input($_POST['username']);
    $password = format_input($_POST['pass']);

    // Create arrays to hold user inputs
    $user_login_data = array(
        'username' => $username,
        'password' => $password
    );
    
    // Run user input tests - Test: User data
    $user_data_test_results = check_user_login_data($user_login_data);

    // Check if errors were found and if yes, populate the outcome variable and return
    if ($user_data_test_results["error"]) {
        $outcome["error"] = $user_data_test_results["error"];
        $outcome["error_identifier"] = $user_data_test_results["error_identifier"];
        return $outcome;
    }

    // Check that the user has an account
    $username_exists = $user_data_handler->username_exists($user_login_data);
    if (!$username_exists) {
        $outcome["error"] = TRUE;
        $outcome["error_identifier"] = 'username_doesnot_exist';
        return $outcome;
    }
    
    // Check for wrong password before searching in the database!!
    $password_correct = $user_data_handler->password_correct($user_login_data);
    if (!$password_correct) {
        $outcome["error"] = TRUE;
        $outcome["error_identifier"] = 'wrong_password';
        return $outcome;
    }
    
    $pending_registration = $user_data_handler->pending_registration($user_login_data);
    if($pending_registration) {
        $outcome["error"] = TRUE;
        $outcome["error_identifier"] = 'pending_registration';
        return $outcome;
    }
            
    // Log in the user
    $cookies_handler->create_username_cookie($username);
    return $outcome;
}

/**
 * The following function is about form validation. It ensures that everything in 
 * the form is filled correctly and throws an error message if not. If there are
 * no errors, then the user can login.
 * 
 * @param   array   $user_login_data    The data of user stored in an associative array
 * @return  array   This array specifies if there is an error ("error" = TRUE) and also includes an error_identifier (which specifies the error_message)
 */
function check_user_login_data($user_login_data) {

    // Check if nothing was given
    if (empty($user_login_data['username']) && empty($user_login_data['password'])) {
        return array("error" => true, "error_identifier" => 'empty');
    }

    if (empty($user_login_data['username'])) {
        return array("error" => true, "error_identifier" => 'no_username');
    }

    if (empty($user_login_data['password'])) {
        return array("error" => true, "error_identifier" => 'no_password');
    }
}
