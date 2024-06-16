<?php

namespace Tests\Feature;

use App\Models\Tag as ModelsTag;
use App\Models\Task as ModelsTask;
use App\Models\TaskList as ModelsTaskList;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TagTest extends TestCase
{
    use RefreshDatabase;

    public function test_tag(): void
    {
        $taskList = ModelsTaskList::create([
            'name' => 'test',
        ]);

        $task = $taskList->tasks()->createQuietly([
            'name' => 'test',
            'content' => 'test',
        ]);

        $tag = $task->tags()->createQuietly([
            'name' => 'test',
        ]);

        $this->assertModelExists($tag);

        $tag->delete();
        $this->assertSoftDeleted($tag);
    }
}
