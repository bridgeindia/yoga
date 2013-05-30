<?php
/* Created by : Lekha
 *  on : 23rd Feb 2012
 *  Class that interacts with the workouts table fitness_workout_timeframes
 */
class FitnessWorkoutTimeframes extends Zend_Db_Table
{
	
    protected $_name = 'fitness_workout_timeframes';
    
    
    public function addData($data)
    {
    	global $db;
    	
    	$data = array(
	    'timeframe'          => $data['user_name']
	    );

    $db->insert('fitness_workout_timeframes', $data);
    	
    }
    
    
    
     public function listTimeframes()
	    {
	    	global $db;
	    	
	    	$sql = 'SELECT * FROM fitness_workout_timeframes  order by timeframe_id ASC';
	
			$result = $db->fetchAll($sql, 2);
			
			return $result;
	    }
	    
	    
	    public function getTimeId($time)
	    {
	    	global $db;
	    	
	    	$sql = 'SELECT timeframe_id FROM fitness_workout_timeframes where timeframe="'.$time.'" order by timeframe_id ASC';
	
			$result = $db->fetchRow($sql, 2);
			
			return $result;
	    }
   
}