<?php
/**
 * 
 * @author Salim Kapadia <salimk786@gmail.com> 
 * inspired by https://gist.github.com/1357662 
 * 
 */
namespace Imixer\Auth\Storage;
class Session extends \Zend_Auth_Storage_Session {        
    /**
     * @param string $namespace
     * @param string $member 
     */
    public function __construct($namespace = self::NAMESPACE_DEFAULT, $member = self::MEMBER_DEFAULT){         
        parent::__construct($namespace, $member);
    }
    
    public function isEmpty() {
        return parent::isEmpty() || $this->read() === NULL;
    }
    
    /**
     * @param integer $identifier 
     */
    public function write($identifier) {  
        //user credentials have already been verified so we simply just pass
        //the user identifier to parent.
        parent::write($identifier);
    }
    
    /**
     * @return \Imixer\Domain\Entities\User|NULL
     */
    public function read() {
        $identifier = parent::read();
        if(empty($identifier)) {
            return NULL;
        }
        $em = \Zend_Registry::get('doctrine')->getEntityManager();
        $user = $em->find('\Imixer\Domain\Entities\User', $identifier);
        if($user === NULL) {
            parent::write(NULL);
        }
        return $user;
    }    
}