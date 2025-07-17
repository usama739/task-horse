<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Task;
use App\Models\Project;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DashboardController extends Controller{
    public function taskStatusTrend(Request $request){
        $start = $request->start_date ? Carbon::parse($request->start_date)->startOfDay() : Carbon::now()->subDays(30)->startOfDay();
        $end = $request->end_date ? Carbon::parse($request->end_date)->endOfDay() : Carbon::now()->endOfDay();

        $query = Task::whereHas('project', function($query){
            $query->where('organization_id', auth()->user()->organization_id);
        })
        ->whereBetween('created_at', [$start, $end]);

        if ($request->has('project_id') && $request->project_id) {
            $query->where('project_id', $request->project_id);
        }

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        $tasks = $query->select(
                DB::raw("DATE(created_at) as date"),
                DB::raw("status"),
                DB::raw("COUNT(*) as count")
            )
            ->groupBy(DB::raw("DATE(created_at)"), "status")
            ->orderBy("date", "ASC")
            ->get();

        // Transform to structured array
        $result = [];

        // Initialize empty data for each day
        $period = Carbon::parse($start)->toPeriod(Carbon::parse($end));
        foreach ($period as $day) {
            $result[$day->toDateString()] = [
                'Created' => 0,
                'In-Progress' => 0,
                'Completed' => 0
            ];
        }

        foreach ($tasks as $task) {
            $result[$task->date][$task->status] = $task->count;
        }

        return response()->json($result);
    }


    public function completedTasksByUser(Request $request){
        $start = $request->start_date ? Carbon::parse($request->start_date)->startOfDay() : Carbon::now()->subDays(30)->startOfDay();
        $end = $request->end_date ? Carbon::parse($request->end_date)->endOfDay() : Carbon::now()->endOfDay();

        $users = User::all(['id', 'name']); 
        $data = [];
        foreach ($users as $user) {
            $query = Task::whereHas('project', function($query){
                $query->where('organization_id', auth()->user()->organization_id);
            })
            ->where('user_id', $user->id)->where('status', 'Completed');

            if ($start && $end) {
                $query->whereBetween('created_at', [$start, $end]);
            }
            if ($request->has('project_id') && $request->project_id) {
                $query->where('project_id', $request->project_id);
            }

            $data[] = [
                'id' => $user->id,
                'name' => $user->name,
                'completed_tasks_count' => $query->count(),
            ];
        }

        return response()->json($data);
    }


    public function tasksByPriority(Request $request){
        $query = Task::query();
        $start = $request->start_date ? Carbon::parse($request->start_date)->startOfDay() : Carbon::now()->subDays(30)->startOfDay();
        $end = $request->end_date ? Carbon::parse($request->end_date)->endOfDay() : Carbon::now()->endOfDay();

        $query = $query->whereHas('project', function($query){
            $query->where('organization_id', auth()->user()->organization_id);
        })
        ->whereBetween('created_at', [$start, $end]);

        if ($request->has('project_id') && $request->project_id) {
            $query->where('project_id', $request->project_id);
        }

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        $tasks = $query->selectRaw('priority, COUNT(*) as count')
                ->groupBy('priority')
                ->get();

        return response()->json($tasks);
    }


    public function tasksByProject(Request $request){
        $query = Task::query();
        $start = $request->start_date ? Carbon::parse($request->start_date)->startOfDay() : Carbon::now()->subDays(30)->startOfDay();
        $end = $request->end_date ? Carbon::parse($request->end_date)->endOfDay() : Carbon::now()->endOfDay();

        $query = $query->whereHas('project', function($query){
            $query->where('organization_id', auth()->user()->organization_id);
        })
        ->whereBetween('created_at', [$start, $end]);

         if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        $tasks = $query->with('project')
            ->selectRaw('project_id, COUNT(*) as count')
            ->groupBy('project_id')
            ->get()
            ->map(function ($task) {
                return [
                    'project' => $task->project->name ?? 'Unknown',
                    'count' => $task->count,
                ];
            });

        return response()->json($tasks);
    }


    public function taskPriorityCounts(Request $request){
        $query = Task::query();

        if ($request->has('project_id') && $request->project_id) {
            $query->where('project_id', $request->project_id);
        }

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        $start = Carbon::parse($request->start_date)->startOfDay();
        $end = Carbon::parse($request->end_date)->endOfDay();
        
        $query->whereHas('project', function($query){
            $query->where('organization_id', auth()->user()->organization_id);
        })
        ->whereBetween('created_at', [$start, $end]);
        
        return response()->json([
            'high' => $query->clone()->where('priority', 'High')->count(),
            'medium' => $query->clone()->where('priority', 'Medium')->count(),
            'low' => $query->clone()->where('priority', 'Low')->count(),
        ]);
    }


    public function overviewCounts(){
        $totalUsers = User::where('role', 'member')->where('organization_id', auth()->user()->organization_id)->count();
        $totalTasks = Task::whereHas('project', function($query) {
            $query->where('organization_id', auth()->user()->organization_id);
        })->count();
        $totalProjects = Project::where('organization_id', auth()->user()->organization_id)->count();

        return response()->json([
            'users' => $totalUsers,
            'tasks' => $totalTasks,
            'projects' => $totalProjects
        ]);
    }

}
