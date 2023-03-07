{{-- single relationships (1-1, 1-n) --}}
@php
    $column['attribute'] = $column['attribute'] ?? (new $column['model'])->identifiableAttribute();
    $column['value'] = $column['value'] ?? $crud->getRelatedEntriesAttributes($entry, $column['entity'], $column['attribute']);
    $column['escaped'] = $column['escaped'] ?? true;
    $column['prefix'] = $column['prefix'] ?? '';
    $column['suffix'] = $column['suffix'] ?? '';
    $column['limit'] = $column['limit'] ?? 32;
    
    if($column['value'] instanceof \Closure) {
        $column['value'] = $column['value']($entry);
    }
    
    foreach ($column['value'] as &$value) {
        $value = Str::limit($value, $column['limit'], '…');
    }
    $models = App\Models\Status::orderBy('status_order')->get();
@endphp
<div class="d-flex" style="min-width: 125px">
  <button class='status-color{{$entry->id}} btn' style='background-color: {{ $entry->hasStatus->color}}'></button>
  
  {{ $column['prefix'] }}
  <select class="custom-status-column custom-status-column{{$entry->id}} form-control" data-id={{$entry->id}} >
    @foreach ($models as $key => $item)
      <option value="{{$item->id}}" data-color="{{$item->color}}"
        @if ($entry->hasStatus->id == $item->id)
          selected 
        @elseif ($key == 0 && $entry->hasStatus->id != $item->id)
          disabled
        @endif>
        {{ $item->name }}
      </option>
    @endforeach
  </select>
  {{ $column['suffix'] }}
</div>

