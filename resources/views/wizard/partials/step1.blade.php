<h4 class="text-center wizard-form-title">{{ __('Project Details') }}</h4>
<p class="text-center text-muted mb-4">Enter core information to create and track this project.</p>

<form action="{{ route('wizard.step1.post') }}" method="POST" class="wizard-step-form">
    @csrf
    @php
        $budgetRaw = old('budget', session('budget'));
    @endphp

    <div class="mb-3">
        <label for="project_uid" class="form-label wizard-input-label">{{ __('Project ID:') }}</label>
        <input type="text" id="project_uid" name="project_uid" class="form-control" required maxlength="100"
            placeholder="{{ __('Enter unique project ID (letters, numbers, dashes, underscores)') }}"
            value="{{ old('project_uid', session('project_uid')) }}">
        @error('project_uid')
            <div class="text-danger mt-1">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="name" class="form-label wizard-input-label">{{ __('Project Name:') }}</label>
        <input type="text" id="name" name="name" class="form-control" required placeholder="{{ __('Enter project name') }}"
            value="{{ old('name', session('name')) }}">
        @error('name')
            <div class="text-danger mt-1">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="description" class="form-label wizard-input-label">{{ __('Description:') }}</label>
        <textarea id="description" name="description" class="form-control" rows="3" required
            placeholder="{{ __('Provide a brief project description') }}">{{ old('description', session('description')) }}</textarea>
        @error('description')
            <div class="text-danger mt-1">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="project_duration" class="form-label wizard-input-label">{{ __('Project Duration (Weeks):') }}</label>
        <input type="number" id="project_duration" name="project_duration" class="form-control" required min="1"
            placeholder="Enter project duration in weeks"
            value="{{ old('project_duration', session('project_duration')) }}">
        @error('project_duration')
            <div class="text-danger mt-1">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="budget_display" class="form-label wizard-input-label">{{ __('Project Budget:') }}</label>
        <input type="text" id="budget_display" class="form-control" required placeholder="{{ __('Enter project budget') }}"
            value="{{ $budgetRaw }}" inputmode="decimal" autocomplete="off">
        <input type="hidden" id="budget" name="budget" value="{{ $budgetRaw }}">
        @error('budget')
            <div class="text-danger mt-1">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-4">
        <label for="address" class="form-label wizard-input-label">{{ __('Project Address:') }}</label>
        <input type="text" id="address" name="address" class="form-control" required placeholder="{{ __('Enter project address') }}"
            value="{{ old('address', session('address')) }}">
        @error('address')
            <div class="text-danger mt-1">{{ $message }}</div>
        @enderror
    </div>

    <div class="d-flex justify-content-end">
        <button type="submit" class="btn btn-success wizard-primary-btn">{{ __('Next') }}</button>
    </div>
</form>
