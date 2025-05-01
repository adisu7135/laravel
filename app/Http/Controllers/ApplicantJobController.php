<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ApplicantJobController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'role:applicant']);
    }

    /**
     * Display a listing of available jobs.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $jobs = Job::with(['company', 'applications'])
            ->where('status', 'active')
            ->latest()
            ->filter(request(['search', 'company', 'type']))
            ->paginate(10);

        $applied_job_ids = auth()->user()->jobApplications()
            ->pluck('job_id')
            ->toArray();

        return view('applicant.jobs.index', [
            'jobs' => $jobs,
            'applied_job_ids' => $applied_job_ids
        ]);
    }

    /**
     * Display the specified job.
     *
     * @param  \App\Models\Job  $job
     * @return \Illuminate\Http\Response
     */
    public function show(Job $job)
    {
        $job->load(['company', 'requirements', 'skills']);

        $has_applied = auth()->user()->jobApplications()
            ->where('job_id', $job->id)
            ->exists();

        return view('applicant.jobs.show', [
            'job' => $job,
            'has_applied' => $has_applied
        ]);
    }

    /**
     * Download applicant's CV file.
     *
     * @param  \App\Models\User  $user
     * @param  string  $filename
     * @return \Illuminate\Http\Response
     */
    public function downloadCv(User $user, $filename)
    {
        // Verify the authenticated user owns this CV
        if (auth()->id() !== $user->id) {
            abort(403);
        }

        $filepath = "cv_files/{$user->id}/{$filename}";

        if (!Storage::disk('public')->exists($filepath)) {
            abort(404);
        }

        return Storage::disk('public')->download($filepath);
    }

    /**
     * Search jobs based on filters.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $validated = $request->validate([
            'search' => 'nullable|string|max:255',
            'company' => 'nullable|string|max:255',
            'type' => 'nullable|in:full-time,part-time,contract,internship'
        ]);

        return redirect()->route('applicant.job.index', $validated);
    }
}
