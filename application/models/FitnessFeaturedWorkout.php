<?php
/* Created by : Lekha
 *  on : 19th April 2012
 *  Class that interacts with the  table fitness_featured_workout
 */
class FitnessFeaturedWorkout extends Zend_Db_Table
{
	
    protected $_name = 'fitness_featured_workout';
    
    
    
     public function addData($data)
    {
    	global $db;
    	
    	$data = array(
		'id'                  => 1,
	    'workout_id'          => $data['workout_id'],
		'featured_date'       => date('Y-m-d'),
		'workout_level'       => $data['workout_level']
			    );

    $db->insert('fitness_featured_workout', $data);
    	
    }
    
    
    
    
    public function getFeaturedImage()
    {
    	global $db;
    	
    	
    	$sql = "select * from fitness_featured_workout";
    	$result = $db->fetchRow($sql, 2);
    	
    	return $result;
    }
	
	 public function getFeaturedImageByDate($date)
    {
    	global $db;
    	
    	
    	$sql = "select * from fitness_featured_workout where featured_date='".$date."'";
    	$result = $db->fetchRow($sql, 2);
    	
    	return $result;
    }
    
	 public function deleteData()
    {
    	global $db;
    	
    	
    	$sql = "TRUNCATE table fitness_featured_workout";
    	$db->query($sql);
    }
	
	
    
    
			   
		   
}