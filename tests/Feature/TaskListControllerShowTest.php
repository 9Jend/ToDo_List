<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\TaskList as ModelsTaskList;

class TaskListControllerShowTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_task_list_controller_show(): void
    {
        $user = User::factory()->create();
        $taskList = ModelsTaskList::create([
            'name' => 'test',
        ]);
        $response = $this->actingAs($user)
                         ->get(route('taskLists.show', $taskList->id));

        $response->assertStatus(302);
    }
}
