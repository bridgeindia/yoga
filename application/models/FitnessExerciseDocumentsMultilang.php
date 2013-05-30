<?php
/* Created by : Lekha
 *  on : 23rd Feb 2012
 *  Class that interacts with the workouts table fitness_exercise_documents_multilang
 */
class FitnessExerciseDocumentsMultilang extends Zend_Db_Table
{
	
    protected $_name = 'fitness_exercise_documents_multilang';
    
   
    
    
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
	    'document_id'          => $data['document_id'],
	    'workout_id'              => $data['workout_id'],
	    'lang_id'              => $data['lang_id'],
	    'workout_youtube_link' => $data['youtube_link'],
	    'workout_sound_file'   => $data['sound_file'],
	    );

    $db->insert('fitness_exercise_documents_multilang', $data);
    	
    }
    
     public function getRecord($workoutId,$lang=1)
	   {
	   	        global $db;
		    	
		    	$sql = 'SELECT * FROM fitness_exercise_documents_multilang where workout_id="'.$workoutId.'" and lang_id="'.$lang.'"';
		
				$result = $db->fetchRow($sql, 2);
				
				return $result;
	   }
    
	   
	   public function getLangRecord($workoutId,$langId)
		   {
		   	 
			    	global $db;
			    	
			    	$sql = 'SELECT count(*) as count FROM fitness_exercise_documents_multilang where lang_id="'.$langId.'" and workout_id="'.$workoutId.'" order by id ASC';
			
					$result = $db->fetchRow($sql, 2);
					
					return $result;
			    
		   }
		   
		   
		  
    
    
}