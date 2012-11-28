<?php
/**
 * @author Salim Kapadia <salimk786@gmail.com>  
 * Special thanks to http://www.zendcasts.com/unit-testing-doctrine-2-entities/2011/02/
 * 
 */
namespace Imixer\Domain\Entities;
/**
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 *
 */
class UserTest extends \ModelTestCase{    
    public function testCanCreateUserInstance(){
        $this->assertInstanceOf('Imixer\Domain\Entities\User',new User());
    }
    public function testUserRecordExists(){
        $em = self::$doctrineContainer->getEntityManager();
        $email = self::$users[0]['email'];
        $results = $em->createQuery("select u from Imixer\Domain\Entities\User u where u.email = '{$email}'")->execute();
        $this->assertEquals(1,count($results[0]));        
    }
    public function testCanUpdateRecord(){
        $em = self::$doctrineContainer->getEntityManager();
        $email = self::$users[0]['email'];
        $results = $em->createQuery("select u from Imixer\Domain\Entities\User u where u.email = '{$email}'")->execute();
        $user = $results[0];
        $newSaltKey = "abcdefghijklmnop";
        $user->setSalt($newSaltKey);

        $em->persist($user);
        $em->flush(); //this will write my user to the db.

        $record = $em->getRepository('Imixer\Domain\Entities\User')->findOneBy(
                        array('email' => $user->getEmail())
            );
        
        $this->assertEquals($newSaltKey, $record->getSalt());     
        
    }    
    public function testHasLifecycleCallbackPreUpdate(){
        $em = self::$doctrineContainer->getEntityManager();
        $record = $em->getRepository('Imixer\Domain\Entities\User')->findOneBy(
                        array('email' => self::$users[0]['email'])
            );
        $dateUpdatedBeforeUpdate = $record->getDateUpdated();

        $newSaltKey = "abc";
        $record->setSalt($newSaltKey);
        
        sleep(3); //wait 3 seconds and let's see if the date updated changed
        
        $em->persist($record);
        $em->flush(); //this will write my user to the db.
        
        $record = $em->getRepository('Imixer\Domain\Entities\User')->findOneBy(
                        array('email' => self::$users[0]['email'])
            );
        $dateUpdatedAfterUpdate = $record->getDateUpdated();
        $this->assertNotEquals($dateUpdatedBeforeUpdate, $dateUpdatedAfterUpdate);
    }
}