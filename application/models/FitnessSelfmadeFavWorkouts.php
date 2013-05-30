<?php
/* Created by : Lekha
 *  on : 19th April 2012
 *  Class that interacts with the  table fitness_selfmade_fav_workouts
 */
class FitnessSelfmadeFavWorkouts extends Zend_Db_Table
{
	
    protected $_name = 'fitness_selfmade_fav_workouts';
    
    
    
     public function addData($data)
    {
    	global $db;
    	
    	$data = array(
		'user_id'          => $data['user_id'],
	    'workout_id'       => $data['workout_id'],
		'fav_status'       => $data['status']  
			    );

    $db->insert('fitness_selfmade_fav_workouts', $data);
    	
    }
    
    
    
    
    public function getSelfFavWorkouts($userid)
    {
    	global $db;
    	
    	
    	$sql = "select workout_id from fitness_selfmade_fav_workouts where user_id='".$userid."' and fav_status=1";
    	$result = $db->fetchAll($sql, 2);
    	
    	return $result;
    }
	
	    
    
			   
		   
}