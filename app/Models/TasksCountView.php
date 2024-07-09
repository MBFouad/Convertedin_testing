<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TasksCountView extends Model
{
    use HasFactory;
    // Specify the table name if it differs from the default naming convention
    protected $table = 'tasks_count_view';

    // Disable timestamps management
    public $timestamps = false;
    public function assignTo(): BelongsTo
    {

        return $this->belongsTo(User::class, 'assign_to');
    }
}
