<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    use HasFactory;

    // restrict field that can be mass assigned
    protected $fillable = [
        'title',
        'is_done'
    ];

    protected $casts = [
        'is_done' => 'boolean'
    ];

    protected $hidden = [
        'updated_at'
    ];

    public function creator(): BelongsTo {
        return $this->belongTo(User::class, 'creator_id');
    }

}
