<?php
/* Created by : Lekha
 *  on : 23rd Feb 2012
 *  Class that interacts with the  table fitness_membership_plans
 */
class FitnessMembershipPlans extends Zend_Db_Table
{
	
    protected $_name = 'fitness_membership_plans';
    protected $_dependentTables = array(
    'FitnessMembershipPlansMultilang'
    );
    
    
    public function addData($data)
    {
    	global $db;
    	
    	$data = array(
	    'membership_offer_period'  => $data['membership_offer_period'],
	    'membership_plan'          => $data['membership_plan'],
	    'membership_rate'          => $data['membership_rate']
	    
	    );

    return $db->insert('fitness_membership_plans', $data);
    	
    }
    
    
    public function getLastMembershipId()
   {
      	global $db;
	    	
	    	$sql = 'SELECT membership_id FROM fitness_membership_plans order by membership_id DESC limit 0,1';
	
			$result = $db->fetchRow($sql, 2);
			
			return $result;
   }
   
   
    public function listPlans()
	    {
	    	global $db;
	    	
	    	$sql = 'SELECT * FROM fitness_membership_plans   order by membership_id ASC';
	
			$result = $db->fetchAll($sql, 2);
			
			return $result;
	    }
	    
    
     public function getPlans($planId,$lang=1)
	    {
	    	global $db;
	    	$sql = 'SELECT * FROM fitness_membership_plans where membership_id="'.$planId.'" ';
	
			$result = $db->fetchRow($sql, 2);
			
			return $result;
	    }
	    
	    
	     public function setStatusPlan($planId,$status)
		    {
		    	 global $db;
		    	 
		    	 
		    	 $sql_update = 'UPDATE fitness_membership_plans set membership_status="'.$status.'" where membership_id="'.$planId.'"';
		    	 $db->query($sql_update);
		    	 
		    }
		   
}