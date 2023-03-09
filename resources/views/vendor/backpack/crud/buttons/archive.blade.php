
@if (isset(request()->archived))
  @if (request()->archived)
  <a id="myButton" href="{{ url($crud->route.'/?archived=0') }}" class="btn btn-primary" data-style="zoom-in">
    <span class="ladda-label">
    </i> <i class="las la-check-circle"></i> {{ trans('custom.active_orders') }}
    </span>
  </a>
  @else
    <a id="myButton" href="{{ url($crud->route.'/?archived=1') }}" class="btn btn-primary" data-style="zoom-in">
      <span class="ladda-label">
      </i> <i class="las la-archive"></i> {{ trans('custom.archived_orders') }}
      </span>
    </a>
  @endif
@else
  <a id="myButton" href="{{ url($crud->route.'/?archived=1') }}" class="btn btn-primary" data-style="zoom-in">
    <span class="ladda-label">
    </i> <i class="las la-archive"></i> {{ trans('custom.archived_orders') }}
    </span>
  </a>
@endif

  

