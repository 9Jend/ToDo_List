<?php

namespace App\Http\Controllers;

use App\Models\TaskList;
use Illuminate\Http\Request;
use App\Http\Requests\StoreTaskListRequest;
use Illuminate\Support\Facades\DB;



class TaskListController extends Controller
{
    private $user;

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

        return view('taskList.index', compact('taskLists'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskListRequest $request)
    {
        $validated = $request->validated();
        $taskList = TaskList::create($validated);
        $this->user->taskLists()->attach($taskList->id, ['role' => 'admin']);

        return response()->json(['id' => $taskList->id, 'name' => $taskList->name, 'role' => 'admin']);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreTaskListRequest $request, TaskList $taskList)
    {
        $validated = $request->validated();
        $taskList->update($validated);

        return true;
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

            return true;
        }
        catch (\Throwable $e) {
            DB::rollBack();

            return false;
        }
    }
}
