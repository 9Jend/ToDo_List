<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\TaskList as ModelsTaskList;

class TaskListUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_task_list_user_test(): void
    {
        $user = User::factory()->create();
        $taskList = ModelsTaskList::create([
            'name' => 'test',
        ]);

        $user->taskLists()->attach($taskList->id, ['role' => 'read']);
        $taskList->users()->attach($user->id, ['role' => 'read']);

        $this->assertDatabaseCount('task_list_user', 2);
    }
}
