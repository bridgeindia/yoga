<?php
class Zend_View_Helper_AdminloginForm extends Zend_View_Helper_Abstract
{
    function adminloginForm() {
	$siteurl   = 'http://'.$_SERVER['SERVER_NAME'];
       $form = new Zend_Form();
		    $form->setAction($siteurl.'/yoga/admin/auth')
		         ->setMethod('post');
		     
		    // Create and configure username element:
		    $username = $form->createElement('text', 'user_username');
		    $username->setLabel('Username');
		    $username->addValidator('alnum')
		             
		             ->addValidator('regex', false, array('/^[a-z]+/'))
		             ->addValidator('stringLength', false, array(6, 20))
		             ->setRequired(true)
		             ->addFilter('StringToLower');
		     
		    // Create and configure password element:
		    $password = $form->createElement('password', 'user_password');
		    $password->setLabel('Password');
		    $password->addValidator('StringLength', false, array(6))
		             ->setRequired(true);
		     
		    // Add elements to form:
		    $form->addElement($username)
		         ->addElement($password)
		         // use addElement() as a factory to create 'Login' button:
		         ->addElement('submit', 'login', array('label' => 'Login','class' => 'noWarn'));
		         return $form;
    }
}

?>
