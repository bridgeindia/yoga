<?php
/* Created by : Lekha
 *  on : 23rd Feb 2012
 *  Class that interacts with the workouts table device_messages
 */
class DeviceMessages extends Zend_Db_Table
{
	
	
	 public function addData($data)
	    {
	    	global $db;
	    	
	    	
	    	$data = array(
		    'user_id'          => $data['user_id'],
		    'device_name'      => $data['device_name'],
		    'message'          => $data['message'],
		    'sender_device'    => $data['sender_device'],
		    'date_sent'        => date('Y-m-d'),
		    'status'           => $data['status']
		   );
	
	    $db->insert('device_messages', $data);
	    	
	    }
	    
	    
	   
}
?>