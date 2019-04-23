<?php

class site_directions_class {
    
    /* Class Variables */
    private $session;
    
    private $error_handling_class;
    
    private $database_class;
    
    private $cookies_handling_class;
    
    private $user_data_handler;
    
    public $force_redirect = "";    // This may contain a new header to be sent to the browser

/**
     * The constructor of the site directions class
     * 
     * @param class $database                Instantiation of db_com class 
     * @param class $error_handler           Instantiation of error_handling class 
     * @param class $cookies_handler         Instantiation of cookies_handling class 
     * @param class $user_data_handler       Instantiation of user_data class 
     */
    function __construct($database, $error_handler, $cookies_handler, $user_data_handler) {
        $this->session = $_SESSION;
        $this->error_handling_class = $error_handler;
        $this->database_class = $database;
        $this->cookies_handling_class = $cookies_handler;
        $this->user_data_handler = $user_data_handler;
    }
    
    function __destruct() {}
    
    public function get_current_page() {
        return "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    }
    
     /**
     * The function that generates the titles of each page
     * 
     * @return string   The title of the page
     */
    public function get_page_title() {
        if ($this->get_current_page() == $this->link_generator("home")) {
            return "home";
        } elseif ($this->get_current_page() == $this->link_generator("about")) {
            return "about";
        } elseif ($this->get_current_page() == $this->link_generator("login")) {
            return "login";
        } elseif ($this->get_current_page() == $this->link_generator("signup")) {
            return "signup";
        } else {
            // return to 404 page
        }
    }
    
    /**
     * A function to set the HTTP header. Simply insert the name of the page
     * and the header will be assigned. See: 
     * http://php.net/manual/en/function.header.php
     * 
     * @param string  $raw_header   the name of the page
     */
    public function set_header($raw_header) {
        header("location: " . $raw_header);
        
        $this->redirecting = true;
    }
    
    /**
     * This function loads the page depending on the page identifier
     * 
     * @param string    $page_identifier    It specifies the name of the page that we want to load 
     */
    public function load_page($page_identifier) {
        if ($page_identifier === 'home') {
            require_once('pages/oper_home.php');
            oper_home();
        } elseif ($page_identifier === 'about') {
            require_once('pages/oper_about.php');
            oper_about();
        } elseif ($page_identifier === 'login') {
            require_once('pages/oper_login.php');
            oper_login();
        } elseif ($page_identifier === 'signup') {
            require_once('pages/oper_signup.php');
            oper_signup();
        }
    }
    
    /**
     * This is the function where we specify all the actions that will happen if
     * any of the buttons is pressed. All the redirections to other pages happen here.
     */
    public function send_post_request() {
        if (isset($_POST['user_signup'])) {
            require_once('pages/oper_signup.php');
            $outcome = user_signup_master($this->user_data_handler, $this->database_class);
  
            if ($outcome["error"]) {
                $this->error_handling_class->save_error_to_cookie('user_signup', $outcome['error_identifier']);
                $this->force_redirect = 'signup';
            } else {
                $username = $this->user_data_handler->get_username();
                $this->results = $outcome['query_result'];
                //$this->force_redirect = 'profile';
            }
            
        } elseif (isset($_POST['user_login'])) {
            require_once('pages/oper_login.php');
            $outcome = user_login_master($this->user_data_handler, $this->cookies_handling_class);
            
            if ($outcome["error"]) {
                $this->error_handling_class->save_error_to_cookie('user_login', $outcome['error_identifier']);
                $this->force_redirect = 'login';
            } else {
                $username = $this->user_data_handler->get_username();
                $this->force_redirect = 'profile';
            }
            
        }
    }
    
    /**
     * The following function can be used to generate links for the pages that 
     * exist in the web site
     * 
     * @param string    $page_identifier    This is the name of the page
     * @return string                       The link in string format
     */
    public function link_generator($page_identifier) {
        if ($page_identifier == 'home') {
            return 'http://' . $_SERVER['SERVER_NAME'] . '/operators_portal/';
        } elseif ($page_identifier == 'login') {
            return 'http://' . $_SERVER['SERVER_NAME'] . '/operators_portal/' . 'login';
        } elseif ($page_identifier == 'about') {
            return 'http://' . $_SERVER['SERVER_NAME'] . '/operators_portal/' . 'about';
        } elseif ($page_identifier == 'signup') {
            return 'http://' . $_SERVER['SERVER_NAME'] . '/operators_portal/' . 'signup';
        }
    }
    
    /**
     * A function to check whether a post request is active, i.e., if a form has
     * been submitted
     * 
     * @return boolean It is true if the user sends a form
     */
    public function is_post_request() {
        return $_SERVER['REQUEST_METHOD'] == 'POST';
    }
}