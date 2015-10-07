<?php
/**
 * Database Utility is used to handle all interactions with the database.  
 */

class DatabaseUtil {
	private $dbconn; //hold the db connection - should not be accessable outside of this object
	
	/**
	 * the construct will connect to the database using the connection parameters given in the server_config.php
	 * @author Nick Ruffilo
	 */
	public function __construct() {
		global $config;
		$this->dbconn = mysql_pconnect($config['db_server'],$config['db_user'],$config['db_pass'])
        	or die("could not connect: " . mysql_error());
        mysql_select_db($config['db_name']) or die("could not select DB");
	}

	/**
	 * Query will run a query against the database.  It will run any type of query
	 *
	 * @param STRING $sql The SQL that you want run
	 * @return ARRAY $results An array of resulting rows from your query.  If they query was an insert or update, it will return the row inserted id or the # of rows effected
	 */
	public function query($sql) {
		//global $ANALYZE_SQL;
		$trimmed_sql = trim($sql);
		$res = mysql_query($trimmed_sql) or die("failed: ($trimmed_sql) " . mysql_error());
		
		if ((stripos($trimmed_sql, 'insert') === 0) || (stripos($trimmed_sql, 'replace') === 0)) {
			return mysql_insert_id();
		}
		
		if (stripos($trimmed_sql, "update") === 0 || stripos($trimmed_sql, "delete") === 0) {
			return true;
		}
		
		$return = array();
		if (count($res)>0) {
			while ($item = mysql_fetch_array($res, MYSQL_ASSOC)) {
				$ret_item = array();
    		    foreach ($item as $key=>$val) {
    		    	$ret_item[$key]=$val;
    		    }
                $return[] = $ret_item;
    		    //$return[] = $item;
			}
		}
		return $return;
		
	}
	
	/**
	 * Query will run a query against the database and return ONE result (the first).  It will run any type of query
	 *
	 * @param STRING $sql The SQL that you want run
	 * @return ARRAY $results An array of resulting rows from your query.  If they query was an insert or update, it will return the row inserted id or the # of rows effected
	 */
	public function query_one($sql) {
		$result = $this->query($sql);
		//$line = mysql_fetch_array($result, MYSQL_ASSOC);
		if (isset($result[0])) {
			return $result[0];
		} else {
			return array();
		}
	}
	
	public function parseForIn($array, $elements_are_strings = false) {
		if(!is_array($array)) return false;
		$string = "(";
		foreach ($array as $elem) {
			if(!$elements_are_strings) $string .= $elem.", ";
			else $string .= "'".$elem."', ";
		}
		$string = substr_replace($string,'',-2);
		$string .= ')';
		return $string; 
	}
	
	/**
	 * This is for when there are multiple queries being ran, this will free up memory
	 *
	 */
	public function clearDebugOutput() {
		$this->dbconn->debug_output = '';
	}
	
}
	
?>