@php
    $column['value'] = $column['value'] ?? data_get($entry, $column['name']);
    $column['prefix'] = $column['prefix'] ?? '';
    $column['suffix'] = $column['suffix'] ?? '';
    $column['disk'] = $column['disk'] ?? null;
    $column['escaped'] = $column['escaped'] ?? true;
    $column['wrapper']['element'] = $column['wrapper']['element'] ?? 'a';
    $column['wrapper']['target'] = $column['wrapper']['target'] ?? '_blank';
    $column_wrapper_href = $column['wrapper']['href'] ?? 
    function($file_path, $disk, $prefix) { 
        if (is_null($disk)) {
            return $prefix.$file_path;
        }
        if (isset($column['temporary'])) {
            return asset(\Storage::disk($disk)->temporaryUrl($file_path, Carbon\Carbon::now()->addMinutes($column['temporary'])));
        }
        return asset(\Storage::disk($disk)->url($file_path));
    };

    if($column['value'] instanceof \Closure) {
        $column['value'] = $column['value']($entry);
    }

@endphp

@if ($column['value'] && count($column['value']))
<div id="preview" class="m-2">
  <!-- Section: Images -->
  <ul class="list-group  flex-row" style="flex-wrap: wrap;">
    @foreach ($column['value'] as $key => $file_path)
      <li class="list-group-item square" style="object-fit: cover; word-break: break-all;">
        <img 
          class="list-group-item-image image-to-preview"
          style="max-height: 50px; max-width: 50px" 
          src="{{ asset(\Storage::disk($field['disk'])->url($file_path)) }}" 
          alt="Image {{$key}}"
        >
      </li>
    @endforeach

  </ul>
  <div id="image-preview-modal" class="modal">
    <span class="close">&times;</span>
    <img class="modal-content" id="img01" />
    <div id="caption"></div>
  </div>
  <!-- Section: Images -->
</div>
@endif

<span>
    @if ($column['value'] && count($column['value']))
        @foreach ($column['value'] as $file_path)
        @php
            $column['wrapper']['href'] = $column_wrapper_href instanceof \Closure ? $column_wrapper_href($file_path, $column['disk'], $column['prefix']) : $column_wrapper_href;
            $text = $column['prefix'].$file_path.$column['suffix'];
        @endphp
            @includeWhen(!empty($column['wrapper']), 'crud::columns.inc.wrapper_start')
            @if($column['escaped'])
                - {{ $text }} <br/>
            @else
                - {!! $text !!} <br/>
            @endif
        @includeWhen(!empty($column['wrapper']), 'crud::columns.inc.wrapper_end')
        @endforeach
    @else
        {{ $column['default'] ?? '-' }}
    @endif
</span>
