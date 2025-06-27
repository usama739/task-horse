<?php

namespace App\Http\Controllers;

use App\Models\TaskComment;
use Illuminate\Http\Request;

class TaskCommentController extends Controller
{
    public function store(Request $request, $id){
        $request->validate(['comment' => 'required']);

        TaskComment::create([
            'task_id' => $id,
            'user_id' => '1',
            'comment' => $request->comment
        ]);

        return response()->json(['message' => 'Comment added successfully.'], 201);
    }


    public function destroy($id) {
        $comment = TaskComment::findOrFail($id);
            $comment->delete();


        // if ($comment->user_id == auth()->id() || auth()->user()->role == 'admin') {
        //     $comment->delete();
        // }

        return response()->json(['message' => 'Comment deleted successfully.'], 200);
    }
}
