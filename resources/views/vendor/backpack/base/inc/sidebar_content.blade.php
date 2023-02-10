{{-- This file is used to store sidebar items, inside the Backpack admin panel --}}
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i class="la la-home nav-icon"></i> {{ trans('backpack::base.dashboard') }}</a></li>

<!-- Users, Roles, Permissions -->
<li class="nav-item nav-dropdown">
  <a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon la la-users"></i> Authentication</a>
  <ul class="nav-dropdown-items">

      @if (backpack_user()->can('Manage Users'))
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('user') }}"><i class="nav-icon la la-user"></i> <span>Users</span></a></li>
      @endif

      @if (backpack_user()->can('Manage Roles'))
      <li class="nav-item"><a class="nav-link" href="{{ backpack_url('role') }}"><i class="nav-icon la la-id-badge"></i> <span>Roles</span></a></li>
      @endif

      @if (backpack_user()->can('Manage Permissions'))
      <li class="nav-item"><a class="nav-link" href="{{ backpack_url('permission') }}"><i class="nav-icon la la-key"></i> <span>Permissions</span></a></li>
      @endif

  </ul>
</li> 
@if (backpack_user()->can('Manage Orders'))
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('orders') }}"><i class="nav-icon la la-question"></i> Orders</a></li>
@endif
@if (backpack_user()->can('Manage Order Status'))
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('status') }}"><i class="nav-icon la la-question"></i> Statuses</a></li>
@endif
@if (backpack_user()->can('Manage Status Log'))
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('order-status') }}"><i class="nav-icon la la-question"></i> Order statuses</a></li>
@endif

<li class="nav-item"><a class="nav-link" href="{{ backpack_url('complexity') }}"><i class="nav-icon la la-question"></i> Complexities</a></li>