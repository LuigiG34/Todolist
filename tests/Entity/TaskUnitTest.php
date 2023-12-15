<?php 

namespace Tests\Entity;

use App\Entity\Task;
use PHPUnit\Framework\TestCase;

class TaskUnitTest extends TestCase
{
    public function testTaskEntity()
    {
        $task = new Task();
        $task->setTitle('Title Test');
        $task->setContent('Lorem ipsum dolor, ...');
        $task->setCreatedAt(new \DateTimeImmutable());

        $this->assertEquals('Title Test', $task->getTitle());
        $this->assertEquals('Lorem ipsum dolor, ...', $task->getContent());
        $this->assertEquals(false, $task->isDone());
        $this->assertEquals(true, $task->getCreatedAt() instanceof \DateTimeImmutable);
    }
}
