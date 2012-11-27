<?php
/**
 * 
 * @author Salim Kapadia <salimk786@gmail.com> 
 * 
 */

namespace Imixer\Domain;
use Doctrine\ORM\EntityRepository;

class Repository extends EntityRepository {    
    protected $_logger; /* @var $em Zend_Log */
    
    public function __construct($em, $class)
    {
        parent::__construct($em,$class);
        $this->_logger = \Zend_Registry::get('logger');
    }        
}
?>
