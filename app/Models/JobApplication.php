<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JobApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_name',
        'job_title',
        'offer_url',
        'offer_salary_from',
        'offer_salary_to',
        'expected_salary_from',
        'expected_salary_to',
        'application_date',
        'notes',
        'status',
    ];

    public function recruitmentEvents(): HasMany
    {
        return $this->hasMany(RecruitmentEvent::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

}
