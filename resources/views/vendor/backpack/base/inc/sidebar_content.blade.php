{{-- This file is used to store sidebar items, inside the Backpack admin panel --}}
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i class="la la-home nav-icon"></i> {{ trans('backpack::base.dashboard') }}</a></li>

<!-- Users, Roles, Permissions -->
@if (backpack_user()->can('users list') || backpack_user()->can('roles list') || backpack_user()->can('permissions list'))
  <li class="nav-item nav-dropdown">
    <a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon la la-users"></i> {{ trans('custom.menu_authentication') }}</a>
    <ul class="nav-dropdown-items">

        @if (backpack_user()->can('users list'))
          <li class="nav-item"><a class="nav-link" href="{{ backpack_url('user') }}"><i class="nav-icon la la-user"></i> <span>{{ trans('custom.menu_users') }}</span></a></li>
        @endif

        @if (backpack_user()->can('roles list'))
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('role') }}"><i class="nav-icon la la-id-badge"></i> <span>{{ trans('custom.menu_roles') }}</span></a></li>
        @endif

        @if (backpack_user()->can('permissions list'))
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('permission') }}"><i class="nav-icon la la-key"></i> <span>{{ trans('custom.menu_permissions') }}</span></a></li>
        @endif

    </ul>
  </li> 
@endif
@if (backpack_user()->can('orders list'))
  <li class="nav-item"><a class="nav-link" href="{{ backpack_url('orders') }}"><i class="nav-icon la la-question"></i> {{ trans('custom.menu_orders') }}</a></li>
@endif
@if (backpack_user()->can('status list'))
  <li class="nav-item"><a class="nav-link" href="{{ backpack_url('status') }}"><i class="nav-icon la la-question"></i> {{ trans('custom.menu_statuses') }}</a></li>
@endif
@if (backpack_user()->can('order status list'))
  <li class="nav-item"><a class="nav-link" href="{{ backpack_url('order-status') }}"><i class="nav-icon la la-question"></i> {{ trans('custom.menu_order_log') }}</a></li>
@endif
@if (backpack_user()->can('complexity list'))
  <li class="nav-item"><a class="nav-link" href="{{ backpack_url('complexity') }}"><i class="nav-icon la la-question"></i> {{ trans('custom.menu_complexity') }}</a></li>
@endif
@if (backpack_user()->can('currency list'))
  <li class="nav-item"><a class="nav-link" href="{{ backpack_url('currency') }}"><i class="nav-icon la la-question"></i> {{ trans('custom.menu_currency') }}</a></li>
@endif
@if (backpack_user()->can('telegram configs list'))
  <li class="nav-item"><a class="nav-link" href="{{ backpack_url('telegram-config') }}"><i class="nav-icon la la-question"></i> {{ trans('custom.menu_telegram_configs') }}</a></li>
@endif

