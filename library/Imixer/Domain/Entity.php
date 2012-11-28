<?php
/**
 * @author Salim Kapadia <salimk786@gmail.com>  
 */
namespace Imixer\Domain;

use Doctrine\ORM\Mapping as ORM;

/**
 * 
 * @ORM\MappedSuperclass 
 * @ORM\HasLifecycleCallbacks
 * 
*/
abstract class Entity {      
    /**
     * This function updates the date updated column before persisting the data.     
     * @ORM\PreUpdate  
     */
    public function preUpdate(){
        if(method_exists($this, 'setDateUpdated')){
            $this->setDateUpdated(new \DateTime());
        }            
    }
}
?>
