<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TaskFile extends Model
{
    use HasFactory;
    protected $fillable = ['task_id', 'file_name', 'file_path', 'uploaded_by'];
}
