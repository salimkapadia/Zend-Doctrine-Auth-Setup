<?php
/**
 * 
 * @author Salim Kapadia <salimk786@gmail.com> 
 * 
 */

/**
 * 
 * @ORM\MappedSuperclass 
 * @ORM\HasLifecycleCallbacks
 * 
*/
namespace Imixer\Domain;

abstract class Entity {      
    /**
     * This function updates the date updated column before persisting the data.     
     * @ORM\preUpdate 
     * 
     */
    public function preUpdate(){
        if(method_exists($this, 'setDateUpdated')){
            $this->setDateUpdated(new \DateTime());
        }            
    }
}
?>
