<?php

namespace Tests\Feature;

use App\Models\Task as ModelsTask;
use App\Models\TaskList as ModelsTaskList;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    public function test_task(): void
    {
        $taskList = ModelsTaskList::create([
            'name' => 'test',
        ]);

        $task = $taskList->tasks()->createQuietly([
            'name' => 'test',
            'content' => 'test',
        ]);

        $this->assertModelExists($task);

        $task->delete();
        $this->assertSoftDeleted($task);
    }
}
