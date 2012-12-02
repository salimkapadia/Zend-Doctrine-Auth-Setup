<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    protected function _initDoctype()
    {
        $this->bootstrap('view');
        $view = $this->getResource('view');
        $view->doctype('XHTML1_STRICT');
    }
    public function _initAutoloaderNamespaces()
    {
        require_once APPLICATION_PATH . '/../library/Doctrine/Common/ClassLoader.php';

        $autoloader = \Zend_Loader_Autoloader::getInstance();
        $fmmAutoloader = new \Doctrine\Common\ClassLoader('Bisna');
        $autoloader->pushAutoloader(array($fmmAutoloader, 'loadClass'), 'Bisna');
    }
    /**
     *
     * @return Zend_Auth
     */
    protected function _initAuth(){
        $this->bootstrap('session');
        $auth = Zend_Auth::getInstance(); 
        $auth->setStorage(new \Imixer\Auth\Storage\Session('imixer_session_storage')); 
         
        return $auth;        
    }      
    protected function _initLogger(){
    	$logger = new Zend_Log();
        $writer = new Zend_Log_Writer_Stream('/var/log/php_errors.log');
        $logger->addWriter($writer);
        Zend_Registry::set('logger',$logger);
    }      
    protected function _initConfig() {
        $config = new Zend_Config($this->getOptions());
        Zend_Registry::set('config', $config);
        return $config;
    }     
}

