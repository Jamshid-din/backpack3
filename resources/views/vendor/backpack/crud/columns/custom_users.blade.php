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
    $users = Spatie\Permission\Models\Role::findByName('Artist')->users;

@endphp
<div style="{{$column['style']??''}}">
  {{ $column['prefix'] }}
  <select class="custom-users-column custom-users-column{{$entry->id}} form-control" data-id={{$entry->id}}>
    <option value="null">-</option>
  
    @foreach ($users as $key => $item)
        <option value="{{$item->id}}" @if ($item->id == $entry->user_id) selected @endif>
          {{$item->name}}
        </option>
    @endforeach
  </select>
  {{ $column['suffix'] }}
</div>
