@extends('layouts.appbar')

@section('content')
    <h1>Create Class</h1>
    <form action="{{ route('classes.store') }}" method="POST">
        @csrf

        <div class="row">
            <!-- Form Selection -->
            <div class="col-md-6">
                <div class="form-group">
                    <label class="mb-1">Form <span class="text-danger">*</span></label>
                    <ng-select class="ng-select-searchable ng-select-clearable ng-select ng-select-single">
                        <div class="ng-select-container">
                            <div class="ng-value-container">
                                <div class="ng-placeholder">Select Form</div>
                                <div role="combobox" aria-haspopup="listbox" class="ng-input">
                                    <input aria-autocomplete="list" type="text" autocorrect="off" autocapitalize="none" autocomplete="a49a72ebeee0">
                                </div>
                            </div>
                            <span class="ng-arrow-wrapper">
                                <span class="ng-arrow"></span>
                            </span>
                        </div>
                    </ng-select>
                    <app-input-error-message></app-input-error-message>
                </div>
            </div>

            <!-- Stream Input -->
            <div class="col-md-6 pt-1">
                <div class="form-group">
                    <label class="control-label mb-1 text-capitalize">Stream(s) <span class="text-danger">*</span>
                        <i data-bs-toggle="tooltip" class="bi bi-question-circle-fill ms-1" title="You can add multiple streams at once using comma separation"></i>
                    </label>
                    <input class="form-control" type="text" placeholder="North, South, East" autocomplete="on">
                    <app-input-error-message></app-input-error-message>
                </div>
            </div>
        </div>

        <!-- Subjects Selection -->
        <div class="pb-2">
            <span class="mb-2">Select Subjects done by this class</span>
        </div>
        <div class="row">
            @foreach($subjects as $subject)
                <div class="col-md-3">
                    <h4 class="fw-bold h6 mb-2">{{ $subject->category }}</h4>
                    <input type="checkbox" class="filled-in my-2" id="{{ $subject->id }}" name="subjects[]" value="{{ $subject->id }}">
                    <label for="{{ $subject->id }}">{{ $subject->name }}</label>
                </div>
            @endforeach
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
@endsection
