@extends('layouts.app')
@section('title', __('core_superadmin.all_users'))

@section('content')

<section class="content-header">
    <h1>@lang('core_superadmin.all_users')</h1>
</section>

<section class="content">
    @include('superadmin.partials.nav')

    <div class="row">
        <div class="col-md-12">
            <div class="box box-solid">
                <div class="box-header with-border">
                    <form method="GET" action="{{ route('core_superadmin.users') }}" class="form-inline">
                        <div class="form-group">
                            <input type="text" name="search" class="form-control" value="{{ request('search') }}"
                                placeholder="@lang('core_superadmin.search_placeholder')" style="min-width: 280px;">
                        </div>
                        <div class="form-group">
                            <select name="business_id" class="form-control">
                                <option value="">@lang('core_superadmin.business'): @lang('core_superadmin.all')</option>
                                @foreach ($businesses as $id => $name)
                                    <option value="{{ $id }}" {{ (string) request('business_id') === (string) $id ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <select name="status" class="form-control">
                                <option value="">@lang('core_superadmin.status'): @lang('core_superadmin.all')</option>
                                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>@lang('core_superadmin.active')</option>
                                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>@lang('core_superadmin.inactive')</option>
                                <option value="terminated" {{ request('status') === 'terminated' ? 'selected' : '' }}>@lang('core_superadmin.terminated')</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-search"></i> @lang('core_superadmin.filter')
                        </button>
                        <a href="{{ route('core_superadmin.users') }}" class="btn btn-default">@lang('core_superadmin.reset')</a>
                    </form>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>@lang('core_superadmin.username')</th>
                                    <th>@lang('core_superadmin.name')</th>
                                    <th>@lang('core_superadmin.email')</th>
                                    <th>@lang('core_superadmin.business')</th>
                                    <th>@lang('core_superadmin.user_type')</th>
                                    <th>@lang('core_superadmin.allow_login')</th>
                                    <th>@lang('core_superadmin.status')</th>
                                    <th>@lang('core_superadmin.actions')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($users as $user)
                                    <tr>
                                        <td>{{ $user->id }}</td>
                                        <td>{{ $user->username }}</td>
                                        <td>{{ $user->user_full_name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            @if ($user->business)
                                                <a href="{{ route('core_superadmin.businesses.show', [$user->business_id]) }}">{{ $user->business->name }}</a>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>{{ $user->user_type }}</td>
                                        <td>{{ $user->allow_login ? __('core_superadmin.yes') : __('core_superadmin.no') }}</td>
                                        <td>
                                            @if ($user->status === 'active')
                                                <span class="label label-success">@lang('core_superadmin.active')</span>
                                            @elseif ($user->status === 'inactive')
                                                <span class="label label-warning">@lang('core_superadmin.inactive')</span>
                                            @else
                                                <span class="label label-danger">{{ $user->status }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($user->id != auth()->id())
                                                <form method="POST" action="{{ route('core_superadmin.users.toggle_status', [$user->id]) }}"
                                                    style="display:inline-block;"
                                                    onsubmit="return confirm('{{ __('core_superadmin.confirm_toggle_user') }}');">
                                                    @csrf
                                                    @if ($user->status === 'active')
                                                        <button type="submit" class="btn btn-xs btn-warning">
                                                            <i class="fa fa-ban"></i> @lang('core_superadmin.deactivate')
                                                        </button>
                                                    @else
                                                        <button type="submit" class="btn btn-xs btn-success">
                                                            <i class="fa fa-check"></i> @lang('core_superadmin.activate')
                                                        </button>
                                                    @endif
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center">@lang('core_superadmin.no_records')</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="box-footer clearfix">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
