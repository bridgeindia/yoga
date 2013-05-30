<?php
/* Created by : Lekha
 *  on : 19th April 2012
 *  Class that interacts with the  table fitness_promotion_codes
 */
class FitnessPromotionCodes extends Zend_Db_Table
{
	
    protected $_name = 'fitness_promotion_codes';
    
    
    
     public function addData($data)
    {
    	global $db;
    	
    	$data = array(
	    'promotion_code'          => $data['promotion_code'],
		'price_reduction'         => $data['price_reduction'],
		'status'                  => $data['status']
			    );

    $db->insert('fitness_promotion_codes', $data);
    	
    }
    
    
    
    
    public function getCodes()
    {
    	global $db;
    	
    	
    	$sql = "select * from fitness_promotion_codes";
    	$result = $db->fetchAll($sql, 2);
    	
    	return $result;
    }
    
	
	
	public function getCodeById($promotioncode)
    {
    	global $db;
    	
    	
    	$sql = "select * from fitness_promotion_codes where id='".$promotioncode."'";
    	$result = $db->fetchRow($sql, 2);
    	
    	return $result;
    }
	
	public function getpromoByCode($promotioncode)
    {
    	global $db;
    	
    	
    	$sql = "select * from fitness_promotion_codes where promotion_code='".$promotioncode."'";
    	$result = $db->fetchRow($sql, 2);
    	
    	return $result;
    }
	
	public function checkStatus($promotioncode)
	{
		global $db;
		
		$sql = "select * from fitness_promotion_codes where promotion_code='".$promotioncode."'";
    	$result = $db->fetchRow($sql, 2);
    	
    	return $result;
		
	}
    
    
			   
		   
}