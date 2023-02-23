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
          class="list-group-item-image cursor-pointer image-to-preview"
          style="max-height: 50px; max-width: 50px; cursor: pointer;" 
          src="{{ asset(\Storage::disk($column['disk'])->url($file_path)) }}" 
          alt="Image {{$key}}"
        >
      </li>
    @endforeach

  </ul>
  <div id="image-preview-modal" class="modal-preview-img">
    <span class="preview-close">&times;</span>
    <img class="modal-content-img-preview" id="img01" />
    <div id="caption"></div>
  </div>
  <!-- Section: Images -->
</div>
@endif

<span>
    @if ($column['value'] && count($column['value']))
        @foreach ($column['value'] as $key => $file_path)
          @php
            $column['wrapper']['href'] = $column_wrapper_href instanceof \Closure ? $column_wrapper_href($file_path, $column['disk'], $column['prefix']) : $column_wrapper_href;
            $text = $column['prefix'].$file_path.$column['suffix'];
          @endphp
            @includeWhen(!empty($column['wrapper']), 'crud::columns.inc.wrapper_start')
            @if($column['escaped'])
                {{$key+1}}. {{ $text }} <br/>
            @else
                - {!! $text !!} <br/>
            @endif
        @includeWhen(!empty($column['wrapper']), 'crud::columns.inc.wrapper_end')
        @endforeach
    @else
        {{ $column['default'] ?? '-' }}
    @endif
</span>

<style type="text/css">
    .modal-preview-img {
      display: none;
      position: fixed;
      z-index: 1;
      padding-top: 100px;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      overflow: auto;
      background-color: rgba(0, 0, 0, 0.9);
      z-index: 999;
    }

    .modal-content-img-preview {
      margin: auto;
      display: block;
      width: 80%;
      max-width: 700px;
    }

    .preview-close {
      position: absolute;
      top: 15px;
      right: 35px;
      color: #f1f1f1;
      font-size: 40px;
      font-weight: bold;
      transition: 0.3s;
    }

    .preview-close:hover,
    .preview-close:focus {
      color: #bbb;
      text-decoration: none;
      cursor: pointer;
    }
</style>
