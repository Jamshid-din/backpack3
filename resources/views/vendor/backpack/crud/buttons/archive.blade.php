
@if (isset($_GET['archived']))
  @if ($_GET['archived'])
  <a id="myButton" href="{{ url($crud->route.'/?archived=0') }}" class="btn btn-primary" data-style="zoom-in">
    <span class="ladda-label">
    </i> <i class="las la-check-circle"></i>{{-- trans('backpack::crud.add') --}} Active Orders {{ $crud->entity_name }}
    </span>
  </a>
  @else
    <a id="myButton" href="{{ url($crud->route.'/?archived=1') }}" class="btn btn-primary" data-style="zoom-in">
      <span class="ladda-label">
      </i> <i class="las la-archive"></i>{{-- trans('backpack::crud.add') --}} Archived {{ $crud->entity_name }}
      </span>
    </a>
  @endif
@else
  <a id="myButton" href="{{ url($crud->route.'/?archived=1') }}" class="btn btn-primary" data-style="zoom-in">
    <span class="ladda-label">
    </i> <i class="las la-archive"></i>{{-- trans('backpack::crud.add') --}} Archived {{ $crud->entity_name }}
    </span>
  </a>
@endif

  

