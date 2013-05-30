<?php
/* Created by : Lekha
 *  on : 23rd Feb 2012
 *  Class that interacts with the app payments table fitness_app_payment_details
 */
class FitnessAppPaymentDetails extends Zend_Db_Table
{
	
    protected $_name = 'fitness_app_payment_details';
  
    
    
    public function addData($data)
    {
    	global $db;
    	
    	$data = array(
	    'user_id'         => $data['user_id'],
	    'amount'          => $data['amount'],
	    'payment_method'  => $data['payment_method'],
	    'payment_status'  => $data['payment_status'],
	    'unlock_type'     => $data['unlock_type'],
	    'payment_date'    => $data['payment_date']
	    );

    return $db->insert('fitness_app_payment_details', $data);
    	
    }
    
    
        public function checkPaymentStatus($userid,$date)
				   {
				      	   global $db;
					    	
					    	$sql = 'SELECT * FROM fitness_app_payment_details where user_id="'.$userid.'" and payment_date="'.$date.'" order by id DESC limit 0,1';
					
							$result = $db->fetchRow($sql, 2);
							
							return $result;
				   }
				   
				    public function getAllPayments()
				   {
				      	   global $db;
					    	
					    	$sql = 'SELECT * FROM fitness_app_payment_details  order by id DESC ';
					
							$result = $db->fetchAll($sql, 2);
							
							return $result;
				   }
    
   
}