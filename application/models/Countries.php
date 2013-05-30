<?php
/* Created by : Lekha
 *  on : 22rd May 2012
 *  Class that interacts with the workouts table countries
 */
class Countries extends Zend_Db_Table
{
	
	
	    
	  
       public function getAllCountries()
	    {
	    	global $db;
	    	
	    	$sql = 'SELECT * FROM countries';
			
		    $result = $db->fetchAll($sql, 2);
					
		    return $result;
	    }
		
}
?>