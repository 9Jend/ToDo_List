<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserTaskRoleMiddleware
{
    private const TASK_ROLE_READ = 'Просмотр';

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $haveAccess = false;
        $canEdit = false;
        $taskListId = $request->route()->parameter('taskList')->id ?? $request->route()->parameter('taskList');
        foreach(auth()->user()->taskLists()->get() as $taskList){
            if($taskListId == $taskList->id){
                $canEdit = $taskList->pivot->role !== self::TASK_ROLE_READ ;
                $haveAccess = true;
                break;
            }
        }
        if(!$haveAccess)
            abort(404);

        if(in_array($request->route()->getActionMethod(), ['create', 'store', 'edit', 'update', 'destroy']) && !$canEdit)
            return redirect(route('taskLists.tasks.index', $request->route()->parameter('taskList')->id));

        \Illuminate\Support\Facades\View::share('canEdit', $canEdit);

        return $next($request);
    }
}
