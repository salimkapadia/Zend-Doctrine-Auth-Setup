<?php
/**
 * 
 * @author Salim Kapadia <salimk786@gmail.com> 
 * 
 */
namespace Imixer\Auth\Storage;
class Cookie implements \Zend_Auth_Storage_Interface {    
    /**
    * Default cookie path
    */
    const COOKIE_PATH_DEFAULT = '/';
    /**
    * Default filter adapter
    */
    const FILTER_ADAPTER_DEFAULT = 'mcrypt';
    /**
    * Default filter encryption key
    */
    const FILTER_ENCRYPTION_KEY_DEFAULT = "KFJGKDK$$##^FFS345678F54";
    /**
    * Default filter domain
    */
    const FILTER_VECTOR_DEFAULT = '236587hgtyujkirtfgty5678';    
    /**
    * Encryption/Decrypt filter adapter
    *
    * @var string
    */
    protected $_filterAdapter;
    /**
    * Encryption/Decrypt filter key
    *
    * @var string
    */
    protected $_filterEncryptionKey;
    /**
    * Encryption/Decrypt filter vector
    *
    * @var string
    */
    protected $_filterVector;    
    /**
    * Cookie expiration time
    *
    * @var string
    */
    protected $_cookieExpiration;
    /**
    * Cookie path 
    *
    * @var string
    */
    protected $_cookiePath;
    /**
    * Cookie domain 
    *
    * @var string
    */
    protected $_cookieDomain;
    
    public function __construct()
    {        
        $this->setCookieExpiration(time()+3600*24); /* expire in 1 day */
        $this->setCookiePath(self::COOKIE_PATH_DEFAULT);        
        $this->setFilterAdapter(self::FILTER_ADAPTER_DEFAULT);
        $this->setFilterEncryptionKey(self::FILTER_ENCRYPTION_KEY_DEFAULT);
        $this->setFilterVector(self::FILTER_VECTOR_DEFAULT);        
    }    
    /**
     * Returns the cookie expiration time
     *
     * @return int
     */    
    public function getCookieExpiration(){
        return $this->_cookieExpiration;
    }
    /**
     * The expiration time of the cookie.
     * @param int $expires 
     */
    public function setCookieExpiration($expires){
        $this->_cookieExpiration = $expires;
    }
    /**
     * Return the cookie path
     * @return string 
     */
    public function getCookiePath(){
        return $this->_cookiePath;
    }
    /**
     * Set the cookie path
     * @param string $path 
     */
    public function setCookiePath($path = '/'){
        $this->_cookiePath = $path;
    }
    /**
     * Returns the filter adapter
     * @return string 
     */
    public function getFilterAdapter(){
        return $this->_filterAdapter;
    }
    /**
     * Set the filter adapter
     * @param string $adapater 
     */
    public function setFilterAdapter($adapater){
        $this->_filterAdapter = $adapater;
    }
    /**
     * Return the filter encryption key.
     * @return string
     */
    public function getFilterEncryptionKey(){
        return $this->_filterEncryptionKey;
    }
    /**
     * Set the Filter Encryption Key
     * @param string $key 
     */
    public function setFilterEncryptionKey($key){
        $this->_filterEncryptionKey = $key;
    }
    /**
     * Return the filter vector
     * @return string
     */
    public function getFilterVector(){
        return $this->_filterVector;
    }
    /**
     * Set the Filter Vector
     * @param string $vector 
     */
    public function setFilterVector($vector){
        $this->_filterVector = $vector;
    }
    /**
     * Defined by Zend_Auth_Storage_Interface
     *
     * @return boolean
     */    
    public function isEmpty() {
        return $this->read() === NULL;
    }
    public function _getOptions(){
        return array(
                'key'=>$this->getFilterEncryptionKey(),
                'adapter' => $this->getFilterAdapter(),
                'algorithm' => 'rijndael-192',
                'vector' => $this->getFilterVector()
                );
    }
    /**
     * Defined by Zend_Auth_Storage_Interface
     *
     * @return \Imixer\Domain\Entities\User|NULL
     */    
    public function read() {        
        $cookies = $this->getCookies();        
        if(is_null($cookies) || count($cookies) !== 3){
            return NULL;
        }
        $needle = "id=";
        $pos = strpos($cookies["evalue"],$needle);
        if( !($pos === false) ){
            $identifier = substr($cookies["evalue"], $pos + strlen($needle));
            $em = \Zend_Registry::get('doctrine')->getEntityManager();        
            $entity = $em->find('\Imixer\Domain\Entities\User', $identifier);       
            error_log(print_r($entity,true));
            return $entity;        
        }
        return NULL;
    }             
    /**
     * Returns cookie data.
     * @return array|NULL
     */
    protected function getCookies(){                
        if  ( isset($_COOKIE["email"]) && isset($_COOKIE["id"]) && isset($_COOKIE["evalue"])){
                return array(
                        "id" => $_COOKIE["id"],
                        "email" => $_COOKIE["email"],
                        "evalue" => $this->decryptCookieValue($_COOKIE["evalue"]),
                    );            
            }
        return NULL;
    }                
    /**
     * Defined by Zend_Auth_Storage_Interface
     *
     * @return void
     */
    public function clear(){        
        setcookie("email", "", time() - 3600, $this->getCookiePath());
        setcookie("id", "", time() - 3600, $this->getCookiePath());
        setcookie("evalue", "", time() - 3600, $this->getCookiePath());
    }        
    /**
     * Defined by Zend_Auth_Storage_Interface
     *
     * @param integer $identifier 
     * @return void
     */    
    public function write($identifier) {
        //user credentials have already been verified.
        //we write data to the cookie.
        $em = \Zend_Registry::get('doctrine')->getEntityManager();
        $user = $em->find('\Imixer\Domain\Entities\User',$identifier);
        
        $evalue = "email=". $user->getEmail() . ";id=" . $user->getId();        
        $this->writeCookie("evalue",$this->encryptCookieValue($evalue));
        $this->writeCookie("email",$user->getEmail());
        $this->writeCookie("id",$user->getId());        
    }
    public function writeCookie($key, $value){        
        return  setcookie($key,$value,$this->getCookieExpiration(), $this->getCookiePath());  
    }
    /**
     * Take a string and return the encrypted value. 
     * @param string $data 
     * @return string encrypted value
     */
    protected function encryptCookieValue($data){            
        $filter = new \Zend_Filter_Encrypt($this->_getOptions());                 
        
        //cookies will only store US-ASCII and ecryption data may have non-US-ASCII
        //so we base64_encode it
        return base64_encode($filter->filter(($data)));
    }
    /**
     * Returns null if decryption fails or string of decrypted data if successfull. 
     * @param string $data
     * @return string|NULL 
     */
    protected function decryptCookieValue($data){        
        $filter = new \Zend_Filter_Decrypt($this->_getOptions());                   
        $value = $filter->filter( base64_decode($data) );
        return (is_null($value)) ? NULL : $value;
    }      
}