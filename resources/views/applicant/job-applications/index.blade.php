@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row pt-4">
        <div class="col-md-12">
            <h3>List of Jobs You Applied To</h3>
        </div>
    </div>
    
    <div class="row mt-4">
        @forelse($job_applications as $application)
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title mb-2">{{ $application->job->title }}</h5>
                    <p class="text-muted mb-2">
                        <small>{{ $application->job->company->name }}</small>
                    </p>
                    <p class="card-text">
                        {!! Str::limit($application->job->description, 100) !!}
                    </p>
                    
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <span class="badge badge-success">
                            <i class="fas fa-check"></i> Applied on 
                            {{ $application->created_at->format('M d, Y') }}
                        </span>
                        
                        <a href="{{ route('applicant.job.show', $application->job->id) }}" 
                           class="btn btn-sm btn-outline-primary">
                            View Details
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-md-12">
            <div class="alert alert-info">
                <h5><i class="fas fa-info-circle"></i> You haven't applied to any jobs yet!</h5>
                <p class="mb-0">Browse <a href="{{ route('applicant.job.index') }}">available jobs</a> to apply.</p>
            </div>
        </div>
        @endforelse
    </div>

    @if($job_applications->count())
    <div class="row pt-3">
        <div class="col-md-12">
            {{ $job_applications->links() }}
        </div>
    </div>
    @endif
</div>
@endsection