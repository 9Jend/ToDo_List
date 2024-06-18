<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\TaskList as ModelsTaskList;
use App\Models\Task as ModelsTask;
use App\Models\Tag as ModelsTag;

class TaskControllerStoreTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_task_list_controller_store(): void
    {
        $tags = 'NewTag1 NewTag2';

        $user = User::factory()->create();

        $taskList = ModelsTaskList::create([
            'name' => 'test',
        ]);

        $user->taskLists()->attach($taskList->id, ['role' => 'Создатель']);

        $response = $this->actingAs($user)
            ->post(
                route('taskLists.tasks.store', $taskList->id),
                [
                    'task_list_id' => $taskList->id,
                    'name' => 'Created',
                    'content' => 'Created',
                    'tags' => $tags
                ]
            );

        $response->assertStatus(302);
        $this->assertDatabaseHas('tasks', [
            'name' => 'Created',
        ]);
        $this->assertDatabaseHas('tags', [
            'name' => 'NewTag1',
        ]);
    }
}
