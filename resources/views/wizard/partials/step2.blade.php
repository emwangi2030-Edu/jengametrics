<h4 class="text-center mb-4">{{ __('Step 2: Confirm and Submit') }}</h4>

<form action="{{ route('wizard.complete') }}" method="POST">
    @csrf
    @php
        $projectUid = old('project_uid', session('project_uid'));
        $projectName = old('name', session('name'));
        $projectDescription = old('description', session('description'));
        $projectAddress = old('address', session('address'));
        $projectBudgetRaw = old('budget', session('budget'));
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
        <input type="text" id="project_uid" name="project_uid" class="form-control" value="{{ $projectUid }}" required maxlength="100">
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
