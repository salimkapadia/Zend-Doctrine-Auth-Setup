<?php
/**
 * @author Salim Kapadia <salimk786@gmail.com>  
 * Special thanks to http://www.zendcasts.com/unit-testing-doctrine-2-entities/2011/02/
 * 
 */
class ModelTestCase extends PHPUnit_Framework_TestCase{
    /**
     *
     * @var \Bisna\Application\Container\DoctrineContainer
     */
    protected static $doctrineContainer;
    /**
     *
     * @var boolean 
     */
    private $hasTearDownHappened = false;
    /**
     *
     * @var array 
     */
    protected static $users = array(
                        array(
                            "firstName" => "Ibn",
                            "lastName" => "Sina",
                            "email" => "Avicenna@thebookofhealing.com",
                            "password" => "goodhealth",
                            "salt" => "salt"
                        ),
                    );
    
    public function __construct(){
        self::$doctrineContainer = Zend_Registry::get('doctrine');        
    }
    
    /**
     *
     * @param type $path - where all the entities are stored
     * @param type $namespace - Namespace of the entities.
     * @return array 
     */
    public static function getClassMetas($path, $namespace) {
        $metas = array();
        if ($handle = opendir($path)){
            while (false !== ($file = readdir($handle))){
                if (strstr($file, ".php")){
                    list($class) = explode('.', $file);
                    $metas[] = self::$doctrineContainer->getEntityManager()->getClassMetadata($namespace . $class);
                }
            }
        }
        return $metas;
    }
    /**
     * This function loads data into our test db for our tests to consume 
     */
    private static function loadTestData(){
        $em = self::$doctrineContainer->getEntityManager();        
        foreach(self::$users as $user){
            $u = new Imixer\Domain\Entities\User();
            $u->setFirstName($user["firstName"]);
            $u->setLastName($user["lastName"]);
            $u->setEmail($user["email"]);
            $u->setPassword($user["password"]);
            $u->setSalt($user["salt"]);
            $em->persist($u);            
        }
        $em->flush(); //this will write my user to the db.        
    }
    public function canCallTearDown(){
        return $this->hasTearDownHappened;
    }
    public static function setUpBeforeClass()
    {                
        $tool = new \Doctrine\ORM\Tools\SchemaTool(self::$doctrineContainer->getEntityManager());        
        $tool->createSchema(self::getClassMetas(APPLICATION_PATH . '/../library/Imixer/Domain/Entities', 'Imixer\Domain\Entities\\'));
        self::loadTestData();      
            
        parent::setUpBeforeClass();        
    }

    public static function tearDownAfterClass(){    
        $hasTearDownHappened = true;
        $tool = new \Doctrine\ORM\Tools\SchemaTool(self::$doctrineContainer->getEntityManager());        
        $tool->dropSchema(self::getClassMetas(APPLICATION_PATH . '/../library/Imixer/Domain/Entities', 'Imixer\Domain\Entities\\'));        
        parent::tearDownAfterClass();        
    }
}