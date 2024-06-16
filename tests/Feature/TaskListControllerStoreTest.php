<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutMiddleware;

use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use App\Models\User;

class TaskListControllerStoreTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_task_list_controller_store(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
                         ->post(route('taskLists.store'), ['name' => 'test']);

        $response->assertStatus(200);
        $this->assertDatabaseHas('task_lists', ['name' => 'test']);
    }
}
