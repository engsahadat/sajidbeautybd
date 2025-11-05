@extends('admin.layouts.app')
@section('admin-title','Users')
@section('admin-content')
<div class="container-fluid">
    <div class="page-header">
        <div class="row">
            <div class="col-lg-6">
                <div class="page-header-left">
                    <h3>{{ __('Users') }}</h3>
                </div>
            </div>
            <div class="col-lg-6">
                <ol class="breadcrumb pull-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">
                            <i data-feather="home"></i>
                        </a>
                    </li>
                    <li class="breadcrumb-item active">{{ __('Users') }}</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid">
    @if(session('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <form class="d-flex gap-2" method="GET" action="{{ route('users.index') }}">
                    <input class="form-control" type="search" name="search" value="{{ request('search') }}" placeholder="{{ __('Search users...') }}" style="max-width:250px">
                    <button class="btn btn-primary me-2 mb-2" type="submit">{{ __('Search') }}</button>
                    <a class="btn btn-secondary mb-2" href="{{ route('users.index') }}">{{ __('Reset') }}</a>
                </form>
                <a class="btn btn-primary" href="{{ route('users.create') }}">
                    <i class="fa fa-plus"></i> {{ __('New User') }}
                </a>
            </div>

            <div class="table-responsive">
                <table class="table table-striped align-middle">
                    <thead>
                        <tr>
                            <th>{{ __('Sl') }}</th>
                            <th>{{ __('Image') }}</th>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Email') }}</th>
                            <th>{{ __('Phone') }}</th>
                            <th>{{ __('Role') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Created At') }}</th>
                            <th>{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    @if($user->image)
                                        <img src="{{ asset($user->image) }}" alt="{{ $user->first_name }}" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
                                    @else
                                        <div class="rounded-circle bg-light d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; border: 1px solid #dee2e6;">
                                            <i class="fa fa-user text-muted"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>{{ $user->first_name }} {{ $user->last_name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->phone ?? '-' }}</td>
                                <td>
                                    <span class="badge bg-{{ $user->role == 1 ? 'danger' : 'info' }}">
                                        {{ $user->role == 1 ? 'Admin' : 'User' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $user->status == 'active' ? 'success' : ($user->status == 'inactive' ? 'secondary' : 'warning') }}">
                                        {{ ucfirst($user->status) }}
                                    </span>
                                </td>
                                <td>{{ $user->created_at->format('M d, Y') }}</td>
                                <td class="text-end">
                                    <a href="{{ route('users.show', $user->id) }}" title="{{ __('View') }}">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                    <a href="{{ route('users.edit', $user->id) }}" title="{{ __('Edit') }}">
                                        <i class="fa fa-pencil"></i>
                                    </a>
                                    <a href="{{ route('users.destroy', $user->id) }}" onclick="event.preventDefault(); if(confirm('{{ __('Are you sure you want to delete this user?') }}')) { document.getElementById('delete-form-{{ $user->id }}').submit(); }" title="{{ __('Delete') }}">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                    <form id="delete-form-{{ $user->id }}" action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-none">
                                        @csrf @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted">{{ __('No users found.') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($users->hasPages())
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div>
                        <p class="mb-0 text-muted">
                            {{ __('Showing :first to :last of :total results', [
                                'first' => $users->firstItem(),
                                'last' => $users->lastItem(),
                                'total' => $users->total()
                            ]) }}
                            @if(request('search'))
                                {{ __('for ":search"', ['search' => request('search')]) }}
                            @endif
                        </p>
                    </div>
                    <div class="d-flex justify-content-end">
                        {{ $users->appends(request()->query())->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection