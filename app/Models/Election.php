<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Election extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'start_date',
        'end_date',
        'is_active',
        'status',
        'total_registered_voters',
        'total_votes_cast',
        'voting_percentage',
        'results_published',
        'results_published_at',
        'allow_abstain',
        'show_live_results',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_active' => 'boolean',
        'results_published' => 'boolean',
        'results_published_at' => 'datetime',
        'allow_abstain' => 'boolean',
        'show_live_results' => 'boolean',
        'voting_percentage' => 'decimal:2',
    ];

    public function parties(): HasMany
    {
        return $this->hasMany(Party::class);
    }

    public function positions(): HasMany
    {
        return $this->hasMany(Position::class);
    }

    public function candidates(): HasMany
    {
        return $this->hasMany(Candidate::class);
    }

    public function votes(): HasMany
    {
        return $this->hasMany(Vote::class);
    }
}
