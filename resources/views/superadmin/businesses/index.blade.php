@extends('layouts.app')
@section('title', __('core_superadmin.all_businesses'))

@section('content')

<section class="content-header">
    <h1>@lang('core_superadmin.all_businesses')</h1>
</section>

<section class="content">
    @include('superadmin.partials.nav')

    <div class="row">
        <div class="col-md-12">
            <div class="box box-solid">
                <div class="box-header with-border">
                    <form method="GET" action="{{ route('core_superadmin.businesses') }}" class="form-inline">
                        <div class="form-group">
                            <input type="text" name="search" class="form-control" value="{{ request('search') }}"
                                placeholder="@lang('core_superadmin.search_placeholder')" style="min-width: 280px;">
                        </div>
                        <div class="form-group">
                            <select name="status" class="form-control">
                                <option value="">@lang('core_superadmin.all')</option>
                                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>@lang('core_superadmin.active')</option>
                                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>@lang('core_superadmin.inactive')</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-search"></i> @lang('core_superadmin.filter')
                        </button>
                        <a href="{{ route('core_superadmin.businesses') }}" class="btn btn-default">@lang('core_superadmin.reset')</a>
                    </form>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>@lang('core_superadmin.business_name')</th>
                                    <th>@lang('core_superadmin.owner')</th>
                                    <th>@lang('core_superadmin.owner_email')</th>
                                    <th>@lang('core_superadmin.currency')</th>
                                    <th>@lang('core_superadmin.locations')</th>
                                    <th>@lang('core_superadmin.users')</th>
                                    <th>@lang('core_superadmin.registered_on')</th>
                                    <th>@lang('core_superadmin.status')</th>
                                    <th>@lang('core_superadmin.actions')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($businesses as $business)
                                    <tr>
                                        <td>{{ $business->id }}</td>
                                        <td>{{ $business->name }}</td>
                                        <td>{{ optional($business->owner)->user_full_name }}
                                            @if ($business->owner)
                                                <br><small class="text-muted">{{ $business->owner->username }}</small>
                                            @endif
                                        </td>
                                        <td>{{ optional($business->owner)->email }}</td>
                                        <td>{{ optional($business->currency)->code }}</td>
                                        <td>{{ $business->locations_count }}</td>
                                        <td>{{ $user_counts[$business->id] ?? 0 }}</td>
                                        <td>{{ $business->created_at ? $business->created_at->format('Y-m-d H:i') : '' }}</td>
                                        <td>
                                            @if ($business->is_active)
                                                <span class="label label-success">@lang('core_superadmin.active')</span>
                                            @else
                                                <span class="label label-danger">@lang('core_superadmin.inactive')</span>
                                            @endif
                                        </td>
                                        <td class="text-nowrap">
                                            <a href="{{ route('core_superadmin.businesses.show', [$business->id]) }}" class="btn btn-xs btn-primary">
                                                <i class="fa fa-eye"></i> @lang('core_superadmin.view')
                                            </a>
                                            <form method="POST" action="{{ route('core_superadmin.businesses.toggle_active', [$business->id]) }}"
                                                style="display:inline-block;"
                                                onsubmit="return confirm('{{ __('core_superadmin.confirm_toggle_business') }}');">
                                                @csrf
                                                @if ($business->is_active)
                                                    <button type="submit" class="btn btn-xs btn-danger">
                                                        <i class="fa fa-ban"></i> @lang('core_superadmin.deactivate')
                                                    </button>
                                                @else
                                                    <button type="submit" class="btn btn-xs btn-success">
                                                        <i class="fa fa-check"></i> @lang('core_superadmin.activate')
                                                    </button>
                                                @endif
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center">@lang('core_superadmin.no_records')</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="box-footer clearfix">
                    {{ $businesses->links() }}
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
