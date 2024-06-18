<?php
use App\Http\Controllers;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Auth::routes();

Route::resource('taskLists', App\Http\Controllers\TaskListController::class)
    ->except(['create'])
    ->middleware('auth');

Route::patch('/taskLists/{taskList}/detach', [App\Http\Controllers\TaskListController::class, 'detach'])
    ->name('taskLists.detach')
    ->middleware('auth');
Route::patch('/taskLists/{taskList}/attach', [App\Http\Controllers\TaskListController::class, 'attach'])
    ->name('taskLists.attach')
    ->middleware('auth');


Route::get('/user/search', [App\Http\Controllers\UserController::class, 'searchByName'])
    ->name('user.search')
    ->middleware('auth');

Route::resource('taskLists.tasks', App\Http\Controllers\TaskController::class)->middleware(['auth', 'userRole']);

Route::get('/task/search', [App\Http\Controllers\TaskController::class, 'searchTaskByTag'])->middleware('auth');

Route::get('/', function () {
    return redirect(route('taskLists.index'));
})->middleware('auth');
