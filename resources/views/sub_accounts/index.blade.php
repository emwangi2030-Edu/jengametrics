@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
                <div>
                    <h2 class="fw-bold text-primary mb-1">{{ __('Add Users') }}</h2>
                    <p class="text-muted mb-0">{{ __('Add and manage users linked to your account.') }}</p>
                </div>
                <button type="button" class="btn btn-success mt-3 mt-md-0" data-bs-toggle="modal" data-bs-target="#createSubAccountModal">
                    {{ __('Add User') }}
                </button>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-body">
                    @if($subAccounts->isEmpty())
                        <p class="text-muted mb-0">{{ __('No users added yet.') }}</p>
                    @else
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>{{ __('Name') }}</th>
                                        <th>{{ __('Email') }}</th>
                                        <th class="text-center">{{ __('BoQ/BoM') }}</th>
                                        <th class="text-center">{{ __('Materials') }}</th>
                                        <th class="text-center">{{ __('Labour') }}</th>
                                        <th class="text-end">{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($subAccounts as $subAccount)
                                        <tr>
                                            <td class="fw-semibold">{{ $subAccount->name }}</td>
                                            <td>{{ $subAccount->email }}</td>
                                            <td class="text-center">
                                                @if($subAccount->can_manage_boq)
                                                    <span class="badge bg-success">{{ __('Write') }}</span>
                                                @else
                                                    <span class="badge bg-secondary">{{ __('Read') }}</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if($subAccount->can_manage_materials)
                                                    <span class="badge bg-success">{{ __('Write') }}</span>
                                                @else
                                                    <span class="badge bg-secondary">{{ __('Read') }}</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if($subAccount->can_manage_labour)
                                                    <span class="badge bg-success">{{ __('Write') }}</span>
                                                @else
                                                    <span class="badge bg-secondary">{{ __('Read') }}</span>
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#editSubAccountModal{{ $subAccount->id }}">
                                                    {{ __('Edit') }}
                                                </button>
                                                <form action="{{ route('sub_accounts.destroy', $subAccount) }}" method="POST" class="d-inline ms-2" data-confirm-message="{{ __('Remove this sub-account?') }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">{{ __('Delete') }}</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="createSubAccountModal" tabindex="-1" aria-labelledby="createSubAccountModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('sub_accounts.store') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="createSubAccountModalLabel">{{ __('Add User') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('Close') }}"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label" for="sub-account-name">{{ __('Name') }}</label>
                        <input type="text" class="form-control" id="sub-account-name" name="name" required maxlength="255">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="sub-account-email">{{ __('Email') }}</label>
                        <input type="email" class="form-control" id="sub-account-email" name="email" required maxlength="255">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="sub-account-password">{{ __('Password') }}</label>
                        <input type="password" class="form-control" id="sub-account-password" name="password" required minlength="8">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="sub-account-password-confirmation">{{ __('Confirm Password') }}</label>
                        <input type="password" class="form-control" id="sub-account-password-confirmation" name="password_confirmation" required minlength="8">
                    </div>
                    <div class="mb-2 fw-semibold">{{ __('Role Access (Write)') }}</div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="role-boq" name="can_manage_boq" value="1">
                        <label class="form-check-label" for="role-boq">{{ __('Manage BoQ and BoM') }}</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="role-materials" name="can_manage_materials" value="1">
                        <label class="form-check-label" for="role-materials">{{ __('Manage Materials') }}</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="role-labour" name="can_manage_labour" value="1">
                        <label class="form-check-label" for="role-labour">{{ __('Manage Labour') }}</label>
                    </div>
                    <small class="text-muted d-block mt-2">{{ __('Unchecked roles will remain read-only.') }}</small>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('Create') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

@foreach($subAccounts as $subAccount)
    <div class="modal fade" id="editSubAccountModal{{ $subAccount->id }}" tabindex="-1" aria-labelledby="editSubAccountModalLabel{{ $subAccount->id }}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ route('sub_accounts.update', $subAccount) }}">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title" id="editSubAccountModalLabel{{ $subAccount->id }}">{{ __('Edit User') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('Close') }}"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label" for="edit-sub-account-name-{{ $subAccount->id }}">{{ __('Name') }}</label>
                            <input type="text" class="form-control" id="edit-sub-account-name-{{ $subAccount->id }}" name="name" value="{{ $subAccount->name }}" required maxlength="255">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="edit-sub-account-email-{{ $subAccount->id }}">{{ __('Email') }}</label>
                            <input type="email" class="form-control" id="edit-sub-account-email-{{ $subAccount->id }}" name="email" value="{{ $subAccount->email }}" required maxlength="255">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="edit-sub-account-password-{{ $subAccount->id }}">{{ __('New Password (optional)') }}</label>
                            <input type="password" class="form-control" id="edit-sub-account-password-{{ $subAccount->id }}" name="password" minlength="8">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="edit-sub-account-password-confirmation-{{ $subAccount->id }}">{{ __('Confirm Password') }}</label>
                            <input type="password" class="form-control" id="edit-sub-account-password-confirmation-{{ $subAccount->id }}" name="password_confirmation" minlength="8">
                        </div>
                        <div class="mb-2 fw-semibold">{{ __('Role Access (Write)') }}</div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="edit-role-boq-{{ $subAccount->id }}" name="can_manage_boq" value="1" @checked($subAccount->can_manage_boq)>
                            <label class="form-check-label" for="edit-role-boq-{{ $subAccount->id }}">{{ __('Manage BoQ and BoM') }}</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="edit-role-materials-{{ $subAccount->id }}" name="can_manage_materials" value="1" @checked($subAccount->can_manage_materials)>
                            <label class="form-check-label" for="edit-role-materials-{{ $subAccount->id }}">{{ __('Manage Materials') }}</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="edit-role-labour-{{ $subAccount->id }}" name="can_manage_labour" value="1" @checked($subAccount->can_manage_labour)>
                            <label class="form-check-label" for="edit-role-labour-{{ $subAccount->id }}">{{ __('Manage Labour') }}</label>
                        </div>
                        <small class="text-muted d-block mt-2">{{ __('Unchecked roles will remain read-only.') }}</small>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('Save Changes') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach
@endsection
