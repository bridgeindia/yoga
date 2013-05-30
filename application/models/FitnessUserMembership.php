<?php
/* Created by : Lekha
 *  on : 29th Feb 2012
 *  Class that interacts with the  table fitness_user_membership
 */
class FitnessUserMembership extends Zend_Db_Table
{
	
    protected $_name = 'fitness_user_membership';
    
    
       protected $_referenceMap = array(
	    'user' => array(
	    'columns' => array('user_id'),
	    'refTableClass' => 'FitnessUserGeneral',
	    'refColumns' => array('user_id'),
	    'onDelete' => self::CASCADE
	));
    
	
	
	public function addData($data)
	    {
	    	global $db;
	    	
	    	$data = array(
		    'user_id'                   => $data['user_id'],
		    'user_status'               => $data['user_status'],
		    'trial'                     => $data['trial'],
		    'trial_period'              => $data['trial_period'],
		    'membership_plan'           => $data['membership_plan'],
		    'membership_validity_date'  => $data['membership_validity_date'],
		    'registration_date'         => $data['registration_date'],
		    'upgrade_date'              => $data['upgrade_date']
		    
		);
	
	    return  $db->insert('fitness_user_membership', $data);
    	
    }

    
	
	 public function getUserMembership($userId)
	    {
	    	global $db;
	    	
	    	
	    	$sql = "select * from fitness_user_membership where user_id='".$userId."'";
	    	$result = $db->fetchRow($sql, 2);
	    	return $result;
	    }
    
		   
	     public function getAllMemberIds()
		    {
		    	global $db;
		    	
		    	
		    	$sql = "select user_id from fitness_user_membership where trial=0 and membership_plan!=0";
		    	$result = $db->fetchAll($sql, 2);
		    	return $result;
		    }
}