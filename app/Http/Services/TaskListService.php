<?php

namespace App\Http\Services;

use App\Models\TaskList;
use Illuminate\Support\Facades\DB;


class TaskListService
{
    private const TASK_ROLE_ADMIN   = 'Создатель';
    private const TASK_ROLE_CHANGE  = 'Редактирование';
    private const TASK_ROLE_READ    = 'Просмотр';

    public function index()
    {
        $taskLists = auth()->user()->taskLists()->get();

        foreach ($taskLists as $task) {
            if ($task->pivot->role !== self::TASK_ROLE_READ)
                $task->canUpdate = true;
        }

        return $taskLists;
    }

    public function store($data)
    {
        $taskList = TaskList::create($data);

        auth()->user()->taskLists()->attach($taskList->id, ['role' => self::TASK_ROLE_ADMIN]);

        return $taskList;
    }


    public function edit(TaskList $taskList)
    {
        $canEdit = false;
        $isAdmin = false;

        foreach ($taskList->users as $user) {
            if ($user->id == auth()->user()->id) {
                $canEdit = in_array($user->pivot->role, [self::TASK_ROLE_ADMIN, self::TASK_ROLE_CHANGE]);
                $isAdmin = $user->pivot->role == self::TASK_ROLE_ADMIN;
            }
        }

        if ($canEdit)
            return view(
                'taskList.edit',
                [
                    "taskList" => $taskList,
                    'isAdmin' => $isAdmin,
                    'taskRoleChange' => self::TASK_ROLE_CHANGE,
                    "taskRoleRead" => self::TASK_ROLE_READ
                ]
            );

        return redirect(route('taskLists.index'));
    }

    public function destroy(TaskList $taskList)
    {
        $users = $taskList->users()->wherePivot('user_id', '=', auth()->user()->id)->get();

        foreach ($users as $user) {
            if ($user->pivot->role !== self::TASK_ROLE_ADMIN) {
                $taskList->users()->detach(auth()->user()->id);

                return response()->json(['success' => 'ok', 'emptyList' => $this->userTaskListCount()]);
            }
        }

        return $this->destroyTransaction($taskList);
    }

    private function destroyTransaction(TaskList $taskList)
    {
        DB::beginTransaction();

        try {
            $taskList->users()->detach();
            $taskList->delete();
            DB::commit();

            return response()->json(['success' => 'ok', 'emptyList' => $this->userTaskListCount()]);
        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    private function userTaskListCount(): bool
    {
        return auth()->user()->taskLists()->count() == 0;
    }
}
