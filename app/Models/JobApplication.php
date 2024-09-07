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

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'company_name' => $this->company_name,
            'job_title' => $this->job_title,
            'offer_url' => $this->offer_url,
            'offer_salary_from' => $this->offer_salary_from,
            'offer_salary_to' => $this->offer_salary_to,
            'expected_salary_from' => $this->expected_salary_from,
            'expected_salary_to' => $this->expected_salary_to,
            'application_date' => $this->application_date,
            'notes' => $this->notes,
            'status' => $this->status,
            'last_update_date' => $this->lastUpdateDate(),
        ];
    }

    public function recruitmentEvents(): HasMany
    {
        return $this->hasMany(RecruitmentEvent::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function lastUpdateDate(): string
    {
        if ($this->recruitmentEvents->count() > 0) {
            return $this->recruitmentEvents->sortByDesc('date')->first()->date;
        }
        return $this->application_date;
    }

}
