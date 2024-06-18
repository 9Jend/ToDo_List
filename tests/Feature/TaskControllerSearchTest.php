<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;
use App\Models\User;
use App\Models\TaskList as ModelsTaskList;
use App\Models\Task as ModelsTask;
use App\Models\Tag as ModelsTag;

class TaskControllerSearchTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_task_controller_search(): void
    {
        $validTags = ['validTag1', 'validTag2'];
        $notValidTags = ['asddsadsa', 'dsadsadsadsadsa'];

        $user = User::factory()->create();

        $taskList = ModelsTaskList::create([
            'name' => 'test',
        ]);

        $user->taskLists()->attach($taskList->id, ['role' => 'Создатель']);

        $taskValid = ModelsTask::create([
            'task_list_id' => $taskList->id,
            'name'         => "TestValid",
            'content'      => "Test",
        ]);

        $taskNotValid = ModelsTask::create([
            'task_list_id' => $taskList->id,
            'name'         => "TestNotValid",
            'content'      => "Test",
        ]);

        foreach ($validTags as $tag) {
            ModelsTag::create([
                'task_id' => $taskValid->id,
                'name'    => $tag,
            ]);
        }

        foreach ($notValidTags as $tag) {
            ModelsTag::create([
                'task_id' => $taskNotValid->id,
                'name'    => $tag,
            ]);
        }


        $response = $this->actingAs($user)->get('/task/search?tagName=validTag1');

        $response->assertStatus(200);
        $response->assertJson(fn (AssertableJson $json) => $json->has(1));
        $response->assertJsonFragment([
            'name'     => 'TestValid',
        ]);
    }
}
