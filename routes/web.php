<?php
use App\Http\Controllers;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Auth::routes();

Route::resource('taskLists', App\Http\Controllers\TaskListController::class)
    ->except(['create', 'edit'])
    ->middleware('auth');

Route::resource('tasks', App\Http\Controllers\TaskController::class);

Route::get('/', function(){
    return redirect(route('taskLists.index'));
})->middleware('auth');
