<?php
class portal_Helper_LoginForm
{
    public function loginForm()
    {
    	 $form = new Zend_Form();
		    $form->setAction('user/auth')
		         ->setMethod('post');
		     
		    // Create and configure username element:
		    $username = $form->createElement('text', 'user_username');
		    $username->addValidator('alnum')
		             ->addValidator('regex', false, array('/^[a-z]+/'))
		             ->addValidator('stringLength', false, array(6, 20))
		             ->setRequired(true)
		             ->addFilter('StringToLower');
		     
		    // Create and configure password element:
		    $password = $form->createElement('password', 'user_password');
		    $password->addValidator('StringLength', false, array(6))
		             ->setRequired(true);
		     
		    // Add elements to form:
		    $form->addElement($username)
		         ->addElement($password)
		         // use addElement() as a factory to create 'Login' button:
		         ->addElement('submit', 'login', array('label' => 'Login'));
    }
}
?>