<?php
class Zend_View_Helper_LoginForm extends Zend_View_Helper_Abstract
{
    function loginForm() {
	$siteurl   = 'http://'.$_SERVER['SERVER_NAME'];
       $form = new Zend_Form();
		    $form->setAction($siteurl.'/user/auth')
		         ->setMethod('post');
		     
		    // Create and configure username element:
		    $username = $form->createElement('text', 'user_username');
		    
			//$username->setValue('Username');
			$username->setLabel('');
			$username->setAttribs(array('class' => 'login user'));
		    $username->addValidator('alnum')
		             
		             ->addValidator('regex', false, array('/^[a-z]+/'))
		             ->addValidator('stringLength', false, array(6, 20))
		             ->setRequired(true)
		             ->addFilter('StringToLower');
		     
		    // Create and configure password element:
		    $password = $form->createElement('password', 'user_password');
		     
			
			$password->setAttribs(array('class' => 'login password'));
		    $password->addValidator('StringLength', false, array(6))
		             ->setRequired(true);
		     
		    
		         // use addElement() as a factory to create 'Login' button:
				 
			$submit = $form->createElement('submit', 'Login');
			$submit->setAttrib('type', 'submit')
			       ->setAttrib('value', 'Sign In');
			// buttons do not need labels
			$submit->setDecorators(array(
			   array('ViewHelper'),
			   array('Description'),
			   array('HtmlTag', array('tag' => 'div', 'class'=>'button green')),
			));
			// Add elements to form:
		    $form->addElement($username)
		         ->addElement($password)
		         ->addElement($submit);
		         return $form;
    }
}

?>
