<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskCollection;
use App\Http\Resources\TaskResource;
use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use Spatie\QueryBuilder\QueryBuilder;

class TaskController extends Controller
{

    public function index(Request $request){
        // new TaskCollection(Task::paginate())

        
        // this will allow us to filter via is_done and sort via lastly created_at time stamp

        // api/tasks?filter[is_done]=0 (uncompleted )
        // $tasks = QueryBuilder::for(Task::class)
        //     ->allowedFilters('is_done')
        //     ->allowedSort('-created_at')
        //     ->allowedSorts(['title', 'is_done', 'created_at']) // api/tasks?sort=is_done,-title (sorted by is_done then title)
        //     ->paginate();

        return new TaskCollection(Task::all());
    }

    // return single resource
    // api/tasks/1
    public function show(Request $request, Task $task){
        return new TaskResource($task);
    }

    public function store(StoreTaskRequest $request){

        $validated = $request->validated();

        //associate task with signed in user
        $task = Auth::user()->tasks()->create($validated);
        // $task = Task::create($validate);

        return new TaskResource($task);
    }

    // api/tasks/2
    // Using route model binding
    public function update(UpdateTaskRequest $request, Task $task){

        $validate = $request->validated();

        $task->update($validate);

        return new TaskResource($task);
    }

    public function destory(Request $request, Task $task){
        $task->delete();
        return response()->noContent();
    }
}
