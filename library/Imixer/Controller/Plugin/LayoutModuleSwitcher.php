<?php
/**
 * This file allows you to switch the layout based on the module.
 * The module layout path and layout file name must be specified in the
 * configuration file:
 * 
 * Example:
 *      admin.resources.layout.layout = "layout"
 *      admin.resources.layout.layoutPath= APPLICATION_PATH "/modules/admin/layouts/scripts"
 * 
 * @author Salim Kapadia <salimk786@gmail.com>  
 */
namespace Imixer\Controller\Plugin;

class LayoutModuleSwitcher extends \Zend_Controller_Plugin_Abstract {

    /**
     * @param Zend_Controller_Request_Abstract Request object
     */
    public function preDispatch(\Zend_Controller_Request_Abstract $request) {                  
        $config = \Zend_Controller_Front::getInstance()->getParam('bootstrap')->getOptions();
        $moduleName = $request->getModuleName();
        
        $layoutPath = \Zend_Layout::getMvcInstance()->getLayoutPath();
        $layoutName = \Zend_Layout::getMvcInstance()->getLayout();
                    
        if(isset($config[$moduleName]['resources']['layout']['layout'])){
            $layoutName = $config[$moduleName]['resources']['layout']['layout'];
        }
        if(isset($config[$moduleName]['resources']['layout']['layoutPath'])){
            $layoutPath = $config[$moduleName]['resources']['layout']['layoutPath'];
        }        
        \Zend_Layout::getMvcInstance()
                ->setLayout($layoutName)
                ->setLayoutPath($layoutPath);
    }        
}