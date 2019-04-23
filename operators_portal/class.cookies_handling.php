<?php
class cookies_handling_class {
    
    /** @var int The default cookie lifetime in seconds */
    private $default_cookie_lifetime = 7200;
    
    /** @var string The default path for the cookie */
    private $default_cookie_path = "/";
    
    /** @var int The default username cookie lifetime in seconds */
    private $default_username_cookie_lifetime = 7200;
    
    /** 
     * @var array A helper variable to show the cookie names we have to search 
     * for when the class is created 
     */
    private $default_cookie_names = array(
        "user-error"
    );
    
    /** @var string Decoded cookie message about an error */
    public $error_category_identifier = "";
    
    /**
     * The constructor of the cookies_handling class. 
     */
    function __construct() {
        $this->error_category_identifier = $this->check_user_error_cookies();
        
        if (!isset($_COOKIE["is_user_logged_in"])) {
            $this->create_cookie("is_user_logged_in", "0", $this->default_username_cookie_lifetime);
        }
    }
    
    function __destruct() {}
    
    /**
     * A function to create cookies. This is the same as the PhP function 
     * setCookies: http://php.net/manual/en/function.setcookie.php
     * In addition, it implements some default values that are basic for the 
     * website
     * 
     * @param type $cookie_name
     * @param type $cookie_value
     * @param type $expiry
     * @param string    $path   Optional. The path of the cookie. If not 
     * set, it takes the default value.
     */
    function create_cookie($cookie_name, $cookie_value, $expiry = null, $path = null) {
        
        // Get the defaults
        $expiry = isset($expiry) ? $expiry : $this->default_cookie_lifetime;
        $path = isset($path) ? $path : $this->default_cookie_path;
        
        // Set the cookie
        setcookie($cookie_name, $cookie_value, time() + $expiry, $path);
    }
    
    /**
     * A cookie cannot be destroyed, but can be set with a time previous to now.
     * Thus, we have to set the cookie again with an expiry time that is older 
     * than the present.
     * 
     * @param string    $cookie_name    The name of the cookie.
     * @param string    $path           Optional. The path of the cookie. If not 
     * set, it takes the default value.
     */
    function destroy_cookie($cookie_name, $path = null) {
        
        // If path is not given, get the default
        $path = isset($path) ? $path : $this->default_cookie_path;
        
        // Get cookie value
        $cookie_value = $_COOKIE[$cookie_name];

        // Unset the cookie
        unset($_COOKIE[$cookie_name]);
        setCookie($cookie_name, $cookie_value, 1, $path);
    }
    
    /**
     * The method creates two cookies, first the one containing the username and
     * second a cookie to determine whether the user is logged in (will be 1 if 
     * user is logged in)
     * 
     * @param string    $username   The username of the user
     */
    public function create_username_cookie($username) {
        $this->create_cookie("username", $username, $this->default_username_cookie_lifetime);
        
        $this->create_cookie("is_user_logged_in", "1", $this->default_username_cookie_lifetime);
    }
    
    /**
     * This method destroys cookies associated with user log in. Namely, the 
     * username and is_user_logged_in cookies
     */
    public function destroy_username_cookie() {
        $this->destroy_cookie("username");
        $this->destroy_cookie("is_user_logged_in");
    }
    
    /**
     * 
     * @return type
     */
    public function get_username_cookie_value() {
        if (isset($_COOKIE["username"])) {
            return $_COOKIE["username"];
        } else {
            return NULL;
        }
    }
    
    /**
     * 
     * @return type
     */
    public function get_exchange_cookie_value() {
        if (isset($_COOKIE["exchange"])) {
            return $_COOKIE["exchange"];
        } else {
            return NULL;
        }
    }
    
    /**
     * This function checks if the user is logged in (TRUE) or not (FALSE)
     * 
     * @return boolean Returns true if the user is logged in and false if not
     */
    public function is_user_logged_in() {
        return ($_COOKIE["is_user_logged_in"] === "1") ? TRUE : FALSE;
    }
    
    /**
     * 
     * @return string
     */
    private function check_user_error_cookies() {
        foreach ($this->default_cookie_names as $error_cookie_name) {
            if (isset($_COOKIE[$error_cookie_name])) {
                $encoded_cookie_value = $_COOKIE[$error_cookie_name];
                
                $cookie_value = base64_decode($encoded_cookie_value);
                
                return $cookie_value;
            }
        }
        return "";
    }
}