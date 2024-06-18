<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\TaskList as ModelsTaskList;

class TaskListControllerAttachUserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_task_list_controller_attach_user(): void
    {
        $user = User::factory()->create();
        $taskList = ModelsTaskList::create([
            'name' => 'test',
        ]);

        $user->taskLists()->attach($taskList->id, ['role' => 'Создатель']);

        $response = $this->actingAs($user)
                         ->patch(route('taskLists.attach', $taskList), ['userId' => User::factory()->create()->id, 'userRole' => 'Просмотр']);

        $response->assertStatus(200);
        $this->assertEquals(count($taskList->users()->get()), 2);
    }
}
