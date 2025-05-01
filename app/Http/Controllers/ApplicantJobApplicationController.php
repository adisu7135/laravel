<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\JobApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ApplicantJobApplicationController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:applicant']);
    }

    public function index()
    {
        $job_applications = auth()->user()->jobApplications()
            ->with(['job.company'])
            ->latest()
            ->paginate(10);

        return view('applicant.job-applications.index', compact('job_applications'));
    }

    public function create(Job $job)
    {
        if (auth()->user()->jobApplications()->where('job_id', $job->id)->exists()) {
            return redirect()->route('applicant.job.show', $job->id)
                ->with('error', 'You have already applied to this job');
        }

        return view('applicant.job-applications.create', compact('job'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'job_id' => 'required|exists:jobs,id',
            'cv_file' => 'required|file|mimes:pdf,doc,docx|max:2048',
            'cover_letter' => 'nullable|string|max:2000'
        ]);

        // Store CV file
        $fileName = $this->storeCvFile($request->file('cv_file'));

        // Create application
        JobApplication::create([
            'user_id' => auth()->id(),
            'job_id' => $request->job_id,
            'company_id' => Job::find($request->job_id)->company_id,
            'cv_file' => $fileName,
            'cover_letter' => $request->cover_letter,
            'status' => 'pending'
        ]);

        return redirect()->route('applicant.job-application.index')
            ->with('success', 'Application submitted successfully!');
    }

    public function destroy($id)
    {
        $application = auth()->user()->jobApplications()
            ->where('id', $id)
            ->firstOrFail();

        // Delete CV file
        Storage::disk('public')->delete('cv_files/' . $application->cv_file);

        $application->delete();

        return back()->with('success', 'Application withdrawn successfully');
    }

    protected function storeCvFile($file)
    {
        $fileName = auth()->id() . '_' . time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
        $file->storeAs('public/cv_files', $fileName);
        return $fileName;
    }
}
