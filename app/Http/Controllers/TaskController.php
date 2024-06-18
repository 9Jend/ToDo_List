<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskList;
use App\Http\Requests\Task\StoreTaskRequest;
use App\Http\Requests\Task\UpdateTaskRequest;
use App\Http\Services\TaskService;

class TaskController extends Controller
{
    protected $taskService;

    public function __construct(TaskService $taskService) {
        $this->taskService = $taskService;
    }

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

        $taskListId = $this->taskService->store($validate);

        return redirect(route('taskLists.tasks.index', $taskListId));
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

        $photo = $this->taskService->update($taskList, $task, $validate);

        return response()->json(['success' => 'ok', 'photo' => $photo]);
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
