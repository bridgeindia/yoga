<?php
/* Created by : Lekha
 *  on : 23rd Feb 2012
 *  Class that interacts with the workouts table fitness_body_areas
 */
class FitnessWorkoutsMultilang extends Zend_Db_Table
{
	
    protected $_name = 'fitness_workouts_multilang';
    
    protected $_referenceMap = array(
	'works' => array(
	    'columns' => array('work_id'),
	    'refTableClass' => 'FitnessWorkouts',
	    'refColumns' => array('id'),
	    'onDelete' => self::CASCADE
	));
    
    
    public function addData($data)
    {
    	global $db;
    	
    	$data = array(
	    'work_id'          => $data['work_id'],
	    'lang_id'          => $data['lang_id'],
	    'work_name'        => $data['work_name'],
    	'description_small'  => $data['description_small'],
	   	'description_big'    => $data['description_big']
	    );

    $db->insert('fitness_workouts_multilang', $data);
    	
    }
    
    
    
	    public function getWorks($workId,$lang=1)
	    {
	    	global $db;
	    	$sql = 'SELECT * FROM fitness_workouts_multilang where lang_id="'.$lang.'" and work_id="'.$workId.'" order by id ASC';
	
			$result = $db->fetchRow($sql, 2);
			
			return $result;
	    }
	    
	    public function getLangRecord($workId,$langId)
		   {
		   	 
			    	global $db;
			    	
			    	$sql = 'SELECT count(*) as count FROM fitness_workouts_multilang where lang_id="'.$langId.'" and work_id="'.$workId.'" order by id ASC';
			        
					$result = $db->fetchRow($sql, 2);
					
					return $result;
			    
		   }
		   
		   public function getAllRecords($langId)
		   {
		   	 
			    	global $db;
			    	
			    	$sql = 'SELECT * FROM fitness_workouts_multilang where lang_id="'.$langId.'"  order by id ASC';
			        
					$result = $db->fetchAll($sql, 2);
					
					return $result;
			    
		   }
	    
   
}