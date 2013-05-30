<?php
/* Created by : Lekha
 *  on : 19th April 2012
 *  Class that interacts with the  table fitness_admin_accounts
 */
class FitnessAdminAccounts extends Zend_Db_Table
{
	
    protected $_name = 'fitness_admin_accounts';
    
    
    
    
    
    
    
    public function getTypeByUsername($username)
    {
    	global $db;
    	
    	
    	$sql = "select admin_user_type from fitness_admin_accounts where admin_username='".$username."'";
    	$result = $db->fetchRow($sql, 2);
    	
    	return $result;
    }
    
    
    
			   
		   
}