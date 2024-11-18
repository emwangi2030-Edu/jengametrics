

@extends('layouts.appbar')

@section('content')
<div class="container mt-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="font-weight-bold text-success">
                {{ __('Bill of Quantities: ') . get_project()->name }}
            </h2>
        </div>
    </div>

    <div class="row">
        <div class="col-md-10">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <!-- Document Details -->
                    <p class="font-weight-bold text-dark">{{ __('Document Details') }}</p>
                    <div class="mt-4">
                      

                        <!-- Link to create a new section -->
                        <a href="{{ route('bq_sections.create') }}" class="btn btn-success mt-4">
                            {{ __('Add New Item') }}
                        </a>

                        <!-- Sections List -->
                        <div class="mt-5">
                            <h3 class="h5 font-weight-bold text-dark">{{ __('Sections') }}</h3>
                            @if($sections->isEmpty())
                                <p class="text-muted">{{ __('No sections found.') }}</p>
                            @else
                                <ul class="list-group mt-3">
                                    @foreach($sections as $section)
                                    @php
                                    $items_count = \App\Models\BqSection::whereProjectId(project_id())->where('section_id', $section->id)->count();
                                    @endphp
                                        <li class="list-group-item">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <p class="font-weight-bold mb-1">{{ $section->name }}</p>
                                                
                                                </div>

                                                <a href="{{ route('section.show',$section->id) }}" class="btn btn-outline-primary btn-sm">
                                                    {{ __('View Section') }} ({{ $items_count }})
                                                </a>
                                               
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

