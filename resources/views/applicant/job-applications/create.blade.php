@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-white pl-0">
            <li class="breadcrumb-item"><a href="{{ route('applicant.home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('applicant.job.index') }}">Jobs</a></li>
            <li class="breadcrumb-item"><a href="{{ route('applicant.job.show', $job->id) }}">{{ $job->title }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">Apply</li>
        </ol>
    </nav>
</div>

<div class="container">
    <div class="row d-flex justify-content-center">
        <div class="col-md-8">
            <div class="card card-default mt-4 shadow">
                <div class="card-header">
                    <h4><b>{{ __('Apply to') }} "{{ $job->title }}"</b></h4>
                </div>

                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('applicant.job-application.store') }}" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="job_id" value="{{ $job->id }}">

                        <div class="form-group">
                            <label for="cv_file">Upload Your CV (PDF/DOC/DOCX, max 2MB)</label>
                            <input type="file" name="cv_file" class="form-control-file @error('cv_file') is-invalid @enderror" id="cv_file" required>
                            
                            @error('cv_file')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="cover_letter">Cover Letter (Optional)</label>
                            <textarea name="cover_letter" class="form-control @error('cover_letter') is-invalid @enderror" id="cover_letter" rows="5">{{ old('cover_letter') }}</textarea>
                            
                            @error('cover_letter')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary btn-block">
                                    {{ __('Submit Application') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection