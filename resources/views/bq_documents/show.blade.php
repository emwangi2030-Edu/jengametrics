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
                        <div class="mt-4">
                        
                            <!-- Link to create a new section -->
                            <a href="{{ route('bq_sections.create') }}" class="btn btn-success mt-4">
                                {{ __('Add New Item') }}
                            </a>

                            <!-- Sections List -->
                            <div class="mt-5">
                                @if($sections->isEmpty())
                                    <p class="text-muted">{{ __('No sections found.') }}</p>
                                @else
                                    <div class="table-responsive mt-3">
                                        <table class="table table-striped">
                                            <thead class="thead-dark">
                                                <tr>
                                                    <th>{{ __('Section') }}</th>
                                                    <th>{{ __('') }}</th>
                                                    <th>{{ __('Amount') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($sections as $section)
                                                    @php
                                                    $items_count = \App\Models\BqSection::whereProjectId(project_id())->where('section_id', $section->id)->count();
                                                    $section_total = \App\Models\BqSection::whereProjectId(project_id())->where('section_id', $section->id)->sum('amount')
                                                    @endphp
                                                    <tr>
                                                        <td class="fw-bold">{{ $section->name }}</td>
                                                        <td>
                                                            <a href="{{ route('section.show',$section->id) }}" class="btn btn-outline-primary btn-sm">
                                                                {{ __('View Section') }} ({{ $items_count }})
                                                            </a>
                                                        </td>
                                                        <td class="fw-bold">{{ $section_total }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        <table class="table table-borderless">
                                            <thead class="p-0 m-0">
                                                <tr class="p-0 m-0">
                                                    <th class="p-0 m-0">{{ __('') }}</th>
                                                    <th class="p-0 m-0">{{ __('') }}</th>
                                                    <th class="p-0 m-0">{{ __('') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td class="fw-bold">{{ __('TOTAL AMOUNT') }}</td>
                                                    <td></td>
                                                    <td class="fw-bold text-center">{{ $totalAmount }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
