<?php
/* Created by : Lekha
 *  on : 29rd Feb 2012
 *  Class that interacts with the  table fitness_targets
 */
class FitnessTargets extends Zend_Db_Table
{
	
    protected $_name = 'fitness_targets';
    protected $_dependentTables = array(
    'FitnessTargetsMultilang'
    );
    
    
    public function addData($data)
    {
    	global $db;
    	
    	$data = array(
	    'target_name'          => $data['target_name']
	    );

    $db->insert('fitness_targets', $data);
    	
    }
    
    
     
    public function listTargets()
	    {
	    	global $db;
	    	
	    	$sql = 'SELECT * FROM fitness_targets  order by target_id ASC';
	
			$result = $db->fetchAll($sql, 2);
			
			return $result;
	    }
	    
    
	    
	     public function getLastTargetId()
		   {
		      	   global $db;
			    	
			    	$sql = 'SELECT target_id FROM fitness_targets order by target_id DESC limit 0,1';
			
					$result = $db->fetchRow($sql, 2);
					
					return $result;
		   }
		   
		   public function getTarget($targetId)
		   {
		      	   global $db;
			    	
			    	$sql = 'SELECT * FROM fitness_targets where target_id="'.$targetId.'"';
			
					$result = $db->fetchRow($sql, 2);
					
					return $result;
		   }
   
}