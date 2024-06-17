<?php

namespace App\Http\Controllers;

use App\Models\TaskList;
use Illuminate\Http\Request;
use App\Http\Requests\StoreTaskListRequest;
use App\Http\Requests\UpdateTaskListRequest;
use App\Http\Requests\DetachTaskListRequest;
use App\Http\Requests\AttachTaskListRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;



class TaskListController extends Controller
{
    private $user;

    private const TASK_ROLE_ADMIN   = 'Создатель';
    private const TASK_ROLE_CHANGE  = 'Редактирование';
    private const TASK_ROLE_READ    = 'Просмотр';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = auth()->user();

            return $next($request);
        });
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $taskLists = $this->user->taskLists()->get();
        foreach($taskLists as $task){
            if($task->pivot->role !== self::TASK_ROLE_READ)
                $task->canUpdate = true;
        }
        return view('taskList.index', compact('taskLists'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TaskList $taskList)
    {
        $canEdit = false;
        $isAdmin = false;

        foreach ($taskList->users as $user) {
            if ($user->id == $this->user->id) {
                $canEdit = in_array($user->pivot->role, [self::TASK_ROLE_ADMIN, self::TASK_ROLE_CHANGE]);
                $isAdmin = $user->pivot->role == self::TASK_ROLE_ADMIN;
            }
        }

        if ($canEdit)
            return view('taskList.edit',
                [
                    "taskList" => $taskList,
                    'isAdmin' => $isAdmin,
                    'taskRoleChange' => self::TASK_ROLE_CHANGE,
                    "taskRoleRead" => self::TASK_ROLE_READ
                ]
            );

        return redirect(route('taskLists.index'));
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
        $taskList = TaskList::create($validated);
        $this->user->taskLists()->attach($taskList->id, ['role' => self::TASK_ROLE_ADMIN]);

        return view('taskList.store')->with('taskList', $taskList);
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
        DB::beginTransaction();

        try {
            $taskList->users()->detach();
            $taskList->delete();
            DB::commit();
            $emptyList = !$this->user->taskLists()->count() > 0;
            return response()->json(['success' => 'ok', 'emptyList' => $emptyList]);
        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
