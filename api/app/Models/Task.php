<?php

namespace App\Models;
use App\Models\Project;
use App\Models\TaskComment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'description', 'priority', 'status', 'category_id', 'user_id', 'due_date'];

    public function category() {
        return $this->belongsTo(Project::class);
    }

    public function comments(){
        return $this->hasMany(TaskComment::class, 'task_id');
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
