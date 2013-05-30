<?php
/* Created by : Lekha
 *  on : 29th Feb 2012
 *  Class that interacts with the  table fitness_user_settings
 */
class FitnessUserSettings extends Zend_Db_Table
{
	
    protected $_name = 'fitness_user_settings';
    
    
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
	    'user_id'              => $data['user_id'],
	    'address1'             => $data['address1'],
		'address2'             => $data['address2'],
		'city'                 => $data['city'],
	    'country'              => $data['country'],
	    'zipcode'              => $data['zipcode'],
	    'telephone'            => $data['telephone'],
	    'workout_targets'      => $data['workout_targets'],
	    'workout_interests'    => $data['workout_interests'],
	    'member_fitnessclub'   => $data['member_fitnessclub'],
	    'subscriptions'        => $data['subscriptions'],
		'offers'               => $data['offers'],
		'updates'              => $data['updates'],
		'dnb'                  => $data['dnb'],
	    'payment_method'       => $data['payment_method'],
	    'address_check'        => $data['address_check']
	    );

    $db->insert('fitness_user_settings', $data);
    	
    }
	
    
	 public function getUserSettings($userId)
	    {
	    	global $db;
	    	
	    	
	    	$sql = "select * from fitness_user_settings where user_id='".$userId."' order by settings_id ASC";
	    	
	    	$result = $db->fetchRow($sql, 2);
	    	return $result;
	    }
	    
	    
	    
	    public function getCount($userId)
	    {
	    	global $db;
	    	
	    	
	    	$sql = "select count(*) as count from fitness_user_settings where user_id='".$userId."' order by settings_id ASC";
	    	
	    	$result = $db->fetchRow($sql, 2);
	    	return $result;
	    }
		
		public function getUsersByField($field)
	    {
	    	global $db;
	    	
	    	
	    	$sql = "select user_id from fitness_user_settings where $field=1 order by settings_id ASC";
	    	
	    	$result = $db->fetchAll($sql, 2);
	    	return $result;
	    }
		   
}