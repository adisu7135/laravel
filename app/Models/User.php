<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    const TYPE_ADMIN = 'admin';
    const TYPE_COMPANY = 'company';
    const TYPE_APPLICANT = 'applicant';

    use HasApiTokens;
    use HasFactory;
    use Notifiable;

    protected $fillable = [
        'username',
        'email',
        'password',
        'user_type'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Corrected job applications relationship
    public function jobApplications()
    {
        return $this->belongsToMany(Job::class, 'job_applications', 'user_id', 'job_id')
            ->withPivot('id', 'company_id', 'cv_file', 'status')
            ->withTimestamps();
    }

    // Alias for better readability
    public function appliedJobs()
    {
        return $this->jobApplications();
    }

    public function company()
    {
        return $this->hasOne(Company::class);
    }

    public function university()
    {
        return $this->hasOne(University::class);
    }

    // Helper methods for user types
    public function isAdmin()
    {
        return $this->user_type === self::TYPE_ADMIN;
    }

    public function isCompany()
    {
        return $this->user_type === self::TYPE_COMPANY;
    }

    public function isApplicant()
    {
        return $this->user_type === self::TYPE_APPLICANT;
    }
}
