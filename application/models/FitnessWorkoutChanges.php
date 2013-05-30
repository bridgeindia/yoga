<?php
/* Created by : Lekha
 *  on : 23rd Feb 2012
 *  Class that interacts with the workouts table fitness_workout_changes
 */
class FitnessWorkoutChanges extends Zend_Db_Table
{
	
	
	 public function addData($data)
	    {
	    	global $db;
	    	
	    	
	    	$data = array(
	    	'status'          => $data['status'],
		    'change_date'        => $data['change_date']
		   		    
		    );
	
	    $db->insert('fitness_workout_changes', $data);
	    	
	    }
	    
	    
	    public function getRecordByDate($date)
	    {
	    	global $db;
	    	
	    	$sql = 'SELECT count(*)  as count FROM fitness_workout_changes where change_date="'.$date.'"';
			
		    $result = $db->fetchRow($sql, 2);
					
		    return $result;
	    }
	  
}
?>