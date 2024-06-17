<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\TaskList as ModelsTaskList;

class TaskListControllerDeleteTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_task_list_controller_delete(): void
    {
        $user = User::factory()->create();
        $taskList = ModelsTaskList::create([
            'name' => 'test',
        ]);

        $taskList->users()->attach($user->id, ['role' => 'Создатель']);

        $response = $this->actingAs($user)
                         ->delete(route('taskLists.destroy', $taskList));

        $response->assertStatus(200);
        $this->assertSoftDeleted($taskList);
    }
}
