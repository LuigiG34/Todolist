<?php 

namespace Tests\Entity;

use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserUnitTest extends TestCase
{
    public function testUserEntity()
    {
        $user = new User();
        $user->setUsername('testUsername');
        $user->setEmail('test@test.fr');
        $user->setPassword('testPassword');
        $user->setRoles(['ROLE_ADMIN']);

        $this->assertEquals('testUsername', $user->getUsername());
        $this->assertEquals('test@test.fr', $user->getEmail());
        $this->assertEquals('testPassword', $user->getPassword());
        $this->assertEquals(['ROLE_ADMIN', 'ROLE_USER'], $user->getRoles());
    }
}
