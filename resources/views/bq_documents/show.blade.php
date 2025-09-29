@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="font-weight-bold" style="color:#027333">
                    Bill of Quantities: <span class="text-black">{{ get_project()->name }}</span>
                </h2>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-11">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <!-- Document Details -->
                        <div class="mt-4">
                        
                            <!-- Link to create a new section -->
                            <a href="{{ route('bq_sections.create') }}" class="btn btn-success mt-2">
                                {{ __('Add New Item') }}
                            </a>

                            <!-- Sections List -->
                            <div class="mt-5">
                                @if($sections->isEmpty())
                                    <p class="text-muted">{{ __('No sections found.') }}</p>
                                @else
                                    <div class="table-responsive mt-3">
                                        <table class="table table-sm table-hover align-middle">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>{{ __('Section') }}</th>
                                                    <th class="text-center">{{ __('Items') }}</th>
                                                    <th class="text-end">{{ __('Amount (KES)') }}</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($sections as $section)
                                                    @php
                                                        $items_count = \App\Models\BqSection::whereProjectId(project_id())
                                                            ->where('section_id', $section->id)
                                                            ->count();
                                                        $section_total = \App\Models\BqSection::whereProjectId(project_id())
                                                            ->where('section_id', $section->id)
                                                            ->sum('amount');
                                                    @endphp
                                                    <tr>
                                                        <td class="fw-semibold p-2">{{ $section->name }}</td>
                                                        <td class="text-center">
                                                            <span class="badge rounded-pill text-bg-secondary">{{ $items_count }}</span>
                                                        </td>
                                                        <td class="text-end fw-bold">{{ number_format($section_total, 2) }}</td>
                                                        <td class="text-end">
                                                            <a href="{{ route('section.show',$section->id) }}" class="btn btn-outline-primary btn-sm">
                                                                {{ __('View Section') }}
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        <div class="bg-secondary bg-opacity-10 text-black border-0 rounded p-2">
                                            <div class="d-flex justify-content-end">
                                                <div class="fw-bold">{{ __('TOTAL AMOUNT') }}: <span class="ms-2">KES {{ number_format($totalAmount, 2) }}</span></div>
                                            </div>
                                        </div>
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

