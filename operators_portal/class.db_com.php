<?php 

/**
 * This file contains the class with methods for searching, creating, updating etc. 
 * tables in the MySQL database
 */
class db_com_class {

    /**
    * Array to hold the credentials
    * 
    * @var array
    */
    private $db_credentials;
    
    /**
    * The database connection
    * 
    * @var mysqli class
    */
    private $connection;
    
    private $conn_activity;
    
    /**
    * Constructor of the class. Initiate the connection with the database
    */
    function __construct($cookies_handler) {

            // Database variables
            $sn = "localhost";      // server name
            $un = "root";           // user name
            $ps = "";               // password
            $dbn = "operators_platform";     // database name

            $this->db_credentials = array($sn, $un, $ps, $dbn);

            // Create connection
            $conn = new mysqli($sn, $un, $ps, $dbn);
            $conn->set_charset("utf8");
            $this->connection = $conn;
            $this->conn_activity = "Active";

            // Check that connection returned no error
            if ($conn->connect_errno) {
                $this->conn_activity = "Error Returned";
            }
//        }
    }

    /**
    * Creates the sql query for data selection from a MySQL table. 
    * 
    * @param   string  $search_for             The column name in the database
    * @param   string  $search_from            The db table from where we are selecting data
    * @param   string  $search_addition_where  Optional. Contains the WHERE arguments in sql format
    * @param   string  $search_addition_other  Optional. Is a string that is added at the end of the query string
    * @param   string  $username               Optional. The username of the connected user
    * @param   boolean $assoc_array            Optional. A boolean determining whether the result will be returned in an indexed (FALSE) or associative (TRUE) array
    * @return  array   $rows                   The results of the select query 
    */
    public function select_data($search_for, $search_from, $search_addition_where = NULL, $search_addition_other = NULL, $username = NULL, $assoc_array = FALSE) {

        // Get only entries that are not deleted
        if ($search_from == "user_data") {
            $search_addition_where = $search_addition_where . " AND deleted = 0";
        }

        // Check if we are searching only for a single user's data
        if (!is_null($username)) {
            if (!is_null($search_addition_where)) { // If true, then the where argument is not empty
                $search_addition_where = $search_addition_where . " AND username='" . $username . "'";
            } else {    // Then, the where argument is empty
                $search_addition_where = "username='" . $username . "'";
            }
        }
        
        // Select data from table
        if (is_null($search_addition_where)){
            $search_string = "SELECT " . $search_for . " FROM " . $search_from;
        }
        else {
            $search_string = "SELECT " . $search_for . " FROM " . $search_from . " WHERE " . $search_addition_where . " " . $search_addition_other . ";";
        }
        
        // Send the query
        $result = $this->connection->query($search_string);
        $rows = []; // create a variable to hold the information

        // Print the result
        if ($result->num_rows > 0) {
            if ($assoc_array) {     // If true, an associative array will be returned
                // Each subsequent call to this function will return the next row within the result set, or NULL if there are no more rows.
                while ($row = mysqli_fetch_assoc($result)) {
                    $rows[] = $row;
                }
            } else {                // Else, an indexed array is returned -> Single 
            //columns are returned only as enumerated
                while ($row = mysqli_fetch_row($result)) {
                    if (count($row) == 1) {     // If true, only one column is returned
                        $rows[] = $row[0];      // Add the row in to the results (data) array
                    } else {                    // Else, more than one column is returned
                        $rows[] = $row;
                    }
                }
            }
        } else {
            return NULL;
        }
        mysqli_free_result($result); 
        return $rows;
    }
    
    /**
    * Creates the SQL query for data insertion in a MySQL table
    * 
    * @param string     $table          The db table from where we are inserting data
    * @param string     $columns        The columns name in the database
    * @param string     $insert_values  Optional. Contains the WHERE arguments in sql format
    * @param boolean    $return_id      Optional. A boolean determining whether a 
    *   result will be returned. The result is always the primary key
    * @return array     $result         The results of the insert query or the primary key 
    *   (depending on $return_id value)
    */
    public function insert_data($table, $columns, $insert_values, $return_id = FALSE){
        $insert_string = "INSERT INTO " . $table . " ( " . $columns . " ) " . " VALUES (" . $insert_values . " )";
        // Send the query
        $result = $this->connection->query($insert_string);
        
        // Check if we need the primary key back
        if ($result && $return_id){
            return $this->connection->insert_id;
        } else {
            return $result;
        }
    }
    
        /**
    * Creates the SQL query for editing existing data in a MySQL table
    * 
    * @param string    $table             The db table from where we are updating data
    * @param string    $updated_values    The new values that we want to update our current values with
    * @param string    $username          The username of the logged in user
    * @return array    $result            The results of the update query 
    */
    public function update_data($table, $updated_values, $username){
        $update_string = "UPDATE " . $table . " SET " . $updated_values . " WHERE username = '". $username . "'";
        // Send the query
        $result = $this->connection->query($update_string);
        return $result;
    }
}