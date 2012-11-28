<?php
/**
 * @author Salim Kapadia <salimk786@gmail.com>  
 * Special thanks to http://www.zendcasts.com/unit-testing-doctrine-2-entities/2011/02/
 * 
 */
namespace Imixer\Domain\Entities;

class UserTest extends \ModelTestCase{    
    public function testCanCreateUserInstance(){
        $this->assertInstanceOf('Imixer\Domain\Entities\User',new User());
    }
    public function testUserRecordExists(){
        $em = $this->doctrineContainer->getEntityManager();
        $results = $em->createQuery("select u from Imixer\Domain\Entities\User u where u.email = '{$this->users[0]['email']}'")->execute();
        $this->assertEquals(1,count($results[0]));        
    }
    public function testCanUpdateRecord(){
        $em = $this->doctrineContainer->getEntityManager();
        $results = $em->createQuery("select u from Imixer\Domain\Entities\User u where u.email = '{$this->users[0]['email']}'")->execute();
        $user = $results[0];
        $newSaltKey = "abcdefghijklmnop";
        $user->setSalt($newSaltKey);

        $em = $this->doctrineContainer->getEntityManager();
        $em->persist($user);
        $em->flush(); //this will write my user to the db.

        $record = $em->getRepository('Imixer\Domain\Entities\User')->findOneBy(
                        array('email' => $user->getEmail())
            );
        
        $this->assertEquals($newSaltKey, $record->getSalt());          
    }    
    public function testHasLifecycleCallbackPreUpdate(){
        $em = $this->doctrineContainer->getEntityManager();
        $record = $em->getRepository('Imixer\Domain\Entities\User')->findOneBy(
                        array('email' => $this->users[0]['email'])
            );
        $dateUpdatedBeforeUpdate = $record->getDateUpdated();

        $newSaltKey = "abcdefghijklmnop";
        $record->setSalt($newSaltKey);

        $em = $this->doctrineContainer->getEntityManager();
        $em->persist($record);
        $em->flush(); //this will write my user to the db.
        
        $record = $em->getRepository('Imixer\Domain\Entities\User')->findOneBy(
                        array('email' => $this->users[0]['email'])
            );
        $dateUpdatedAfterUpdate = $record->getDateUpdated();

        $this->assertNotEquals($dateUpdatedBeforeUpdate, $dateUpdatedAfterUpdate);
    }
}