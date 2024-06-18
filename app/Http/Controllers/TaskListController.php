<?php

namespace App\Http\Controllers;

use App\Models\TaskList;
use App\Http\Requests\TaskList\StoreTaskListRequest;
use App\Http\Requests\TaskList\UpdateTaskListRequest;
use App\Http\Requests\TaskList\DetachTaskListRequest;
use App\Http\Requests\TaskList\AttachTaskListRequest;
use App\Http\Services\TaskListService;



class TaskListController extends Controller
{
    protected $taskListService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(TaskListService $taskListService)
    {
        $this->taskListService = $taskListService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $taskLists = $this->taskListService->index();

        return view('taskList.index', compact('taskLists'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TaskList $taskList)
    {
        return $this->taskListService->edit($taskList);
    }

    public function detach(DetachTaskListRequest $request, TaskList $taskList)
    {
        $taskList->users()->detach($request->validated()['userId']);

        return view('taskList.userAccess', ['taskList' => $taskList]);
    }

    public function attach(AttachTaskListRequest $request, TaskList $taskList)
    {
        $taskList->users()->syncWithoutDetaching([$request->validated()['userId'] => ['role' => $request->validated()['userRole']]]);

        return view('taskList.userAccess', ['taskList' => $taskList]);
    }

    public function show()
    {
        return redirect(route('taskLists.index'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskListRequest $request)
    {
        $validated = $request->validated();

        $taskList = $this->taskListService->store($validated);

        return view('taskList.store', ['taskList' => $taskList]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskListRequest $request, TaskList $taskList)
    {
        $validated = $request->validated();

        $taskList->update($validated);

        return redirect(route('taskLists.index'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TaskList $taskList)
    {
        return $this->taskListService->destroy($taskList);
    }
}
