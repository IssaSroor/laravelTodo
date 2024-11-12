<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subtask extends Model
{

    use HasFactory;
    protected $table = 'sub_tasks';
    protected $fillable = [
        'user_id', 
        'task_id', 
        'subtask_name', 
        'description', 
        'status'
    ];

    public function task()
{
    return $this->belongsTo(Task::class);
}
}
