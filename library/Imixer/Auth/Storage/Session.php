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
        return parent::isEmpty() || $this->read() === null;
    }
    
    /**
     * @param \Entities\Users|null $contents 
     */
    public function write($contents) {        
        $em = \Zend_Registry::get('doctrine')->getEntityManager();
        $identifier = $em->find('\Imixer\Domain\Entities\User',$contents);
        $contents = empty($identifier) ? null : $identifier->getId();
        parent::write($contents);
    }
    
    /**
     * @return \Imixer\Domain\Entities\User|null
     */
    public function read() {
        $data = parent::read();
        if(empty($data)) {
            return null;
        }
        $em = \Zend_Registry::get('doctrine')->getEntityManager();
        $data = $em->find('\Imixer\Domain\Entities\User', $data);
        if($data === null) {
            parent::write(null);
        }
        return $data;
    }    
}