<?php
/**
 * @author Salim Kapadia <salimk786@gmail.com> 
 */

namespace Imixer\Domain\Entities;

use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity(repositoryClass="Imixer\Domain\Repositories\UserRepository")
 * @ORM\Table(name="user") 
 */
class User extends \Imixer\Domain\Entity{   
    
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */    
    private $id;

    /**
     * @var string $firstName
     *
     * @ORM\Column(name="first_name", type="string", length=45, nullable=false)
     */
    private $firstName;

    /**
     * @var string $lastName
     *
     * @ORM\Column(name="last_name", type="string", length=45, nullable=false)
     */
    private $lastName;
    
    /**
     * @var string $email
     *
     * @ORM\Column(name="email", type="string", length=60, nullable=false)
     */
    private $email;

    /**
     * @var string $password
     *
     * @ORM\Column(name="password", type="string", length=40, nullable=false)
     */
    private $password;

    /**
     * @var string $salt
     *
     * @ORM\Column(name="salt", type="string", length=40, nullable=false)
     */
    private $salt;

    /**
     * @var boolean $isActive
     *
     * @ORM\Column(name="is_active", type="boolean", nullable=false)
     */
    private $isActive;

    /**
     * @var \DateTime $dateCreated
     *
     * @ORM\Column(name="date_created", type="datetime", nullable=false)
     */
    private $dateCreated;

    /**
     * @var \DateTime $dateUpdated
     *
     * @ORM\Column(name="date_updated", type="datetime", nullable=false)
     */
    private $dateUpdated;
    
    public function __construct(){
        $this->isActive = TRUE;
        $this->dateCreated = new \DateTime("now");
        $this->dateUpdated = new \DateTime("now");
    }
    /**
     * Get id
     *
     * @return integer $id
     */    
    public function getId(){
        return $this->id;
    }
    /**
     * Get firstName
     *
     * @return string firstName
     */    
    public function getFirstName(){
        return $this->firstName;
    }
    /**
     * Set firstName
     *
     * @param string $firstName
     */
    public function setFirstName($firstName){
        $this->firstName = $firstName;
    }    
    /**
    * Get lastName
    *
    * @return string lastName
    */    
    public function getLastName(){
        return $this->lastName;
    }
    /**
     * Set lastName
     *
     * @param string $lastName
     */
    public function setLastName($lastName){
        $this->lastName = $lastName;
    }        
    /**
     * Get email
     *
     * @return string email
     */    
    public function getEmail(){
        return $this->email;
    }
    /**
     * Set email
     *
     * @param string $email
     */
    public function setEmail($email){
        $this->email = $email;
    }            
    /**
     * Get password
     *
     * @return string password
     */        
    public function getPassword(){
        return $this->password;
    }
    /**
     * Set password
     *
     * @param string $password
     */
    public function setPassword($password){
        $this->password = $password;
    }
    /**
     * Get salt
     *
     * @return string salt
     */        
    public function getSalt(){
        return $this->salt;
    }
    /**
     * Set salt
     *
     * @param string $salt
     */
    public function setSalt($salt){
        $this->salt = $salt;
    }    
    /**
     * Get isActive
     *
     * @return boolean isActive
     */            
    public function getIsActive(){
        return $this->isActive;
    }
    /**
     * Set isActive
     *
     * @param boolean $isActive
     */            
    public function setIsActive($isActive){
        $this->isActive = $isActive;
    }       
    /**
     * Get dateCreated
     *
     * @return \DateTime dateCreated
     */            
    public function getDateCreated(){
        return $this->dateCreated;
    }
    /**
     * Set dateCreated
     *
     * @param \DateTime $dateCreated
     */
    public function setDateCreated(\DateTime $dateCreated){
        $this->dateCreated = $dateCreated;
    }    
    /**
     * Get dateUpdated
     *
     * @return \DateTime dateUpdated
     */                
    public function getDateUpdated(){
        return $this->dateUpdated;
    }
    /**
     * Set dateUpdated
     *
     * @param \DateTime $datUpdated
     */
    public function setDateUpdated(\DateTime $datUpdated){        
        $this->dateUpdated = $datUpdated;        
    }           
}