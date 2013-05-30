<?php
/* Created by : Lekha
 *  on : 29th Feb 2012
 *  Class that interacts with the  table fitness_selfmade_workouts
 */
class FitnessSelfmadeWorkouts extends Zend_Db_Table
{
	
    protected $_name = 'fitness_selfmade_workouts';
    
    
       protected $_referenceMap = array(
	    'user' => array(
	    'columns' => array('userid'),
	    'refTableClass' => 'FitnessUserGeneral',
	    'refColumns' => array('user_id'),
	    'onDelete' => self::CASCADE
	));
    
	
	
	public function addData($data)
    {
    	global $db;
    	
    	$data = array(
	    'userid'                     => $data['userid'],
	    'workout_name'               => $data['workout_name'],
	    'collection'                 => $data['collection'],
	    'duration'                   => $data['duration'],
	    'date_created'               => date('Y-m-d')
	          	  );
     
    $db->insert('fitness_selfmade_workouts', $data);
    	
    }
    
    public function getUserWorkoutSelf($userId)
	    {
	    	global $db;
	    	
	    	
	    	$sql = "select * from fitness_selfmade_workouts where userid='".$userId."' and status=1";
	    	$result = $db->fetchAll($sql);
	    	return $result;
	    }
		
		public function getSelfWorkout($userId,$workoutId)
	    {
	    	global $db;
	    	
	    	
	    	$sql = "select * from fitness_selfmade_workouts where userid='".$userId."' and id='".$workoutId."'";
	    	$result = $db->fetchRow($sql);
	    	return $result;
	    }
		
		public function getSelfCount($userId)
		{
			global $db;
	    	
	    	
	    	$sql = "select count(*) as count from fitness_selfmade_workouts where userid='".$userId."' and status=1";
	    	$result = $db->fetchRow($sql);
	    	return $result;
		}
		
		public function getLastSelfId()
			   {
			      	global $db;
				    	
				    	$sql = 'SELECT id FROM fitness_selfmade_workouts order by id DESC limit 0,1';
				
						$result = $db->fetchRow($sql, 2);
						
						return $result;
			   }
		   
}