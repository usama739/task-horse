<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Category;
use App\Models\User;
use App\Jobs\UploadTaskFileJob;

class TaskController extends Controller {
    public function getEvents() {
        if (auth()->user()->can('user')) {
            $tasks = Task::where('user_id', auth()->id())->whereNotNull('due_date')->get();
        } else {
            $tasks = Task::whereNotNull('due_date')->get();
        }

        $events = [];
        foreach ($tasks as $task) {
            $priorityClass = $task->priority == 'High' ? 'fc-event-high' 
                       : ($task->priority == 'Medium' ? 'fc-event-medium' 
                       : 'fc-event-low');
            $events[] = [
                'title' => $task->title,
                'start' => $task->due_date, // Use due_date as event date
                'classNames' => [$priorityClass], 
            ];
        }
    
        return response()->json($events);
    }


    public function index() {
        if (auth()->user()->can('user')) {
            $tasks = Task::where('user_id', auth()->id())->orderBy('created_at', 'desc')->get();
            $users = []; 
        } else {
            $tasks = Task::orderBy('created_at', 'desc')->get();

            if (auth()->user()->role == 'admin') {
                $users = User::whereIn('role', ['manager', 'user'])->get();
            } else {
                $users = User::where('role', 'user')->get();
            }
        }

         // Count tasks based on status
        $pendingCount = $tasks->where('status', 'Pending')->count();
        $inProgressCount = $tasks->where('status', 'In-Progress')->count();
        $completedCount = $tasks->where('status', 'Completed')->count();

        $categories = Category::all();
        return view('tasks.index', compact('tasks', 'categories', 'users', 'pendingCount', 'inProgressCount', 'completedCount'));
    }


    public function store(Request $request) {
        if (auth()->user()->role == 'user') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

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
                UploadTaskFileJob::dispatch($path, $task->id, auth()->id());
            }
        }

        return redirect()->route('tasks.index')->with('success', 'Task added successfully.');
    }
    

    public function update(Request $request, Task $task) {
        if (auth()->user()->role == 'user') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|string|in:Pending,In-Progress,Completed',
            'priority' => 'required|in:Low,Medium,High',
            'category_id' => 'nullable|exists:categories,id',
            'user_id' => 'required|exists:users,id',
            'due_date' => 'nullable|date'
        ]);

        $task->update($request->all());
        return redirect()->route('tasks.index')->with('success', 'Task updated successfully.');
    }


    public function destroy(Task $task) {
        if (auth()->user()->role == 'user') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        $task->delete();
        return redirect()->route('tasks.index')->with('success', 'Task deleted successfully.');
    }


    public function show($id) {
        $task = Task::with('comments.user')->findOrFail($id);
        return view('tasks.show', compact('task'));
    }


   
}
