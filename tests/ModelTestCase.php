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
    
    public function setUp()
    {        
        $this->doctrineContainer = Zend_Registry::get('doctrine');        
        $tool = new \Doctrine\ORM\Tools\SchemaTool($this->doctrineContainer->getEntityManager());
        
        //In the event a test fails, the tearDown will not execute. Thus, we drop the tables
        //prior to creating the tables.
        $tool->dropSchema($this->getClassMetas(APPLICATION_PATH . '/../library/Imixer/Domain/Entities', 'Imixer\Domain\Entities\\'));                
        $tool->createSchema($this->getClassMetas(APPLICATION_PATH . '/../library/Imixer/Domain/Entities', 'Imixer\Domain\Entities\\'));        
        parent::setUp();        
    }

    public function tearDown(){
        $this->doctrineContainer = Zend_Registry::get('doctrine');        
        $tool = new \Doctrine\ORM\Tools\SchemaTool($this->doctrineContainer->getEntityManager());
        $tool->dropSchema($this->getClassMetas(APPLICATION_PATH . '/../library/Imixer/Domain/Entities', 'Imixer\Domain\Entities\\'));        
        parent::tearDown();        
    }
}