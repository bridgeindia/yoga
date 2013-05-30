<?php
/* Created by : Lekha
 *  on : 19th April 2012
 *  Class that interacts with the  table fitness_promotion_users
 */
class FitnessPromotionUsers extends Zend_Db_Table
{
	
    protected $_name = 'fitness_promotion_users';
    
    
    
     public function addData($data)
    {
    	global $db;
    	
    	$data = array(
		'promotion_id'            => $data['promotion_id'],
	    'promotion_code'          => $data['promotion_code'],
		'user_id'                 => $data['user_id']
			    );

    $db->insert('fitness_promotion_users', $data);
    	
    }
    
    
    
    
    public function checkUser($user_id,$promo)
    {
    	global $db;
    	
    	
    	$sql = "select count(*) as count from fitness_promotion_users where user_id='".$user_id."' and promotion_code='".$promo."'";
    	$result = $db->fetchRow($sql, 2);
    	
    	return $result;
    }
    
    
    public function getUsersByCode($promotion)
    {
    	global $db;
    	
    	
    	$sql = "select count(*) as count  from fitness_promotion_users where promotion_id='".$promotion."'";
    	$result = $db->fetchAll($sql, 2);
    	
    	return $result;
    }
	
	
	public function getUserCount($promotion)
    {
    	global $db;
    	
    	
    	$sql = "select count(*) as count from fitness_promotion_users where promotion_id='".$promotion."'";
    	$result = $db->fetchRow($sql, 2);
    	
    	return $result;
    }
	
	
	public function getPromotionUsers()
    {
    	global $db;
    	
    	
    	$sql = "select DISTINCT user_id from fitness_promotion_users ";
    	$result = $db->fetchAll($sql, 2);
    	
    	return $result;
    }		   
		   
}