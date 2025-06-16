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
            'user_id' => auth()->id(),
            'comment' => $request->comment
        ]);

        return back();
    }

    public function destroy($id) {
        $comment = TaskComment::findOrFail($id);

        if ($comment->user_id == auth()->id() || auth()->user()->role == 'admin') {
            $comment->delete();
        }

        return back();
    }
}
