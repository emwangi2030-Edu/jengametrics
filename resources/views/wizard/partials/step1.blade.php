<h4 class="text-center mb-4">{{ __('Project Details') }}</h4>

<form action="{{ route('wizard.step1.post') }}" method="POST">
    @csrf
    @php
        $budgetRaw = old('budget', session('budget'));
    @endphp

    <div class="mb-3">
        <label for="project_uid" class="form-label">{{ __('Project ID:') }}</label>
        <input type="text" id="project_uid" name="project_uid" class="form-control" required maxlength="100"
            placeholder="{{ __('Enter unique project ID (letters, numbers, dashes, underscores)') }}"
            value="{{ old('project_uid', session('project_uid')) }}">
        @error('project_uid')
            <div class="text-danger mt-1">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="name" class="form-label">{{ __('Project Name:') }}</label>
        <input type="text" id="name" name="name" class="form-control" required placeholder="{{ __('Enter project name') }}"
            value="{{ old('name', session('name')) }}">
    </div>

    <div class="mb-3">
        <label for="description" class="form-label">{{ __('Description:') }}</label>
        <textarea id="description" name="description" class="form-control" rows="3" required
            placeholder="{{ __('Provide a brief project description') }}">{{ old('description', session('description')) }}</textarea>
    </div>

    <div class="mb-3">
        <label for="budget_display" class="form-label">{{ __('Project Budget:') }}</label>
        <input type="text" id="budget_display" class="form-control" required placeholder="{{ __('Enter project budget') }}"
            value="{{ $budgetRaw }}" inputmode="decimal" autocomplete="off">
        <input type="hidden" id="budget" name="budget" value="{{ $budgetRaw }}">
        <small class="text-muted">{{ __('Display uses comma separators. Stored value remains unformatted.') }}</small>
        @error('budget')
            <div class="text-danger mt-1">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-4">
        <label for="address" class="form-label">{{ __('Project Address:') }}</label>
        <input type="text" id="address" name="address" class="form-control" required placeholder="{{ __('Enter project address') }}"
            value="{{ old('address', session('address')) }}">
    </div>

    <div class="d-flex justify-content-end">
        <button type="submit" class="btn btn-success">{{ __('Next') }}</button>
    </div>
</form>
