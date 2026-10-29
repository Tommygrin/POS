@extends('layouts.app')
@section('title', __('core_superadmin.dashboard'))

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>@lang('core_superadmin.dashboard')</h1>
</section>

<!-- Main content -->
<section class="content">
    @include('superadmin.partials.nav')

    <div class="row">
        <div class="col-md-12">
            <div class="callout callout-info">
                <h4><i class="fa fa-info-circle"></i> @lang('core_superadmin.notice_title')</h4>
                <p>@lang('core_superadmin.notice_text')</p>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-aqua">
                <div class="inner">
                    <h3>{{ $stats['businesses'] }}</h3>
                    <p>@lang('core_superadmin.total_businesses')</p>
                </div>
                <div class="icon"><i class="fa fa-briefcase"></i></div>
                <a href="{{ route('core_superadmin.businesses') }}" class="small-box-footer">
                    @lang('core_superadmin.view') <i class="fa fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-green">
                <div class="inner">
                    <h3>{{ $stats['active_businesses'] }}</h3>
                    <p>@lang('core_superadmin.active_businesses')</p>
                </div>
                <div class="icon"><i class="fa fa-check-circle"></i></div>
                <a href="{{ route('core_superadmin.businesses', ['status' => 'active']) }}" class="small-box-footer">
                    @lang('core_superadmin.view') <i class="fa fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-red">
                <div class="inner">
                    <h3>{{ $stats['inactive_businesses'] }}</h3>
                    <p>@lang('core_superadmin.inactive_businesses')</p>
                </div>
                <div class="icon"><i class="fa fa-ban"></i></div>
                <a href="{{ route('core_superadmin.businesses', ['status' => 'inactive']) }}" class="small-box-footer">
                    @lang('core_superadmin.view') <i class="fa fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-yellow">
                <div class="inner">
                    <h3>{{ $stats['new_businesses_30d'] }}</h3>
                    <p>@lang('core_superadmin.new_businesses_30d')</p>
                </div>
                <div class="icon"><i class="fa fa-plus-circle"></i></div>
                <a href="{{ route('core_superadmin.businesses') }}" class="small-box-footer">
                    @lang('core_superadmin.view') <i class="fa fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-purple">
                <div class="inner">
                    <h3>{{ $stats['users'] }}</h3>
                    <p>@lang('core_superadmin.total_users')</p>
                </div>
                <div class="icon"><i class="fa fa-users"></i></div>
                <a href="{{ route('core_superadmin.users') }}" class="small-box-footer">
                    @lang('core_superadmin.view') <i class="fa fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-teal">
                <div class="inner">
                    <h3>{{ $stats['active_users'] }}</h3>
                    <p>@lang('core_superadmin.active_users')</p>
                </div>
                <div class="icon"><i class="fa fa-user"></i></div>
                <a href="{{ route('core_superadmin.users', ['status' => 'active']) }}" class="small-box-footer">
                    @lang('core_superadmin.view') <i class="fa fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-navy">
                <div class="inner">
                    <h3>{{ $stats['locations'] }}</h3>
                    <p>@lang('core_superadmin.total_locations')</p>
                </div>
                <div class="icon"><i class="fa fa-map-marker"></i></div>
                <span class="small-box-footer">&nbsp;</span>
            </div>
        </div>
        <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-olive">
                <div class="inner">
                    <h3>{{ $stats['sales'] }}</h3>
                    <p>@lang('core_superadmin.total_sales')</p>
                </div>
                <div class="icon"><i class="fa fa-shopping-cart"></i></div>
                <span class="small-box-footer">&nbsp;</span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="box box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title">@lang('core_superadmin.recent_businesses')</h3>
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
                                    <th>@lang('core_superadmin.locations')</th>
                                    <th>@lang('core_superadmin.registered_on')</th>
                                    <th>@lang('core_superadmin.status')</th>
                                    <th>@lang('core_superadmin.actions')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($recent_businesses as $business)
                                    <tr>
                                        <td>{{ $business->id }}</td>
                                        <td>{{ $business->name }}</td>
                                        <td>{{ optional($business->owner)->user_full_name }}
                                            @if ($business->owner)
                                                <br><small class="text-muted">{{ $business->owner->username }}</small>
                                            @endif
                                        </td>
                                        <td>{{ optional($business->owner)->email }}</td>
                                        <td>{{ $business->locations_count }}</td>
                                        <td>{{ $business->created_at ? $business->created_at->format('Y-m-d H:i') : '' }}</td>
                                        <td>
                                            @if ($business->is_active)
                                                <span class="label label-success">@lang('core_superadmin.active')</span>
                                            @else
                                                <span class="label label-danger">@lang('core_superadmin.inactive')</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('core_superadmin.businesses.show', [$business->id]) }}" class="btn btn-xs btn-primary">
                                                <i class="fa fa-eye"></i> @lang('core_superadmin.view')
                                            </a>
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
