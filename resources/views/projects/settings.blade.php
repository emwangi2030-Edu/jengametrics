@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-12 d-flex flex-column flex-md-row justify-content-between align-items-md-center">
            <div>
                <h2 class="fw-bold mb-1" style="color:#027333;">{{ __('Project Settings') }}</h2>
            </div>
            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary mt-3 mt-md-0">{{ __('Back') }}</a>
        </div>
    </div>

    <div class="card shadow-sm border-0 m-7">
        <div class="card-body">
            <h5 class="fw-bold mb-3" style="color:#027333;">{{ __('Project Information') }}</h5>
            @if(isset($project))
                <form method="POST" action="{{ route('projects.settings.update') }}">
                    @csrf
                    @method('PATCH')
                    <div class="mb-3">
                        <label class="form-label" for="project-uid">{{ __('Project ID') }}</label>
                        <input type="text" class="form-control" id="project-uid" name="project_uid" value="{{ old('project_uid', $project->project_uid) }}" required maxlength="100">
                        <small class="text-muted">{{ __('Use letters, numbers, dashes, or underscores.') }}</small>
                        @error('project_uid')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="project-name">{{ __('Project Name') }}</label>
                        <input type="text" class="form-control" id="project-name" name="name" value="{{ old('name', $project->name) }}" required maxlength="255">
                        @error('name')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="project-description">{{ __('Description') }}</label>
                        <textarea class="form-control" id="project-description" name="description" rows="3">{{ old('description', $project->description) }}</textarea>
                        @error('description')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="project-duration">{{ __('Project Duration (Weeks)') }}</label>
                        <input type="number" class="form-control" id="project-duration" name="project_duration" min="1"
                            placeholder="Enter project duration in weeks" value="{{ old('project_duration', $project->project_duration) }}">
                        @error('project_duration')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="project-budget">{{ __('Budget') }}</label>
                        <input type="text" class="form-control" id="project-budget" name="budget" value="{{ old('budget', $project->budget) }}" maxlength="255">
                        @error('budget')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="project-address">{{ __('Address') }}</label>
                        <input type="text" class="form-control" id="project-address" name="address" value="{{ old('address', $project->address) }}" maxlength="255">
                        @error('address')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-success">{{ __('Save Changes') }}</button>
                </form>
                @if(!auth()->user()->isSubAccount())
                    <form method="POST" action="{{ route('projects.destroy', $project->id) }}" class="mt-2" data-confirm-message="{{ __('Are you sure you want to delete this project?') }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">{{ __('Delete Project') }}</button>
                    </form>
                @endif
            @else
                <p class="mb-0 text-muted">{{ __('No project selected.') }}</p>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    (function () {
        const form = document.querySelector('form[action="{{ route('projects.settings.update') }}"]');
        const projectIdInput = document.getElementById('project-uid');
        const checkUrl = "{{ route('projects.check_uid') }}";
        const currentProjectId = "{{ $project->id ?? '' }}";

        if (!form || !projectIdInput) {
            return;
        }

        const checkAvailability = async () => {
            const value = (projectIdInput.value || '').trim();
            if (!value) {
                return true;
            }

            try {
                const query = new URLSearchParams({
                    project_uid: value,
                    ignore_project_id: currentProjectId
                });

                const response = await fetch(`${checkUrl}?${query.toString()}`, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });

                if (!response.ok) {
                    return true;
                }

                const payload = await response.json();
                if (payload.exists) {
                    window.alert('This ID already exists');
                    projectIdInput.focus();
                    return false;
                }

                return true;
            } catch (error) {
                return true;
            }
        };

        projectIdInput.addEventListener('blur', checkAvailability);

        form.addEventListener('submit', async function (event) {
            const isAvailable = await checkAvailability();
            if (!isAvailable) {
                event.preventDefault();
                event.stopPropagation();
            }
        });
    })();
</script>
@endpush
