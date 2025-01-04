<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

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

    // Establish the relationship between Task and User with creator_id
    public function creator(): BelongsTo {
        return $this->belongTo(User::class, 'creator_id');
    }

    public function project(): BelongsTo {
        return $this->belongsTo(Project::class);
    }

    // Applies a global query scope to the model. This scope ensures that whenever the model is queried, 
    // it automatically filters the results to include only records where the creator_id matches the ID of the currently authenticated user.
    protected static function booted(): void{
        static::addGlobalScope('creator', function (Builder $builder) {
            $builder->where('creator_id', Auth::id());
        });
    }

}
