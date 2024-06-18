<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\TaskList as ModelsTaskList;

class TaskListControllerDetachUserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_task_list_controller_detach_user(): void
    {
        $user = User::factory()->create();
        $detachingUser = User::factory()->create();
        $taskList = ModelsTaskList::create([
            'name' => 'test',
        ]);

        $taskList->users()->attach($user->id, ['role' => 'Создатель']);
        $taskList->users()->attach($detachingUser->id, ['role' => 'Просмотр']);

        $this->assertEquals(count($taskList->users()->get()), 2);

        $response = $this->actingAs($user)
                         ->patch(route('taskLists.detach', $taskList), ['userId' => $detachingUser->id]);

        $response->assertStatus(200);

        $this->assertEquals(count($taskList->users()->get()), 1);
    }
}
