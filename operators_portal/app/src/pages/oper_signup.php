<?php 

function oper_signup() {
?>
    <div class="container">
        <div class="header">
            <h1>Sign up</h1>
        </div>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <!-- Information sent from a form with the POST method is invisible to others
        (all names/values are embedded within the body of the HTTP request) and has 
        no limits on the amount of information to send.-->
            <div class="row">
                <div class="col-sm-12">
                    <h2 class="profile-section-headers">User Info</h2>
                    <div class="form-group">
                        <label for="First name">First name</label>
                        <input type="text" style="width: 250px" class="form-control" name="first_name"/>
                    </div>
                    <div class="form-group">
                        <label for="Last name">Last name</label>
                        <input type="text" style="width: 250px" class="form-control" name="last_name"/>
                    </div>    
                    <div class="form-group">
                        <label for="Username">Username</label>
                        <input type="text" style="width: 250px" class="form-control" name="username"/>
                    </div>
                    <div class="form-group">
                        <label for="Email">Email</label>
                        <input type="email" style="width: 250px" class="form-control" name="email"/>
                    </div>
                    <div class="form-group">
                        <label for="Password">Password</label>
                        <input type="password" style="width: 250px" class="form-control" name="pass"/>
                    </div>
                    <div class="form-group">
                        <label for="Retype Password">Retype Password</label>
                        <input type="password" style="width: 250px" class="form-control" name="repass"/>
                    </div>
                </div>
            </div>
            <hr> <!-- separation line --> 
            <div class="row">
                <div class="col-sm-12">
                    <h2 class="profile-section-headers">Company Info</h2>
                    <div class="form-group">
                        <label for="company_name">Company's name</label>
                        <input type="text" style="width: 250px" class="form-control" name="company_name"/>
                    </div>
                    <div class="form-group">
                        <label for="VAT">Company's address</label>
                        <input type="text" style="width: 250px" class="form-control" name="company_address"/>
                    </div>
                    <div class="form-group">
                        <label for="company_telephone">Company's telephone</label>
                        <input type="text" style="width: 250px" class="form-control" name="company_telephone"/>
                    </div>
                    <div class="form-group">
                        <label for="website">Website</label>
                        <input type="url" style="width: 250px" class="form-control" name="website"/>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-info btn-lg" name="user_signup">Submit</button>
        </form>
        <p>
            Already a member? <a href="index.php?page_title=login">Login</a>
        </p>

<!--       Uploads documents and stores it in a local file -->
        <div class="row">
            <div class="col-sm-12">
               <p>We need your verification documents to confirm your registration</p>
                <div>
                    <form method="post" enctype="multipart/form-data"> <!--It specifies which content-type to use when submitting the form-->
                        <input type="file" name="doc_to_upload[]" multiple/>
                        <input type="submit" value="Upload verification documents" name="upload_doc">
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php
}

/**
 * Retrieve user input from register form, perform checks and send the data to 
 * the database
 * 
 * @param class     $user_data_handler  The instantiation of user_data class
 * @param class     $database_handler   The instantiation of database class
 * @return array    $outcome            An array that contains the outcome of the
 * user input tests (keys: error, error_identifier) and the outcome of sending 
 * the query (user registration) to the database (keys: query_result, query_message)
 */
function user_signup_master($user_data_handler, $database_handler) {

    if( is_null(database_handler) ) {
        $cookies_handler = new cookies_handling_class();
       $database_handler = new db_com_class($cookies_handler);
    }




    // Initialize the return variable
    $outcome = array("error" => false, "error_identifier" => "", "query_result" => null, "query_message" => null, "user_signup_data" => null);

    // Get user inputs and format them
    $first_name = format_input($_POST['first_name']);
    $last_name = format_input($_POST['last_name']);
    $username = format_input($_POST['username']);
    $email = format_input($_POST['email']);
    $password = format_input($_POST['pass']);
    $repassword = format_input($_POST['repass']);
    
    // Encrypt the password before saving in the database
    $encoded_password = md5($password); 
    $password_retyped = md5($_POST['repass']);
   // $VAT_num = format_input($_POST['VAT']);
    $company_name = format_input($_POST['company_name']);
    $company_address = format_input($_POST['company_address']);
    $company_telephone = format_input($_POST['company_telephone']);
    $website = format_input($_POST['website']);

    // Create arrays to hold user inputs
    $user_signup_data = array(
        'first_name' => $first_name,
        'last_name' => $last_name,
        'username' => $username,
        'email' => $email,
        'password' => $password,
        'repassword' => $repassword,
        'encoded_password' => $encoded_password,
        'password_retyped' => $password_retyped
    );

    $company_signup_data = array(
       //'VAT_num' => $VAT_num, 
        'company_name' => $company_name,
        'company_telephone' => $company_telephone,
        'company_address' => $company_address,
        'website' => $website
    );

    // Run user input tests - Test 1: User data
    $user_data_test_results = check_user_signup_data($user_signup_data, $company_signup_data);
    
    // Run user input tests - Test 2: Company data
    $company_data_test_results = check_company_signup_data($company_signup_data);
    
    // Check if errors were found and if yes, populate the outcome variable and 
    // return. If there is no error, no "if" will be implemented.
    
    if ($user_data_test_results["error"]) {
        $outcome["error"] = $user_data_test_results["error"];
        $outcome["error_identifier"] = $user_data_test_results["error_identifier"];
        return $outcome;
    }
    
    if ($company_data_test_results["error"]) {
        $outcome["error"] = $company_data_test_results["error"];
        $outcome["error_identifier"] = $company_data_test_results["error_identifier"];
        return $outcome;
    }
    
    // Check if there is any other user with the same username
    $username_exists = $user_data_handler->username_exists($user_signup_data);
    if ($username_exists) {
        $outcome["error"] = TRUE;
        $outcome["error_identifier"] = 'username_exists';
        return $outcome;
    }
    
    // Check if the same email has been inserted again
    $email_exists = $user_data_handler->email_exists($user_signup_data);
    if ($email_exists) {
        $outcome["error"] = TRUE;
        $outcome["error_identifier"] = 'email_exists';
        return $outcome;
    }
    
    // Check if the VAT number already exists in the database
//    $VAT_exists = $user_data_handler->VAT_exists($company_signup_data);
//    if ($VAT_exists) {
//        $outcome["error"] = TRUE;
//        $outcome["error_identifier"] = 'VAT_exists';
//        return $outcome;
//    }
    
    // Check if the email is a valid email address
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $outcome["error"] = TRUE;
        $outcome["error_identifier"] = 'email_invalid';
        return $outcome;
    }
    
    // Insert the new data to the DB
    print ("INSERT DATA");
    if( is_null($database_handler )) {
        $cookies_handler = new cookies_handling_class();
        $database_handler = new db_com_class($cookies_handler);
    }

    if( is_null(database_handler) )
        $handler = "null\n";
    else $handler ="not null \n";

    $file = 'joanguiLog.txt';
    $current = file_get_contents($file);
    $current .= "Insert. Database handler: ".$handler;
    file_put_contents($file, $current);


    print ($user_signup_data);
    $result = $database_handler->insert_data("user_data", "first_name, last_name, username, email, password, pending_registration", "'" . $user_signup_data['first_name'] 
            . "', '" . $user_signup_data['last_name'] . "', '" . $user_signup_data['username'] . "', '" . $user_signup_data['email'] . "', '" 
            . $user_signup_data['encoded_password'] . "', 1" );
    $result_company = $database_handler->insert_data("company_data", "username, company_name, company_telephone, company_address, website, pending_registration", "'" . $user_signup_data['username'] 
            . "', '" . $company_signup_data['company_name'] . "', '" . $company_signup_data['company_telephone'] . "', '" . $company_signup_data['company_address'] . "', '" . $company_signup_data['website'] .  "', 1");
    
    $outcome["error"] = TRUE;
    $outcome["error_identifier"] = 'pending_exists';
    $outcome["query_result"] = $result;
    $outcome["user_signup_data"] = $user_signup_data;
    return $outcome;
}

/** The following function is about form validation. It ensures that everything in 
 * the form is filled correctly and throws an error message if not. If there are
 * no errors, then the user can register.
 * 
 * @param array     $user_signup_data   An associative array that includes all the data that the user inserted during his/her registration
 * @return array    This array specifies if there is an error ("error" = TRUE) and also includes an error_identifier (which specifies the error_message)
 */
function check_user_signup_data($user_signup_data, $company_signup_data) {

    // Check if the credentials inserted have more than 30 characters
    if(strlen($user_signup_data['first_name']) > 30 || $user_signup_data['last_name'] > 30 || $user_signup_data['username'] > 30|| $user_signup_data['email'] > 30) {
        return array("error" => true, "error_identifier" => 'big_names');

    }
    
    // Check if nothing was given
    if (empty($user_signup_data['first_name']) && empty($user_signup_data['last_name']) && empty($user_signup_data['username']) 
            && empty($user_signup_data['email']) && empty($user_signup_data['password']) && empty($user_signup_data['repassword'])
            && empty($company_signup_data['company_name']) && empty($company_signup_data['company_address']) && empty($company_signup_data['company_telephone']) 
            && empty($company_signup_data['website'])){
        return array("error" => true, "error_identifier" => 'empty');
    }

    if (empty($user_signup_data['first_name'])){
        return array("error" => true, "error_identifier" => 'no_first_name');
    }
    
    if (empty($user_signup_data['last_name'])){
        return array("error" => true, "error_identifier" => 'no_last_name');
    }
    
    if (empty($user_signup_data['username'])){
        return array("error" => true, "error_identifier" => 'no_username');
    }

    if (empty($user_signup_data['password'])) { 
        return array("error" => true, "error_identifier" => 'no_password');
    }
    
    if (empty($user_signup_data['repassword'])) { 
        return array("error" => true, "error_identifier" => 'no_password');
    }
    
    // Check for wrong password before inserting the data in the DB
    if (($user_signup_data['encoded_password']) != ($user_signup_data['password_retyped'])) {
        return array("error" => true, "error_identifier" => 'password_mismatch');
    }
    
    if (empty($user_signup_data['email'])) {
        return array("error" => true, "error_identifier" => 'no_email');
    }
}  
    
/** 
 * The following function is about form validation. It ensures that everything in 
 * and checks if anything or transfered in the  completed in the form is empty
 * the form is filled correctly and throws an error message if not. If there are
 * no errors, then the user can register.
 *  
 * @param array  $company_signup_data   An associative array that includes all the data that the user inserted about his/her company during registration 
 * @return array This array specifies if there is an error ("error" = TRUE) and also includes an error_identifier (which specifies the error_message)
 */
function check_company_signup_data($company_signup_data) {

//    // Check if the credentials inserted have more than 30 characters
//    if(strlen($company_signup_data['company_name']) || $company_signup_data['company_address'] || $company_signup_data['company_telephone'] || $company_signup_data['website'] > 30) {
//        return array("error" => true, "error_identifier" => 'big_names');
//    }
    
   // Check if nothing was given
    if (empty($company_signup_data['company_name'])){
       return array("error" => true, "error_identifier" => 'no_company_name');
    }
    
    if (empty($company_signup_data['company_address'])){
       return array("error" => true, "error_identifier" => 'no_address');
    }
    
    if (empty($company_signup_data['company_telephone'])){
       return array("error" => true, "error_identifier" => 'no_company_telephone');
    }
    
    if (empty($company_signup_data['website'])){
       return array("error" => true, "error_identifier" => 'no_website');
    }
}