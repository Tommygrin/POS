@extends('layouts.app')
@section('title', __('core_superadmin.business_details'))

@section('content')

<section class="content-header">
    <h1>@lang('core_superadmin.business_details'): {{ $business->name }}</h1>
</section>

<section class="content">
    @include('superadmin.partials.nav')

    <div class="row">
        <div class="col-md-6">
            <div class="box box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ $business->name }}</h3>
                    <div class="box-tools">
                        @if ($business->is_active)
                            <span class="label label-success">@lang('core_superadmin.active')</span>
                        @else
                            <span class="label label-danger">@lang('core_superadmin.inactive')</span>
                        @endif
                    </div>
                </div>
                <div class="box-body">
                    <table class="table table-condensed">
                        <tr>
                            <th style="width: 40%;">ID</th>
                            <td>{{ $business->id }}</td>
                        </tr>
                        <tr>
                            <th>@lang('core_superadmin.owner')</th>
                            <td>{{ optional($business->owner)->user_full_name }}
                                @if ($business->owner)
                                    <small class="text-muted">({{ $business->owner->username }})</small>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>@lang('core_superadmin.owner_email')</th>
                            <td>{{ optional($business->owner)->email }}</td>
                        </tr>
                        <tr>
                            <th>@lang('core_superadmin.currency')</th>
                            <td>{{ optional($business->currency)->code }} {{ optional($business->currency)->symbol }}</td>
                        </tr>
                        <tr>
                            <th>@lang('core_superadmin.timezone')</th>
                            <td>{{ $business->time_zone }}</td>
                        </tr>
                        <tr>
                            <th>@lang('core_superadmin.financial_year')</th>
                            <td>{{ $business->fy_start_month }}</td>
                        </tr>
                        <tr>
                            <th>@lang('core_superadmin.registered_on')</th>
                            <td>{{ $business->created_at ? $business->created_at->format('Y-m-d H:i') : '' }}</td>
                        </tr>
                        <tr>
                            <th>@lang('core_superadmin.sales_count')</th>
                            <td>{{ $sales_count }}</td>
                        </tr>
                    </table>
                </div>
                <div class="box-footer">
                    <a href="{{ route('core_superadmin.businesses') }}" class="btn btn-default">
                        <i class="fa fa-arrow-left"></i> @lang('core_superadmin.back')
                    </a>
                    <form method="POST" action="{{ route('core_superadmin.businesses.toggle_active', [$business->id]) }}"
                        style="display:inline-block;"
                        onsubmit="return confirm('{{ __('core_superadmin.confirm_toggle_business') }}');">
                        @csrf
                        @if ($business->is_active)
                            <button type="submit" class="btn btn-danger">
                                <i class="fa fa-ban"></i> @lang('core_superadmin.deactivate')
                            </button>
                        @else
                            <button type="submit" class="btn btn-success">
                                <i class="fa fa-check"></i> @lang('core_superadmin.activate')
                            </button>
                        @endif
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="box box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title">@lang('core_superadmin.locations') ({{ $business->locations->count() }})</h3>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-condensed">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>@lang('core_superadmin.location_name')</th>
                                    <th>@lang('core_superadmin.city')</th>
                                    <th>@lang('core_superadmin.country')</th>
                                    <th>@lang('core_superadmin.mobile')</th>
                                    <th>@lang('core_superadmin.is_active')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($business->locations as $location)
                                    <tr>
                                        <td>{{ $location->id }}</td>
                                        <td>{{ $location->name }}</td>
                                        <td>{{ $location->city }}</td>
                                        <td>{{ $location->country }}</td>
                                        <td>{{ $location->mobile }}</td>
                                        <td>{{ $location->is_active ? __('core_superadmin.yes') : __('core_superadmin.no') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">@lang('core_superadmin.no_records')</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="box box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title">@lang('core_superadmin.users') ({{ $users->count() }})</h3>
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
                                        <td>{{ $user->username }}
                                            @if ($business->owner_id == $user->id)
                                                <span class="label label-info">@lang('core_superadmin.owner')</span>
                                            @endif
                                        </td>
                                        <td>{{ $user->user_full_name }}</td>
                                        <td>{{ $user->email }}</td>
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
                                        <td colspan="8" class="text-center">@lang('core_superadmin.no_records')</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
