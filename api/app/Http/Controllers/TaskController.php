<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Project;
use App\Models\User;
use App\Jobs\UploadTaskFileJob;

class TaskController extends Controller {
    // public function getEvents() {
    //     if (auth()->user()->can('user')) {
    //         $tasks = Task::where('user_id', auth()->id())->whereNotNull('due_date')->get();
    //     } else {
    //         $tasks = Task::whereNotNull('due_date')->get();
    //     }

    //     $tasks = Task::whereNotNull('due_date')->get();

    //     $events = [];
    //     foreach ($tasks as $task) {
    //         $priorityClass = $task->priority == 'High' ? 'fc-event-high' 
    //                    : ($task->priority == 'Medium' ? 'fc-event-medium' 
    //                    : 'fc-event-low');
    //         $events[] = [
    //             'title' => $task->title,
    //             'start' => $task->due_date, // Use due_date as event date
    //             'classNames' => [$priorityClass], 
    //         ];
    //     }
    
    //     return response()->json($events);
    // }

    public function index() {
        $query = Task::query();
        if(auth()->user()->role == 'member'){
            $query->where('user_id', auth()->user()->id);
        }
        
        //get all tasks with project and user name
        $tasks = $query->with(['project', 'user'])
        ->whereHas('project', function($query) { 
            $query->where('organization_id', auth()->user()->organization_id); 
        })
        ->orderBy('due_date', 'asc')->get();
        return response()->json($tasks);
    }


    public function store(Request $request) {
        // if (auth()->user()->role == 'user') {
        //     return response()->json(['error' => 'Unauthorized'], 403);
        // }

        // $request->validate([
        //     'title' => 'required|string|max:255',
        //     'description' => 'nullable|string',
        //     'status' => 'required|string|in:Pending,In-progress,Completed',
        //     'priority' => 'required|in:Low,Medium,High',
        //     'category_id' => 'nullable|exists:categories,id',
        //     'user_id' => 'required|exists:users,id',
        //     'due_date' => 'nullable|date',
        // ]);

        $task = Task::create($request->all());
       
        // Handle multiple file uploads via queue
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $originalName = $file->getClientOriginalName();
                $path = $file->storeAs('temp', $originalName, 'local');
                UploadTaskFileJob::dispatch($path, $task->id, auth()->user()->id);
            }
        }

        return response()->json(['success' => 'Task added successfully.'], 201);
    }
    

    public function update(Request $request, Task $task) {
        // if (auth()->user()->role == 'user') {
        //     return response()->json(['error' => 'Unauthorized'], 403);
        // }

        // $request->validate([
        //     'title' => 'required|string|max:255',
        //     'description' => 'nullable|string',
        //     'status' => 'required|string|in:Pending,In-Progress,Completed',
        //     'priority' => 'required|in:Low,Medium,High',
        //     'category_id' => 'nullable|exists:categories,id',
        //     'user_id' => 'required|exists:users,id',
        //     'due_date' => 'nullable|date'
        // ]);

        $task->update($request->all());
        return response()->json(['success' => 'Task updated successfully.'], 200);
        // return redirect()->route('tasks.index')->with('success', 'Task updated successfully.');
    }


    public function destroy(Task $task) {
        // if (auth()->user()->role == 'user') {
        //     return response()->json(['error' => 'Unauthorized'], 403);
        // }
        
        $task->delete();
        return response()->json(['success' => 'Task deleted successfully.'], 200);
        // return redirect()->route('tasks.index')->with('success', 'Task deleted successfully.');
    }


    public function show($id) {
        $task = Task::with('comments.user')->findOrFail($id);           // there is also relaton between comments ans user
        return response()->json($task);
    }


   
}
