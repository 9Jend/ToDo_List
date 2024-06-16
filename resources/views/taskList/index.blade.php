@extends('layouts.app')

@section('content')
    @foreach ($taskLists as $taskList)
        {{ $taskList->id }} - {{ $taskList->name }}
    @endforeach
@endsection
