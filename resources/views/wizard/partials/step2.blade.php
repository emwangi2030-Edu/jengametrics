<h4 class="text-center mb-4">{{ __('Step 2: Confirm and Submit') }}</h4>

<form action="{{ route('wizard.complete') }}" method="POST">
    @csrf

    <div class="mb-3">
        <label for="name" class="form-label">{{ __('Project Name:') }}</label>
        <input type="text" id="name" name="name" class="form-control-plaintext" value="{{ session('name') }}" readonly>
    </div>

    <div class="mb-3">
        <label for="description" class="form-label">{{ __('Project Description:') }}</label>
        <input type="text" id="description" name="description" class="form-control-plaintext" value="{{ session('description') }}" readonly>
    </div>

    <div class="mb-3">
        <label for="address" class="form-label">{{ __('Project Address:') }}</label>
        <input type="text" id="address" name="address" class="form-control-plaintext" value="{{ session('address') }}" readonly>
    </div>

    <div class="mb-3">
        <label for="budget" class="form-label">{{ __('Budget:') }}</label>
        <input type="text" id="budget" name="budget" class="form-control-plaintext" value="{{ session('budget') }}" readonly>
    </div>

    <div class="d-flex justify-content-between">
        <a href="{{ route('wizard', ['step' => 1]) }}" class="btn btn-warning">
            {{ __('Edit') }}
        </a>
        <button type="submit" class="btn btn-success">{{ __('Complete') }}</button>
    </div>
</form>
