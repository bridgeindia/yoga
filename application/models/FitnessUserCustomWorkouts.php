<?php
/* Created by : Lekha
 *  on : 29th Feb 2012
 *  Class that interacts with the  table fitness_user_custom_workouts
 */
class FitnessUserCustomWorkouts extends Zend_Db_Table
{
	
    protected $_name = 'fitness_user_custom_workouts';
    
    
       protected $_referenceMap = array(
	    'user' => array(
	    'columns' => array('user_id'),
	    'refTableClass' => 'FitnessUserGeneral',
	    'refColumns' => array('user_id'),
	    'onDelete' => self::CASCADE
	));
    
	
	
	public function addData($data)
    {
    	global $db;
    	
    	$data = array(
	    'user_id'                    => $data['user_id'],
	    'custom_workout_name'        => $data['custom_workout_name'],
	    'custom_workout_collection'  => $data['custom_workout_collection'],
	    'total_workout_time'         => $data['total_workout_time'],
		'workout_focus'              => $data['workout_focus'],
		'workout_equipment'          => $data['workout_equipment'],
	    'date_created'               => date('Y-m-d'),
	    'custom_status'              => 1
	       	    
	    );
     
    $db->insert('fitness_user_custom_workouts', $data);
    
    }
    
    public function getUserWorkoutCustom($userId)
	    {
	    	global $db;
	    	
	    	
	    	$sql = "select * from fitness_user_custom_workouts where user_id='".$userId."' and custom_status =1 order by custom_workout_id DESC";
	    	$result = $db->fetchAll($sql);
	    	return $result;
	    }
		
		public function getCustomWorkout($userId,$workoutId)
	    {
	    	global $db;
	    	
	    	
	    	$sql = "select * from fitness_user_custom_workouts where user_id='".$userId."' and custom_workout_id='".$workoutId."' and custom_status =1";
	    	$result = $db->fetchRow($sql);
	    	return $result;
	    }
		
		public function getCustomCount($userId)
		{
			global $db;
	    	
	    	
	    	$sql = "select count(*) as count from fitness_user_custom_workouts where user_id='".$userId."' and custom_status =1";
	    	$result = $db->fetchRow($sql);
	    	return $result;
		}
		
		public function getLastCustomId()
			   {
			      	global $db;
				    	
				    	$sql = 'SELECT custom_workout_id FROM fitness_user_custom_workouts order by custom_workout_id DESC limit 0,1';
				
						$result = $db->fetchRow($sql, 2);
						
						return $result;
			   }
		   
}