<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use App\Models\User;

class TaskListControllerIndexTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_task_list_controller_index(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
                         ->get(route('taskLists.index'));

        $response->assertStatus(200);
    }
}
