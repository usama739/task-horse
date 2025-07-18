<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Project extends Model
{
    use HasFactory;
    
    protected $fillable = ['name', 'organization_id'];

    public function tasks(){
        return $this->hasMany(Task::class);
    }

}
