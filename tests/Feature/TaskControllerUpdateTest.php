<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\TaskList as ModelsTaskList;
use App\Models\Task as ModelsTask;
use App\Models\Tag as ModelsTag;

class TaskControllerUpdateTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_task_list_controller_update(): void
    {
        $tags = ['tag1', 'tag2'];
        $updateTags = 'NewTag1 NewTag2';

        $user = User::factory()->create();

        $taskList = ModelsTaskList::create([
            'name' => 'test',
        ]);

        $user->taskLists()->attach($taskList->id, ['role' => 'Создатель']);

        $task = ModelsTask::create([
            'task_list_id' => $taskList->id,
            'name'         => "test",
            'content'      => "Test",
        ]);

        foreach ($tags as $tag) {
            ModelsTag::create([
                'task_id' => $task->id,
                'name'    => $tag,
            ]);
        }

        $response = $this->actingAs($user)
            ->patch(
                route('taskLists.tasks.update', ['taskList' => $taskList->id, 'task' => $task->id]),
                [
                    'name' => 'updated',
                    'content' => 'updated',
                    'tags' => $updateTags
                ]
            );

        $response->assertStatus(200);
        $this->assertDatabaseHas('tasks', [
            'name' => 'updated',
        ]);
        $this->assertDatabaseHas('tags', [
            'name' => 'NewTag1',
        ]);
    }
}
