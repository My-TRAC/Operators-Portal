<?php

class error_handling_class {
    
    private $cookies_handling_class;
    
    /**
     * This field holds the error messages for errors that can appear when the 
     * user interacts with the web site, depending on each error_category
     */
    private $error_messages = array(
        'user_signup' => array(
            'empty' => 'You must fill all the empty fields',
            'no_first_name' => 'First name is required',
            'no_last_name' => 'Last name is required',
            'no_name' => 'Your first and last name is required',
            'no_username' => 'Username is required',
            'no_email' => 'Email is required',
            'no_password' => 'Password is required',
            'password_mismatch' => 'The passwords do not match',
            'password_invalid' => 'Password must be at least 8 characters',
            'no_VAT_num' => 'VAT number is required',
            'wrong_VAT_num' => 'VAT number is not correct',
            'no_address' => 'Address is required',
            'no_company_name' => 'Name of company is required',
            'no_website' => 'Website of company is required',
            'no_company_telephone' => 'Telephone of company is required',
            'username_exists' => 'Username already exists',
            'email_exists' => 'Email already exists',
            'email_invalid' => 'Invalid email format',
            'big_names' => 'Please insert less than 30 characters',
            'VAT_exists' => 'There is already a registered user from your company',
            'pending_exists' => 'Thank you for your registration. When all the documents are checked, we will inform you by email. For now, your registration is pending'
        ),
        'user_login' => array(
            'empty' => 'Please fill all the fields',
            'no_username' => 'Username is required',
            'no_password' => 'Password is required',
            'username_doesnot_exist' => 'Username does not exist',
            'wrong_password' => 'Wrong password',
            'pending_registration' => 'Your registration has not been confirmed yet'
        ),
        'edit_profile' => array(
            'empty' => 'You did not change any field'
        ),
        'upload_image' => array(
            'file_exists' => 'File already exists',
            'large_file' => 'Your file is too large',
            'wrong_format' => 'Only JPG, JPEG, PNG & GIF files are allowed',
            'wrong_format_pdf' => 'Only PDF, JPG, JPEG, PNG & GIF files are allowed',
            'fake_image' => 'File is not an image',
            'big_filename' => 'File name should be less that 50 characters'
        ),
        'upload_data' => array(
            'no_data_category' => 'You should choose one of the categories',
            'no_description' => 'You must describe the urgent situation',
//            'no_start_time' => 
//            'no_stop_sequence' => 
//            'no_delay' => 
//            'no_time' => 
            'trip_id_omitted' => 'Since trip_id is not provided, route_id, direction_id, start_time and start_date are required',
            'no_delay_time' => 'Either delay or time must be provided'
        )
    );
    
    /**
     * The constructor of the class
     * 
     * @param class  $cookies_handler     The cookies_handling class instantiation
     */
    function __construct($cookies_handler) {
        $this->cookies_handling_class = $cookies_handler;
    }
    
    function __destruct() {}
    
    /**
     * A getter to retrieve the message of the error that is associated with an
     * error identifier. The identifier is the key of $error_messages class 
     * variable for the specific category
     * 
     * @param string    $error_category     The category of the error depending on 
     * the actions of the user
     * @param string    $error_identifier   A string that describes the specific error that has been made
     * @return string 
     */
    public function get_error_message($error_category, $error_identifier) {
        foreach ($this->error_messages[$error_category] as $key => $value) {
            if ($key == $error_identifier) {
                return $value;
            }
        } 
        return null;
    }
    
    /**
     * The function where the error message is created and appears on the screen
     * 
     * @param string    $error_identifier   A string that describes the specific error that has been made
     */
    public function generate_error_msg($error_category, $error_identifier) {
        
        $error_msg = $this->get_error_message($error_category, $error_identifier);
        
?>
        <script type ="text/javascript">
            alert("<?php echo $error_msg; ?>");
        </script>
<?php
    }
    
    /**
     * 
     * @param string    $error_category     The category that of the error depending on the actions of the user
     * @param string    $error_identifier   A string that describes the specific error that has been made
     */
    public function save_error_to_cookie($error_category, $error_identifier) {
        
        // Create the value by combining category (e.g., signup) with the 
        // identifier (e.g., wrong_VAT_num)
        $cookie_value = $error_category . "-" . $error_identifier;
        
        // Encode the cookie
        $encoded_cookie_value = base64_encode($cookie_value);
        
        $this->cookies_handling_class->create_cookie('user-error', $encoded_cookie_value);
    }
}
