<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\TaskList as ModelsTaskList;

class TaskCheckAccessTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_task_checAccessTest(): void
    {
        $userCreater = User::factory()->create();
        $userWithNoAccess = User::factory()->create();
        $taskList = ModelsTaskList::create([
            'name' => 'test',
        ]);

        $userCreater->taskLists()->attach($taskList->id, ['role' => 'Создатель']);

        $responseSuccess = $this->actingAs($userCreater)
                         ->get(route('taskLists.tasks.index', $taskList));

        $responseSuccess->assertStatus(200);

        $responseError = $this->actingAs($userWithNoAccess)
                         ->get(route('taskLists.tasks.index', $taskList));

        $responseError->assertStatus(404);
    }
}
