<?php

namespace Tests\Feature;

use App\Models\TaskList as ModelsTaskList;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TaskListTest extends TestCase
{
    use RefreshDatabase;

    public function test_task_list(): void
    {
        $taskList = ModelsTaskList::create([
            'name' => 'test',
        ]);

        $this->assertModelExists($taskList);

        $taskList->delete();
        $this->assertSoftDeleted($taskList);
    }
}
