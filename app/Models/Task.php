<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Task extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'assign_to',
        'created_by',
    ];
    public function assignTo(): BelongsTo
    {

        return $this->belongsTo(User::class, 'assign_to');
    }
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
