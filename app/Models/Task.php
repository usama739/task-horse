<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Task extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'description', 'priority', 'status', 'category_id', 'user_id', 'due_date'];

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function comments(){
        return $this->hasMany(TaskComment::class, 'task_id');
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
