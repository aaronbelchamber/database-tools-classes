<?php
//  namespace library\database\fns;

class db_data{
	
	
	public function get_data($query,$connObj)
    	{
        	if (!$connObj){
				
				//TODO:  add condition to connect to db
				echo 'Not connected to database.';	
				return false;
			}
			
			
			// Multi array results as ASSOCIATIVE ARRAYS!
			$result_arr=array();
			
			if($stmt = $connObj->query($query)){
				
				while ($row = $stmt->fetch_assoc()) {
						array_push($result_arr,$row);
				    }
								
				return $result_arr;
								
			}
			else
				{
					//	return 'Error connecting to database.';
					return false;
				}
			
    	}
	
	
	public function put_data($table='',$data_row_assoc='',$update_type='I',$rec_id_arr='',$connObj){
		
		/**	$update_type	I - Insert, U - Update
		  *	
		  *		$rec_id_arr		ASSOC array   -- field_name and value of table INDEX
		  *
		  **/
		
		if (!$connObj){
			
			echo "<p>No database connection established, cannot continue.</p>";
			return false;	
		}
		
		if ($table==''){
			
			echo "<p>No database table name provided, cannot continue.</p>";
			return false;
		}
		
		$update_type=strtoupper(trim($update_type));
						
		
		if ($update_type=="U"){
			
			if ($rec_id_arr){
		
				$rec_id_field=key($rec_id_arr);
				
				if ($rec_id_field==''){ $rec_id_field='id';}
				$rec_id_val=$rec_id_arr[$rec_id_field];
			}

			if ($rec_id_val==''){
		
				echo "<p>Invalid record update -- must have a valid Record ID to reference for update.</p>";		
				return false;
			}			

			if ($rec_id_field==''){
				
				echo "<p>Invalid record update -- must have a valid Record Field Name to reference for update.</p>";		
				return false;
			}
			
			$query="UPDATE $table SET $set_vals WHERE $rec_id_field=$rec_id_val";
			//  echo "<p>DEBUG ".__FILE__.__LINE__."$query</p>";
			return;
		}
		

		// Assume this is an INSERT
		foreach($data_row_assoc as $key=>$val){
			
			$fields_arr[]=$key;
			if ($val!=''){
				$val=	addslashes(	stripslashes($val)	);
			}
			
			$vals_arr[]="'".$val."'";	
		}
		
		
		$fields=implode(",",$fields_arr);
		$vals=implode(",",$vals_arr);
		
		$query="INSERT INTO $table ($fields) VALUES ($vals)";


		if (DEBUG) {
						ini_set('display_errors',true); error_reporting(E_ALL);
						echo "<p>DEBUG: ".__FILE__.__LINE__."$query</p>";
		}


		//  	return;
		
		$stmt = $connObj->query($query);
		
		if ($stmt){
					// On successful insert, return the ID generated
					//  echo "...SUCCESS";
					return $connObj->insert_id;
		}
		else {
				//echo "...FAILED";
				return false;	
		}
		
	}
		
		
	//	This shows data from a single or multi-dim array in different formats
	public function show_data($arr,$format="delimited",$delimiter=', '){
		
		$out='';
		$format=strtolower(trim($format));
		
		//	@format:	string			"delimited,table,div"
		//				="table"		standard html table
		//				="delimited"	output fields delimited by $delimiter, \r\n line break for ea rec
		//				="div"			output divs, any $delimiter make class="$delimiter" for divs

		
		//	Output delimited list of multi arrays
		if($format=="delimited"){
			
		foreach($arr as $rec){		
				$out.=implode($delimiter,$rec)."\r\n";	
			}
		
		}
		
		
		//Output DB results to html table
		if($format=="table"){
			$class='';
			
			if ($delimiter!=", "){
				$class="class='$delimiter'";
			}
			
			$out= "<table class='data_table'>";
			foreach($arr as $rec){
				
				$out.="<tr>";
				foreach($rec as $cell){
					$out.="<td>$cell</td>";
				}
				
				$out.="</tr>";	
			}

			$out.='</table>';
		}
		
	return $out;
	
	}
		
}


?>