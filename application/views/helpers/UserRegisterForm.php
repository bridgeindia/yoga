<?php
class Zend_View_Helper_UserRegisterForm extends Zend_View_Helper_Abstract
{
    function employeeAddForm() {
       $form = new Zend_Form();
		    $form->setAction('http://localhost/zend/index/registeruser')
		         ->setMethod('post');
		     
		    // Create and configure username element:
		    $name = $form->createElement('text', 'user_name');
		    $name->setLabel('Name');
		    $name->setRequired(true)
		             ->addFilter('StringToLower');
		             
		     $email = new Zend_Form_Element_Text('email');
             $email->setLabel('Email')
              ->addFilter('StringToLower')
              ->setRequired(true)
              ->addValidator('NotEmpty', true)
              ->addValidator('EmailAddress');         
		             
		             
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
		    $form->addElement($name)
		         ->addElement($email)
		          ->addElement($username)
		          ->addElement($password)
		         // use addElement() as a factory to create 'Login' button:
		         ->addElement('submit', 'login', array('label' => 'Login'));
		         return $form;
    }
}

?>