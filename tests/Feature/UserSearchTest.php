<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;
use App\Models\User;


class UserSearchTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_user_search(): void
    {
        $user = User::factory()->create();

        $names = ['john', 'johnotan', 'martin', 'eric', 'joha', 'chris'];

        foreach ($names as $name) {
            User::factory()->create([
                'name' => $name,
            ]);
        }

        $response = $this->actingAs($user)->get('/user/search?userName=joh');

        $response->assertStatus(200);
        $response
            ->assertJson(fn (AssertableJson $json) =>
            $json->has(3));
        $response->assertJsonFragment([
            'name'     => 'john',
        ]);
    }
}
