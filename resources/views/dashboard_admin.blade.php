@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex align-items-center justify-content-between my-4">
        <h1 class="mb-0" style="color:#027333">Admin Dashboard</h1>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white border-0 pb-0">
            <h5 class="mb-0">Users</h5>
        </div>
        <div class="card-body">
            @if($users->isEmpty())
                <p class="text-muted mb-0">No users found.</p>
            @else
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Created</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $listedUser)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $listedUser->name }}</td>
                                    <td>{{ $listedUser->email }}</td>
                                    <td>{{ optional($listedUser->created_at)->format('M d, Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

