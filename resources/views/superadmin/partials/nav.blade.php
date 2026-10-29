{{-- Sub-navigation shared by all built-in super admin pages --}}
<div class="row" style="margin-bottom: 15px;">
    <div class="col-md-12">
        <ul class="nav nav-tabs">
            <li class="{{ request()->routeIs('core_superadmin.index') ? 'active' : '' }}">
                <a href="{{ route('core_superadmin.index') }}">
                    <i class="fa fa-tachometer"></i> @lang('core_superadmin.dashboard')
                </a>
            </li>
            <li class="{{ request()->routeIs('core_superadmin.businesses*') ? 'active' : '' }}">
                <a href="{{ route('core_superadmin.businesses') }}">
                    <i class="fa fa-briefcase"></i> @lang('core_superadmin.all_businesses')
                </a>
            </li>
            <li class="{{ request()->routeIs('core_superadmin.users*') ? 'active' : '' }}">
                <a href="{{ route('core_superadmin.users') }}">
                    <i class="fa fa-users"></i> @lang('core_superadmin.all_users')
                </a>
            </li>
        </ul>
    </div>
</div>

@if (session('status'))
    <div class="row">
        <div class="col-md-12">
            <div class="alert {{ session('status.success') ? 'alert-success' : 'alert-danger' }} alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                {{ session('status.msg') }}
            </div>
        </div>
    </div>
@endif
