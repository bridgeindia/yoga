<?php
/* Created by : Lekha
 *  on : 23rd Feb 2012
 *  Class that interacts with the workouts table fitness_equipments_multilang
 */
class FitnessEquipmentsMultilang extends Zend_Db_Table
{
	
    protected $_name = 'fitness_equipments_multilang';
    
     protected $_referenceMap = array(
	'equipment' => array(
	    'columns' => array('equipment_id'),
	    'refTableClass' => 'FitnessEquipments',
	    'refColumns' => array('eqp_home_id'),
	    'onDelete' => self::CASCADE
	));
    
    
    public function addData($data)
    {
    	global $db;
    	
    	$data = array(
	    'equipment_id'            => $data['equipment_id'],
	    'lang_id'                 => $data['lang_id'],
	    'equipment_name'          => $data['equipment_name']
	    );

    $db->insert('fitness_equipments_multilang', $data);
    	
    }
    
    
    
     public function listEquipments($lang=1)
	    {
	    	global $db;
	    	
	    	$sql = 'SELECT * FROM fitness_equipments_multilang where lang_id="'.$lang.'" order by id ASC';
	
			$result = $db->fetchAll($sql, 2);
			
			return $result;
	    }
	    
	    
	    public function getEquipments($equipId,$lang=1)
	    {
	    	global $db;
	    	$sql = 'SELECT equipment_id,equipment_name FROM fitness_equipments_multilang where lang_id="'.$lang.'" and equipment_id="'.$equipId.'" order by id ASC';
	
			$result = $db->fetchRow($sql, 2);
			
			return $result;
	    }
	    
	    public function getLangRecord($equipId,$langId)
		   {
		   	 
			    	global $db;
			    	
			    	$sql = 'SELECT count(*) as count FROM fitness_equipments_multilang where lang_id="'.$langId.'" and equipment_id="'.$equipId.'" order by id ASC';
			        
					$result = $db->fetchRow($sql, 2);
					
					return $result;
			    
		   }
	    
		
		 public function getEquipmentById($equipId,$lang)
	    {
	    	global $db;
	    	$sql = 'SELECT equipment_name FROM fitness_equipments_multilang where lang_id="'.$lang.'" and equipment_id="'.$equipId.'" ';
	
			$result = $db->fetchRow($sql, 2);
			
			return $result;
	    }
	    
   
}