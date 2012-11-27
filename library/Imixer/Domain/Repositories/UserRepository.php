<?php
/**
 * 
 * @author Salim Kapadia <salimk786@gmail.com> 
 */
namespace Imixer\Domain\Repositories;

class UserRepository extends \Imixer\Domain\Repository{
    /**
     * This function generates a salt key.
     * @return string - a uniquely generated value. 
     */
    public static function generateSalt(){
        return (md5(uniqid(rand(), TRUE)));
    }    
    /**
     *
     * @param string $password - User's password
     * @param string $salt - A unique string
     * @return string - encrypted password
     */
    public static function encryptPassword($password,$salt){
        return md5($salt.$password);
    }
        
    public function create($firstName, $lastName, $email, $password) {
        $user = new \Imixer\Domain\Entities\User();
        $user->setFirstName($firstName);
        $user->setLastName($lastName);
        $user->setEmail($email);
        $user->setSalt(self::generateSalt());        
        $user->setPassword(self::encryptPassword($password,$user->getSalt()));        
        $user->setIsActive(TRUE);
        $user->setDateCreated(new \DateTime());
        $user->setDateUpdated(new \DateTime());
        
        $em = \Zend_Registry::get('doctrine')->getEntityManager();
        $em->persist($user);
        $em->flush();        
        return $user;
    }    
}