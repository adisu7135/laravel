<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class JobApplication extends Pivot
{
    protected $table = 'job_applications';

    protected $fillable = [
        'user_id',
        'job_id',
        'company_id',
        'cv_file',
        'cover_letter',
        'status'
    ];

    public $incrementing = true;

    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
