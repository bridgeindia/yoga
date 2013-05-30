<?php
/* Created by : Lekha
 *  on : 23rd Feb 2012
 *  Class that interacts with the workouts table fitness_exercise_documents
 */
class FitnessExerciseRanking extends Zend_Db_Table
{
	
    protected $_name = 'fitness_exercise_ranking';
    
     protected $_referenceMap = array(
	'workout_id' => array(
	    'columns' => array('workout_id'),
	    'refTableClass' => 'FitnessExerciseGeneral',
	    'refColumns' => array('workout_id'),
	    'onDelete' => self::CASCADE
	));
    
    
    public function addData($data)
    {
    	global $db;
    	
    	$data = array(
	    'workout_id'          => $data['workout_id'],
	    'body_area_id'          => $data['body_area_id'],
	    'ranking'          => $data['ranking']
	    );

    $db->insert('fitness_exercise_ranking', $data);
    	
    }
    
    
    public function getRecord($workoutId)
	   {
	   	        global $db;
		    	
		    	$sql = 'SELECT * FROM fitness_exercise_ranking where workout_id="'.$workoutId.'" ';
		
				$result = $db->fetchAll($sql, 2);
				
				return $result;
	   }
   
    
	   public function getCountRecords($workoutId,$areaId)
	   {
	   	        global $db;
		    	
		    	$sql = 'SELECT count(*) as count FROM fitness_exercise_ranking where workout_id="'.$workoutId.'" and body_area_id="'.$areaId.'"';
		
				$result = $db->fetchRow($sql, 2);
				
				return $result;
	   }
	   
	   public function getexerciseByStep1($workoutId,$muscles)
	   {
	   	       global $db;
		    	
				$musclesArray =  explode(",",$muscles);
				$count        = count($musclesArray);
		    	
		$sql ='SELECT * FROM fitness_exercise_ranking where workout_id="'.$workoutId.'" and `ranking` IN (5, 6) AND `body_area_id` IN ('.$muscles.') GROUP BY workout_id HAVING COUNT(`workout_id`) = '.$count;
		  
				
				
				$result = $db->fetchAll($sql, 2);
				
				return $result;
	   }
	   
	   public function getexerciseByStep2($workoutId,$muscle,$rank)
	   {
		   	global $db;
			
			$sql = 'SELECT * FROM fitness_exercise_ranking where workout_id="'.$workoutId.'" and body_area_id="'.$muscle.'" and ranking="'.$rank.'"';
			$result = $db->fetchRow($sql, 2);
					
		    return $result;
	   }
	   
	    public function getRankingById($workoutId,$areaId)
	   {
	   	        global $db;
		    	
		    	$sql = 'SELECT ranking  FROM fitness_exercise_ranking where workout_id="'.$workoutId.'" and body_area_id="'.$areaId.'"';
		
				$result = $db->fetchRow($sql, 2);
				
				return $result;
	   }
	   
	   
    public function getByRank($focus)
	   {
	   	        global $db;
		    	
		    	$sql = 'SELECT workout_id FROM fitness_exercise_ranking where body_area_id="'.$focus.'" and ranking=6';
		
				$result = $db->fetchAll($sql, 2);
				
				return $result;
	   }
}