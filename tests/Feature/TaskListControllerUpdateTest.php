<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\TaskList as ModelsTaskList;

class TaskListControllerUpdateTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_task_list_controller_update(): void
    {
        $user = User::factory()->create();
        $taskList = ModelsTaskList::create([
            'name' => 'test',
        ]);

        $user->taskLists()->attach($taskList->id, ['role' => 'read']);
        $taskList->users()->attach($user->id, ['role' => 'read']);

        $response = $this->actingAs($user)
                         ->patch(route('taskLists.update', $taskList), ['name' => 'test_update']);

        $response->assertStatus(302);
        $this->assertDatabaseHas('task_lists', ['name' => 'test_update']);
    }
}
