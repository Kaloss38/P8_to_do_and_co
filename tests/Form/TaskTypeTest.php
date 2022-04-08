<?php

namespace Tests\Form;

use App\Entity\Task;
use App\Form\TaskType;
use DateTime;
use Symfony\Component\Form\Test\TypeTestCase;

class TaskTypeTest extends TypeTestCase
{
    public function testBuildForm(): void
    {
        $formData = [
            'title' => 'test',
            'content' => 'test2'
        ];

        $model = new Task();
        $model->setCreatedAt(new DateTime('05/04/2022 20:00:00'));

        $form = $this->factory->create(TaskType::class, $model);
        $form->submit($formData);

        $expected = new Task();
        $expected->setTitle('test');
        $expected->setContent('test2');
        $expected->setCreatedAt(new DateTime('05/04/2022 20:00:00'));

        $this->assertTrue($form->isSynchronized());

        $this->assertEquals($expected, $model);
    }
}