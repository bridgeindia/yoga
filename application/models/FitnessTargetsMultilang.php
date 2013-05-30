<?php
/* Created by : Lekha
 *  on : 29 Feb 2012
 *  Class that interacts with the workouts table fitness_targets_multilang
 */
class FitnessTargetsMultilang extends Zend_Db_Table
{
	
    protected $_name = 'fitness_targets_multilang';
    
     protected $_referenceMap = array(
	'targets' => array(
	    'columns' => array('target_id'),
	    'refTableClass' => 'FitnessTargets',
	    'refColumns' => array('target_id'),
	    'onDelete' => self::CASCADE
	));
    
    
    public function addData($data)
    {
    	global $db;
    	
    	$data = array(
	    'target_id'              => $data['target_id'],
	    'lang_id'                => $data['lang_id'],
	    'target_name'          => $data['target_name']
	    );

    $db->insert('fitness_targets_multilang', $data);
    	
    }
    
    
    
   
	    
	    
	    public function getTargets($targetId,$lang=1)
	    {
	    	global $db;
	    	$sql = 'SELECT target_id,target_name FROM fitness_targets_multilang where lang_id="'.$lang.'" and target_id="'.$targetId.'" order by id ASC';
	
			$result = $db->fetchRow($sql, 2);
			
			return $result;
	    }
	    
	    public function getLangRecord($targetId,$langId)
		   {
		   	 
			    	global $db;
			    	
			    	$sql = 'SELECT count(*) as count FROM fitness_targets_multilang where lang_id="'.$langId.'" and target_id="'.$targetId.'" order by id ASC';
			        
					$result = $db->fetchRow($sql, 2);
					
					return $result;
			    
		   }
		   
		    public function getAllTargets($lang=1)
			    {
			    	global $db;
			    	$sql = 'SELECT target_id,target_name FROM fitness_targets_multilang where lang_id="'.$lang.'"  order by id ASC';
			
					$result = $db->fetchAll($sql, 2);
					
					return $result;
			    }
			    
   
}