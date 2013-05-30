<?php
/* Created by : Lekha
 *  on : 23rd Feb 2012
 *  Class that interacts with the workouts table fitness_body_areas
 */
class FitnessMembershipPlansMultilang extends Zend_Db_Table
{
	
    protected $_name = 'fitness_membership_plans_multilang';
    
    protected $_referenceMap = array(
	'membership' => array(
	    'columns' => array('membership_id'),
	    'refTableClass' => 'FitnessMembershipPlans',
	    'refColumns' => array('membership_id'),
	    'onDelete' => self::CASCADE
	));
    
    
    public function addData($data)
    {
    	global $db;
    	
    	$data = array(
	    'membership_id'               => $data['membership_id'],
	    'lang_id'                     => $data['lang_id'],
	    'membership_plan'             => $data['membership_plan'],
	    'membership_description'      => $data['membership_description']
	    );

    $db->insert('fitness_membership_plans_multilang', $data);
    	
    }
    
    
    
     public function listPlans($lang=1)
	    {
	    	global $db;
	    	
	    	$sql = 'SELECT * FROM fitness_membership_plans_multilang where lang_id="'.$lang.'" order by id ASC';
	
			$result = $db->fetchAll($sql, 2);
			
			return $result;
	    }
	    
	    
	    public function getPlans($planId,$lang=1)
	    {
	    	global $db;
	    	$sql = 'SELECT membership_id,membership_plan,membership_description FROM fitness_membership_plans_multilang where lang_id="'.$lang.'" and membership_id="'.$planId.'" order by id ASC';
	
			$result = $db->fetchRow($sql, 2);
			
			return $result;
	    }
	    
	    public function getLangRecord($planId,$langId)
		   {
		   	 
			    	global $db;
			    	
			    	$sql = 'SELECT count(*) as count FROM fitness_membership_plans_multilang where lang_id="'.$langId.'" and membership_id="'.$planId.'" order by id ASC';
			        
					$result = $db->fetchRow($sql, 2);
					
					return $result;
			    
		   }
	    
   
}