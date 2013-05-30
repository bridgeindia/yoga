<?php
/* Created by : Lekha
 *  on : 23rd Feb 2012
 *  Class that interacts with the workouts table fitness_user_workouts_unlocked
 */
class FitnessUserWorkoutsUnlocked extends Zend_Db_Table
{
	
    protected $_name = 'fitness_user_workouts_unlocked';
    
     protected $_referenceMap = array(
	'workout_id' => array(
	    'columns' => array('workout_id'),
	    'refTableClass' => 'FitnessWorkouts',
	    'refColumns' => array('id'),
	    'onDelete' => self::CASCADE
	),
	
	
	 'user' => array(
	    'columns' => array('user_id'),
	    'refTableClass' => 'FitnessUserGeneral',
	    'refColumns' => array('user_id'),
	    'onDelete' => self::CASCADE
	)
	
	
	);
	
	
	 public function addData($data)
    {
    	global $db;
    	
    	$data = array(
	    'user_id'                          => $data['user_id'],
	    'workout_id'                       => $data['workout_id'],
	    'workout_purchase_status'          => $data['workout_purchase_status'],
	    'unlocked_date'                    => $data['unlocked_date'],
	    'unlocked_status'                  => $data['unlocked_status'],
		'unlock_location'                => $data['unlock_location']
	    	    
	    );

    $db->insert('fitness_user_workouts_unlocked', $data);
    	
    }
	
	
	
	
	public function getUserWorkoutsUnlocked($userId)
	    {
	    	global $db;
	    	
	    	
	    	$sql = "select * from fitness_user_workouts_unlocked where user_id='".$userId."' and unlocked_status=1 order by unlocked_date DESC";
	    	
	    	$result = $db->fetchAll($sql);
	    	return $result;
	    }
	    
	    
	     
			    public function checkRecordExists($userId,$workout_id)
			   {
			      	global $db;
				    	
				    	$sql = 'SELECT count(*) as count FROM fitness_user_workouts_unlocked where user_id="'.$userId.'" and workout_id="'.$workout_id.'" order by unlocked_id DESC limit 0,1';
				
						$result = $db->fetchRow($sql, 2);
						
						return $result;
			   }
			   
			   
			   public function setLockStatus($userId,$lock,$purchased)
			    {
			    	global $db;
			    	
			    	
			    	$sql = "update fitness_user_workouts_unlocked set unlocked_status='".$lock."' where user_id='".$userId."' and workout_id NOT IN ($purchased)";
			    	
			    	$db->query($sql);
			    	
			    }
			    
			    
			    public function checkFreeStatus($userId,$workoutId)
			    {
			    	global $db;
			    	$sql = "select workout_purchase_status from fitness_user_workouts_unlocked where user_id='".$userId."' and workout_id='".$workoutId."'";
			    	$result = $db->fetchRow($sql, 2);
						
				    return $result;
			    }
			    
			     public function getPurchasedWorkouts($userId)
			    {
			    	global $db;
			    	$sql = "select workout_id from fitness_user_workouts_unlocked where user_id='".$userId."' and workout_purchase_status='true'";
			    	$result = $db->fetchAll($sql);
						
				    return $result;
			    }
			    
			    
			    public function getUnlockedByDate($date)
				    {
				    	global $db;
				    	
				    	
				    	$sql = "select user_id from fitness_user_workouts_unlocked where unlocked_date='".$date."' and unlocked_status=1 and unlock_location=1";
				    	
				    	$result = $db->fetchAll($sql);
				    	return $result;
				    }
    
}
?>  
    
    
    