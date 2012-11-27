<?php
/**
 *
 * @author Salim Kapadia <salimk786@gmail.com>  
 * Special thanks to http://www.zendcasts.com/writing-a-zend_auth_adapter-with-doctrine/2010/01/
 */
namespace Imixer\Auth;

class Adapter implements \Zend_Auth_Adapter_Interface{
    const FAILURE_IDENTITY_NOT_FOUND_MESSAGE = "Account not found";
    const FAILURE_CREDENTIAL_INVALID_MESSAGE = "Password is invalid";      
    /**
    *
    * @var string
    */
    protected $email; 
    /**
    *
    * @var string
    */
    protected $password; 

    /**
    *
    * @param email $email
    * @param email $password 
    */
    public function __construct($email, $password) {       
       $this->email = $email;
       $this->password = $password;
       
    }    
    /**
     * Performs an authentication attempt
     *    
     * @return Zend_Auth_Result
     */
    public function authenticate(){   
       $em = \Zend_Registry::get('doctrine')->getEntityManager();
       $user = $em->getRepository('Imixer\Domain\Entities\User')->findOneBy(array('email' => $this->email));
       if ($user == NULL){
           return $this->createResult(\Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND, 
                   NULL, self::FAILURE_IDENTITY_NOT_FOUND_MESSAGE
            );
       }
       $encryptedPassword = \Imixer\Domain\Repositories\UserRepository::encryptPassword(
                                $this->password,$user->getSalt()
                            );                    
       if ( !($user->getPassword() == $encryptedPassword) ){
           return $this->createResult(\Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID, 
                   NULL, self::FAILURE_CREDENTIAL_INVALID_MESSAGE
            );           
       }
       return $this->createResult(\Zend_Auth_Result::SUCCESS, $user);       
    }
    /**
     * Factory for Zend_Auth_Result
     *
     *@param integer    The Result code, see Zend_Auth_Result
     *@param \Entities\User    The entity whose identifier we will use. 
     *@param mixed      The Message, can be a string or array
     *@return \Zend_Auth_Result
     */
    public function createResult($code, $user = NULL, $messages = array()) {         
        if (!is_array($messages)) {
            $messages = array($messages);
        }
        $identifier = NULL;
        if(!is_null($user)){
            $identifier = $user->getId();
        }
        return new \Zend_Auth_Result($code,$identifier,$messages);
    }
}