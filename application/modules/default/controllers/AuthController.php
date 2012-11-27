<?php
class Default_AuthController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {        
        $this->_forward("login");
    }
    public function loginAction()
    {                        
        if ($this->getRequest()->isPost()){
            //you should really validate that they submitted all the required fields.
            //however for the sake of this example, I won't do that.            
            $adapter = new \Imixer\Auth\Adapter($this->_getParam('email'), $this->_getParam('password'));
            $result = \Zend_Auth::getInstance()->authenticate($adapter);

            if($result->isValid()){       
                $this->_redirect('/default/auth/secret');
            }else{
                $this->view->message = implode(' ' ,$result->getMessages());                                   
            }
        }
    }
    public function logoutAction(){
        \Zend_Auth::getInstance()->clearIdentity();  
        $this->_redirect('/');
    }
    
    public function secretAction()
    {
        if (! \Zend_Auth::getInstance()->hasIdentity()){
            $this->_redirect('/');        
        }            
    }
}

