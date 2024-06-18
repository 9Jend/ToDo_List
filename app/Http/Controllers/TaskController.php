<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskList;
use App\Models\Tag;
use Illuminate\Http\Request;
use App\Http\Requests\Task\StoreTaskRequest;
use App\Http\Requests\Task\UpdateTaskRequest;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(TaskList $taskList)
    {
        $tasks = $taskList->tasks()->get();

        return view('task.index', compact('tasks', 'taskList'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(TaskList $taskList)
    {
        return view('task.create', compact('taskList'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request)
    {
        $validate = $request->validated();

        $tags = preg_split('/[^a-zA-Z0-9]+/', $validate['tags']);
        unset($validate['tags']);

        if (isset($validate['photo'])) {
            $photo = $validate['photo'];
            unset($validate['photo']);

            if ($photo->isFile()) {
                $path = '/taskImage/' . $validate['task_list_id'];
                $fileName = uniqid() . $photo->getClientOriginalName();
                if ($photo->move(public_path() . $path, $fileName))
                    $validate['photo'] = $path . '/' . $fileName;
            }
        }

        $task = Task::create($validate);

        foreach ($tags as $tag) {
            Tag::create([
                'task_id'   => $task->id,
                'name'      => $tag,
            ]);
        }

        return redirect(route('taskLists.tasks.index', $validate['task_list_id']));
    }

    /**
     * Display the specified resource.
     */
    public function show(TaskList $taskList, Task $task)
    {
        return view('task.show', compact('taskList', 'task'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TaskList $taskList, Task $task)
    {
        return view('task.edit', compact('taskList', 'task'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, TaskList $taskList, Task $task)
    {
        $validate = $request->validated();

        $tags = preg_split('/[^a-zA-Z0-9]+/', $validate['tags']);
        unset($validate['tags']);

        $removePhoto = null;

        if (isset($validate['removePhoto'])) {
            $removePhoto = $validate['removePhoto'];
            unset($validate['removePhoto']);
        }

        if (isset($validate['photo'])) {
            $photo = $validate['photo'];
            unset($validate['photo']);

            if ($photo->isFile()) {
                $path = '/taskImage/' . $taskList->id;
                $fileName = uniqid() . $photo->getClientOriginalName();
                if ($photo->move(public_path() . $path, $fileName)) {
                    $validate['photo'] = $path . '/' . $fileName;
                    if ($task->photo)
                        unlink(public_path() . $task->photo);
                }
            }
        } elseif ($removePhoto && $task->photo) {
            unlink(public_path() . $task->photo);
            $validate['photo'] = null;
        }

        $task->tags()->delete();

        foreach ($tags as $tag) {
            if(!empty($tag))
                Tag::create([
                    'task_id'   => $task->id,
                    'name'      => $tag,
                ]);
        }

        $task->update($validate);

        return response()->json(['success' => 'ok', 'photo' => $task->photo]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TaskList $taskList, Task $task)
    {
        $task->delete();
        return response()->json(['success' => 'ok', 'taskId' => $task->id]);
    }
}
