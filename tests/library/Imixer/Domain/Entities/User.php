<?php
/**
 * @author Salim Kapadia <salimk786@gmail.com>  
 * Special thanks to http://www.zendcasts.com/unit-testing-doctrine-2-entities/2011/02/
 * 
 */
namespace Imixer\Domain\Entities;

class UserTest extends \ModelTestCase{
    private $testUsers = array(
            array(
                "firstName" => "Ibn",
                "lastName" => "Sina",
                "email" => "Avicenna@thebookofhealing.com",
                "password" => "goodhealth",
                "salt" => "salt"
            )
    );
    
    public function testCanCreateUser(){
        $this->assertInstanceOf('Imixer\Domain\Entities\User',new User());
    }
    public function testCanSaveFirstLastName(){
        $u= new User(); //Since the first line identifies my namespace, this is 
                        //the same as new Imixer\Domain\Entities\User()
        
        $u->setFirstName($this->testUsers[0]["firstName"]);
        $u->setLastName($this->testUsers[0]["lastName"]);
        $u->setEmail($this->testUsers[0]["email"]);
        $u->setPassword($this->testUsers[0]["password"]);
        $u->setSalt($this->testUsers[0]["salt"]);
        
        $em = $this->doctrineContainer->getEntityManager();
        $em->persist($u);
        $em->flush(); //this will write my user to the db.
        
        //Now let's do a find operation to confirm it was written.        
        $users = $em->createQuery("select u from Imixer\Domain\Entities\User u")->execute();
        $this->assertEquals(1,count($users));        
        $this->assertEquals($this->testUsers[0]["firstName"], $users[0]->getFirstName());
        $this->assertEquals($this->testUsers[0]["lastName"], $users[0]->getLastName());        
    }
    public function testDateUpdated(){
        //this function shows how the date updated column gets updated via
        //Doctrine @HasLifecycleCallbacks
        
        $u= new User(); //Since the first line identifies my namespace, this is 
                        //the same as new Imixer\Domain\Entities\User()
        
        $u->setFirstName($this->testUsers[0]["firstName"]);
        $u->setLastName($this->testUsers[0]["lastName"]);
        $u->setEmail($this->testUsers[0]["email"]);
        $u->setPassword($this->testUsers[0]["password"]);
        $u->setSalt($this->testUsers[0]["salt"]);
        
        $em = $this->doctrineContainer->getEntityManager();
        $em->persist($u);
        $em->flush(); //this will write my user to the db.
        
        $user = $em->getRepository('Imixer\Domain\Entities\User')->findOneBy(
                array('email' => $this->testUsers[0]["email"]));
        
        $dateUpdated = $user->getDateUpdated();
        $user->setSalt("newSalt");        
        $em->persist($user);
        $em->flush();        
        
        $users = $em->createQuery("select u from Imixer\Domain\Entities\User u")->execute();
        $this->assertEquals(1,count($users));        
        $this->assertNotEquals($dateUpdated, $users[0]->getDateUpdated());                
    }

}