<?php

class db_fns{
	
	public $conn;
	
	public function db_conn($db_name='',$db_host='',$db_user='',$db_pass=''){
				
		if ($db_name=="") {$db_name=DB_NAME;}
		if ($db_host=="") {$db_host=DB_HOST;}
		if ($db_user=="") {$db_user=DB_USER;}
		if ($db_pass=="") {$db_pass=DB_PASS;}
				
		//  echo "CONNECTING: ($db_name,$db_host,$db_user,$db_pass)<br/>"; //	exit;
		
		$this->conn = new mysqli($db_host,$db_user,$db_pass,$db_name)
			or die('Cannot connect to server at moment, please try later.');
		
		return $this->conn;
	}
	
}

?>