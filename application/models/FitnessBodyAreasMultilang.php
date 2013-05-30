<?php
/* Created by : Lekha
 *  on : 23rd Feb 2012
 *  Class that interacts with the workouts table fitness_body_areas
 */
class FitnessBodyAreasMultilang extends Zend_Db_Table
{
	
    protected $_name = 'fitness_body_areas_multilang';
    
    protected $_referenceMap = array(
	'muscle' => array(
	    'columns' => array('bodyarea_id'),
	    'refTableClass' => 'FitnessBodyAreas',
	    'refColumns' => array('area_id'),
	    'onDelete' => self::CASCADE
	));
    
    
    public function addData($data)
    {
    	global $db;
    	
    	$data = array(
	    'bodyarea_id'          => $data['bodyarea_id'],
	    'lang_id'              => $data['lang_id'],
	    'area_name'            => $data['area_name']
	    );

    $db->insert('fitness_body_areas_multilang', $data);
    	
    }
    
    
    
     public function listMuscles($lang=1)
	    {
	    	global $db;
	    	
	    	$sql = 'SELECT * FROM fitness_body_areas_multilang where lang_id="'.$lang.'" order by id ASC';
	
			$result = $db->fetchAll($sql, 2);
			
			return $result;
	    }
	    
	    
	    public function getMuscles($bodyAreaId,$lang=1)
	    {
	    	global $db;
	    	$sql = 'SELECT bodyarea_id,area_name FROM fitness_body_areas_multilang where lang_id="'.$lang.'" and bodyarea_id="'.$bodyAreaId.'" order by id ASC';
	
			$result = $db->fetchRow($sql, 2);
			
			return $result;
	    }
	    
	    public function getLangRecord($bodyAreaId,$langId)
		   {
		   	 
			    	global $db;
			    	
			    	$sql = 'SELECT count(*) as count FROM fitness_body_areas_multilang where lang_id="'.$langId.'" and bodyarea_id="'.$bodyAreaId.'" order by id ASC';
			        
					$result = $db->fetchRow($sql, 2);
					
					return $result;
			    
		   }
		   
		    public function listMuscleIds($lang=1)
			    {
			    	global $db;
			    	
			    	$sql = 'SELECT bodyarea_id FROM fitness_body_areas_multilang where lang_id="'.$lang.'" order by id ASC';
			
					$result = $db->fetchAll($sql, 2);
					
					return $result;
			    }
	    
	    
   
}