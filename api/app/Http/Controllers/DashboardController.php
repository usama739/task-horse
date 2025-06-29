<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function taskStatusTrend(){
        $startDate = Carbon::now()->subDays(29)->startOfDay();  // 30 days including today

        $tasks = Task::where('created_at', '>=', $startDate)
            ->select(
                DB::raw("DATE(created_at) as date"),
                DB::raw("status"),
                DB::raw("COUNT(*) as count")
            )
            ->groupBy(DB::raw("DATE(created_at)"), "status")
            ->orderBy("date", "ASC")
            ->get();

        // return response()->json($tasks);

        // Transform to structured array
        $result = [];

        // Initialize empty data for each day
        for ($i = 0; $i < 30; $i++) {
            $day = Carbon::now()->subDays(29 - $i)->toDateString();
            $result[$day] = [
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


    public function completedTasksByUser(){
        $users = User::all(['id', 'name']); 
        $data = [];

        foreach ($users as $user) {
            $completedCount = Task::where('user_id', $user->id)
                                ->where('status', 'Completed')
                                ->count();

            $data[] = [
                'id' => $user->id,
                'name' => $user->name,
                'completed_tasks_count' => $completedCount,
            ];
        }

        return response()->json($data);
    }


    public function tasksByPriority(){
        $tasks = Task::selectRaw('priority, COUNT(*) as count')
                ->groupBy('priority')
                ->get();

        return response()->json($tasks);
    }


    public function tasksByProject()
    {
        $tasks = Task::with('project')
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
}
