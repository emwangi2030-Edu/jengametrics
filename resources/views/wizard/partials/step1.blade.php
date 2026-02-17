<h4 class="text-center mb-4">{{ __('Project Details') }}</h4>

<form action="{{ route('wizard.step1.post') }}" method="POST">
    @csrf

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
        <label for="budget" class="form-label">{{ __('Project Budget:') }}</label>
        <input type="text" id="budget" name="budget" class="form-control" required placeholder="{{ __('Enter project budget') }}"
            value="{{ old('budget', session('budget')) }}">
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
