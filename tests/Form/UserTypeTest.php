<?php

namespace Tests\Form;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Component\Form\Test\TypeTestCase;

class UserTypeTest extends TypeTestCase
{
    public function testBuildForm(): void
    {
        $formData = [
            'username' => 'test',
            'password' => ['first' => 'secret', 'second' => 'secret'],
            'email' => 'test@example.com',
            'roles_options' => 'ROLE_USER'
        ];

        $model = new User();
        $form = $this->factory->create(UserType::class, $model);
        $form->submit($formData);

        $expected = new User();
        $expected->setUsername('test');
        $expected->setPassword('secret');
        $expected->setEmail('test@example.com');

        $this->assertTrue($form->isSynchronized());

        $this->assertEquals($expected, $model);
    }
}