<h4 class="text-center mb-4">{{ __('Step 2: Confirm and Submit') }}</h4>

<form action="{{ route('wizard.complete') }}" method="POST">
    @csrf
    @php
        $wizardProject = $wizardProject ?? [];
        $projectUid = $wizardProject['project_uid'] ?? old('project_uid', session('project_uid'));
        $projectName = $wizardProject['name'] ?? old('name', session('name'));
        $projectDescription = $wizardProject['description'] ?? old('description', session('description'));
        $projectDuration = $wizardProject['project_duration'] ?? old('project_duration', session('project_duration'));
        $projectAddress = $wizardProject['address'] ?? old('address', session('address'));
        $projectBudgetRaw = $wizardProject['budget'] ?? old('budget', session('budget'));
        $projectBudgetNormalized = preg_replace('/,/', '', (string) $projectBudgetRaw);
        if (is_numeric($projectBudgetNormalized)) {
            $projectBudgetDisplay = number_format((float) $projectBudgetNormalized, 2, '.', ',');
            $projectBudgetDisplay = rtrim(rtrim($projectBudgetDisplay, '0'), '.');
        } else {
            $projectBudgetDisplay = $projectBudgetRaw;
        }
    @endphp

    <div class="mb-3">
        <label for="project_uid" class="form-label">{{ __('Project ID:') }}</label>
        <input type="text" id="project_uid" name="project_uid" class="form-control" value="{{ $projectUid }}" required maxlength="100" readonly>
        @error('project_uid')
            <div class="text-danger mt-1">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="name" class="form-label">{{ __('Project Name:') }}</label>
        <input type="text" id="name" name="name" class="form-control" value="{{ $projectName }}" readonly>
        @error('name')
            <div class="text-danger mt-1">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="description" class="form-label">{{ __('Project Description:') }}</label>
        <textarea id="description" name="description" class="form-control" rows="3" readonly>{{ $projectDescription }}</textarea>
        @error('description')
            <div class="text-danger mt-1">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="project_duration" class="form-label">{{ __('Project Duration (Weeks):') }}</label>
        <input type="text" id="project_duration" name="project_duration" class="form-control" value="{{ $projectDuration }}" readonly>
        @error('project_duration')
            <div class="text-danger mt-1">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="address" class="form-label">{{ __('Project Address:') }}</label>
        <input type="text" id="address" name="address" class="form-control" value="{{ $projectAddress }}" readonly>
        @error('address')
            <div class="text-danger mt-1">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="budget" class="form-label">{{ __('Budget:') }}</label>
        <input type="text" id="budget_display" class="form-control" value="{{ $projectBudgetDisplay }}" readonly>
        <input type="hidden" id="budget" name="budget" value="{{ $projectBudgetNormalized }}">
        @error('budget')
            <div class="text-danger mt-1">{{ $message }}</div>
        @enderror
    </div>

    <div class="d-flex justify-content-between">
        <a href="{{ route('wizard', ['step' => 1]) }}" class="btn btn-warning">
            {{ __('Edit') }}
        </a>
        <button type="submit" class="btn btn-success">{{ __('Complete') }}</button>
    </div>
</form>
