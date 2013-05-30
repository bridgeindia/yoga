<?php
/* Created by : Lekha
 *  on : 23rd Feb 2012
 *  Class that interacts with the workouts table fitness_android_key
 */
class FitnessAndroidKey extends Zend_Db_Table
{
	
	
	
	    
	   
	    
       public function getAllKeys()
	    {
	    	global $db;
	    	
	    	$sql = 'SELECT android_key FROM fitness_android_key';
			
		    $result = $db->fetchAll($sql, 2);
					
		    return $result;
	    }
		
		 public function getKeyByUser($userid)
	    {
	    	global $db;
	    	
	    	$sql = 'SELECT android_key FROM fitness_android_key where user_id="'.$userid.'"';
			
		    $result = $db->fetchRow($sql, 2);
					
		    return $result;
	    }
}
?>