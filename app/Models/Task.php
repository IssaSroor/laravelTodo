<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    use HasFactory;

    // Specify the table name if it differs from the plural of the model name
    protected $table = 'tasks';

    // Define which fields are mass-assignable
    protected $fillable = [
        'title',
        'status',
        'user_id'
    ];

    // Define the relationship with the User model
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function subtasks()
{
    return $this->hasMany(Subtask::class);
}
}
