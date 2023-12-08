<?php 

namespace App\tests\AppBundle\Entity;

use AppBundle\Entity\User;
use PHPUnit\Framework\TestCase;

class UserUnitTest extends TestCase
{
    public function testUserEntity()
    {
        $user = new User();
        $user->setUsername('testUsername');
        $user->setEmail('test@test.fr');
        $user->setPassword('testPassword');

        $this->assertEquals('testUsername', $user->getUsername());
        $this->assertEquals('test@test.fr', $user->getEmail());
        $this->assertEquals('testPassword', $user->getPassword());
    }
}
