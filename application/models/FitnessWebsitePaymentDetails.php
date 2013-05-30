<?php
/* Created by : Lekha
 *  on : 29th Feb 2012
 *  Class that interacts with the  table fitness_user_general
 */
class FitnessWebsitePaymentDetails extends Zend_Db_Table
{
	
    protected $_name = 'fitness_website_payment_details';
   
    
    
   
   
	 
			    public function checkPaymentStatus($userid,$date)
				   {
				      	   global $db;
					    	
					    	$sql = 'SELECT * FROM fitness_website_payment_details where user_id="'.$userid.'" and payment_date="'.$date.'" order by id DESC limit 0,1';
					
							$result = $db->fetchRow($sql, 2);
							
							return $result;
				   }
				   
				    public function checkGooglePaymentStatus($userid,$date)
				   {
				      	   global $db;
					    	
					    	$sql = 'SELECT * FROM fitness_website_payment_details where user_id="'.$userid.'"  and payment_method=2 order by id DESC limit 0,1';
					        
							$result = $db->fetchRow($sql, 2);
							
							return $result;
				   }
				   
				    public function checkRecurringStatus($userid,$date)
				   {
				      	   global $db;
					    	
					    	$sql = 'SELECT * FROM fitness_website_payment_details where user_id="'.$userid.'"  and payment_type_workouts="subscr_payment" order by id DESC limit 0,1';
					
							$result = $db->fetchRow($sql, 2);
							
							return $result;
				   }
				    public function getAllPayments()
				   {
				      	   global $db;
					    	
					    	$sql = 'SELECT * FROM fitness_website_payment_details  order by id DESC ';
					
							$result = $db->fetchAll($sql, 2);
							
							return $result;
				   }
		   
}