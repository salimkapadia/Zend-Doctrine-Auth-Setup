<?php
/**
 * @author Salim Kapadia <salimk786@gmail.com>  
 * Special thanks to http://www.zendcasts.com/unit-testing-doctrine-2-entities/2011/02/
 * 
 */
namespace Imixer\Domain\Entities;

class UserTest extends \ModelTestCase{
    public function testCanCreateUser(){
        $this->assertInstanceOf('Imixer\Domain\Entities\User',new User());
    }
    public function testCanSaveFirstLastName(){
        $u= new User(); //Since the first line identifies my namespace, this is 
                        //the same as new Imixer\Domain\Entities\User()
        
        $u->setFirstName('Salim');
        $u->setLastName('Kapadia');
        $u->setEmail('salimk786@gmail.com');
        $u->setPassword('password');
        $u->setSalt('salt');
        
        $em = $this->doctrineContainer->getEntityManager();
        $em->persist($u);
        $em->flush(); //this will write my user to the db.
        
        //Now let's do a find operation to confirm it was written.        
        $users = $em->createQuery("select u from Imixer\Domain\Entities\User u")->execute();
        $this->assertEquals(1,count($users));        
        $this->assertEquals('Salim', $users[0]->getFirstName());
        $this->assertEquals('Kapadia', $users[0]->getLastName());        
    }

}