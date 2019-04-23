<?php
class user_data_class {
    
    /**
     * The username of the connected user
     * 
     * @var string
     */
    private $username;
    /**
     * The instance of the database connection
     * 
     * @var db_com_class 
     */
    private $database_handler;
    
    /**
     * Constructor of the class
     */
    function __construct($database_handler, $cookies_handler) {
        
        // Get the username from cookies
        $this->username = $cookies_handler->get_username_cookie_value();
        
        // Save the database handler
        $this->database_handler = $database_handler;
    }
    
    function __destruct() {}
    
    /**
     * This method checks whether a user is logged in given his/her username
     * 
     * @return boolean Will be true if the user is logged in
     */
    private function authenticate_user() {
        return true;
    }
    
    /**
     * A getter of the username
     */
    public function get_username() {
        return $this->username;
    }
    
    /**
     * Check whether the same username already exists in efficious databases
     * 
     * @param   string  $user_data  The username to check whether it exists in the db
     * @return boolean  Will be false if the username does not exist
     */
    public function username_exists($user_data) {
        $result = $this->database_handler->select_data("username", "user_data", "username='" . $user_data['username'] . "' AND deleted = 0");
        
        // If what the db returns is not empty, then the username exists
        if (!is_null($result)) {
            return TRUE; 
        } 
        return FALSE;
    }
    
    /**
     * Check whether the same email exists in efficious databases
     * 
     * @param   string  $user_data The email to check whether it exists in the db
     * @return  boolean  Will be false if the email does not exist
     */
    public function email_exists($user_data) {
        $result = $this->database_handler->select_data("email", "user_data", "email='" . $user_data['email'] . "'");
        
        // If what the db returns is not empty, then the email has already been inserted
        if (!is_null($result)) {
            return TRUE; 
        }
        return FALSE;
    }
    
    /**
     * Here we check if the registration of the user has been confirmed
     * 
     * @param type $user_data
     * @return boolean
     */
    function pending_registration($user_data) {
        $result = $this->database_handler->select_data("pending_registration", "user_data", "username='" . $user_data['username'] . "'", NULL, NULL, TRUE);
        
        // If what the db returns is not empty, then the username exists
        if ($result[0]['pending_registration'] == 1) {
            return TRUE; 
        } 
        return FALSE;
    }
    
    /**
     * This is the function that checks if the password that the user has 
     * inserted is correct
     * 
     * @param array $user_data  An associative array that contains all the data about the user
     * @return boolean  It is true when the password returned from db is equal to the one inserted from user
     */
    public function password_correct($user_data) {
        $encoded_password = md5($user_data['password']);
        $result = $this->database_handler->select_data("password", "user_data", "username='" . $user_data["username"] . "'", NULL, NULL, TRUE);
        
        // If the password that the db returns is equal to the password that the user inserted, then the password is correct
        if ($result[0]["password"] == $encoded_password) {
            return TRUE; 
        } 
        return FALSE;
    }

    /**
     * Check whether a VAT number exists in efficious database
     * 
     * @param   string      $company_data   The VAT number to check whether it exists in the db
     * @return  boolean     Will be false if the VAT number does not exist
     */
    public function VAT_exists($company_data) {
        $result = $this->database_handler->select_data("VAT", "company_data", "VAT ='" . $company_data['VAT_num'] . "'");
        
        // If what the db returns is not empty, then the VAT number already exists
        if (!is_null($result)) {
            return TRUE; 
        } 
        return FALSE;
    }
    
    
    /**
     * This function deletes the user's profile by setting the variable $deleted = 1
     * 
     * @param string $username The username of the logged in user
     */
    function delete_profile($username) {
        $deleted = 1;
        $set_new_variable = "deleted = '". $deleted . "'";
        // variable deleted gets equal to 1 in user_data table
        $this->database_handler->update_data("user_data", $set_new_variable, $username);
        
        // we delete also his company from company_data table
        $this->database_handler->update_data("company_data", $set_new_variable, $username);
        
        // finally, we delete all his uploaded products from company_data table
        $this->database_handler->update_data("products_data", $set_new_variable, $username);
    }
    
    /** The following function destroys the session and clears the username variable, 
     * when the user chooses logout and then redirects him to the login page to 
     * re-enter his data.
     * 
     * input class   $cookies_handler   The instantiation class of cookies
     */
    function user_logout($cookies_handler) {
        $cookies_handler->destroy_username_cookie();
        return true;
    }
}