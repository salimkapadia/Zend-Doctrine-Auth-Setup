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
    protected $doctrineContainer;
    /**
     *
     * @var boolean 
     */
    private $hasTearDownHappened = false;
    /**
     *
     * @var array 
     */
    protected $users = array(
                        array(
                            "firstName" => "Ibn",
                            "lastName" => "Sina",
                            "email" => "Avicenna@thebookofhealing.com",
                            "password" => "goodhealth",
                            "salt" => "salt"
                        ),
                    );
    
    public function __construct(){
        $this->doctrineContainer = Zend_Registry::get('doctrine');        
    }
    
    /**
     *
     * @param type $path - where all the entities are stored
     * @param type $namespace - Namespace of the entities.
     * @return array 
     */
    public function getClassMetas($path, $namespace) {
        $metas = array();
        if ($handle = opendir($path)){
            while (false !== ($file = readdir($handle))){
                if (strstr($file, ".php")){
                    list($class) = explode('.', $file);
                    $metas[] = $this->doctrineContainer->getEntityManager()->getClassMetadata($namespace . $class);
                }
            }
        }
        return $metas;
    }
    /**
     * This function loads data into our test db for our tests to consume 
     */
    private function loadTestData(){
        $em = $this->doctrineContainer->getEntityManager();        
        foreach($this->users as $user){
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
    public function setUp()
    {                
        $tool = new \Doctrine\ORM\Tools\SchemaTool($this->doctrineContainer->getEntityManager());        
        $tool->createSchema($this->getClassMetas(APPLICATION_PATH . '/../library/Imixer/Domain/Entities', 'Imixer\Domain\Entities\\'));
        $this->loadTestData();

        // the below anonymous function will be called on shutdown in the event
        // teardown does not get called and the only time tearDown doesn't get
        // called is when an exception prevents the execution of the unit tests.
        $that = $this;
        register_shutdown_function(function() use ($that) {
                if (!$that->canCallTearDown()){
                    $that->tearDown();
                }
            });        
            
        parent::setUp();        
    }

    public function tearDown(){    
        $this->hasTearDownHappened = true;
        $tool = new \Doctrine\ORM\Tools\SchemaTool($this->doctrineContainer->getEntityManager());
        $tool->dropSchema($this->getClassMetas(APPLICATION_PATH . '/../library/Imixer/Domain/Entities', 'Imixer\Domain\Entities\\'));        
        parent::tearDown();        
    }
}