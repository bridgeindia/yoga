<?php
class ErrorController extends Zend_Controller_Action
{
  public function errorAction()
  {
    // Insert error handling code here
	$this->_helper->layout()->setLayout('layout_page');
	$isAjaxRequest = $this->getRequest()->isXmlHttpRequest();
	$errors = $this->_getParam('error_handler');
	
	//$log = Zend_Registry::get('Zend_Log');
	
	switch ($errors->type)
		{
		  case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
		  case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
		  
		   	if ($isAjaxRequest)
			{
			  $errorMessage = 'ERROR,404';
			}
			else
			{
			  $this->view->title = 'Page not found';
			  $this->view->message = 
			    'The page you requested could not be found.';
			}
			
			//$log->info('404 error occured: ' . 
		    $this->getRequest()->getRequestUri();
		    break;
		 
		  default:
		    // application error
		    // Insert internal error handling code here
			
			if ($isAjaxRequest)
				{
				  $errorMessage = 'ERROR,500';
				}
				else 
				{
				  $this->view->title = 'Application error';
				  $this->view->message = 
				    'An error occured in the web application.';
				}
				 
				//$log->crit('500 error occured: ' . 
				$errors->exception->getMessage();
			    break;
		}
	
	
	
	if ($isAjaxRequest)
{
  $this->_helper->layout->disableLayout();
  $this->_helper->viewRenderer->setNoRender(true);
  echo $errorMessage;
}
else 
{
  $this->view->exception = $errors->exception;
  $this->view->request   = $errors->request;
}
  }
  
  
  /**
	 * function that checks if a user session is set
	 * @author lekha
	 * @date 3/22/2012
	 * 
	 */ 
	public function isLoggedIn()
	{
		If (Zend_Session::namespaceIsset('UserSession')) 
		{

          return 1;

        }
        else 
        {
        	return 0;
        }
	}
}
?>