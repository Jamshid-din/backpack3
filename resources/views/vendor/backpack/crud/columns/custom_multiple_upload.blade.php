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

{{-- HINT --}}
@if (isset($field['hint']))
    <p class="help-block">{!! $field['hint'] !!}</p>
@endif

@include('crud::fields.inc.wrapper_end')

  {{-- ########################################## --}}
  {{-- Extra CSS and JS for this particular field --}}

  @push('crud_fields_styles')
    @loadOnce('upload_field_styles')
      <style type="text/css">
        .existing-file {
          border: 1px solid rgba(0,40,100,.12);
          border-radius: 5px;
          padding-left: 10px;
          vertical-align: middle;
        }
        .existing-file a {
          padding-top: 5px;
          display: inline-block;
          font-size: 0.9em;
        }
        .backstrap-file {
          position: relative;
          display: inline-block;
          width: 100%;
          height: calc(1.5em + 0.75rem + 2px);
          margin-bottom: 0;
        }

        .backstrap-file-input {
          position: relative;
          z-index: 2;
          width: 100%;
          height: calc(1.5em + 0.75rem + 2px);
          margin: 0;
          opacity: 0;
        }

        .backstrap-file-input:focus ~ .backstrap-file-label {
          border-color: #acc5ea;
          box-shadow: 0 0 0 0rem rgba(70, 127, 208, 0.25);
        }

        .backstrap-file-input:disabled ~ .backstrap-file-label {
          background-color: #e4e7ea;
        }

        .backstrap-file-input:lang(en) ~ .backstrap-file-label::after {
          content: "Browse";
        }

        .backstrap-file-input ~ .backstrap-file-label[data-browse]::after {
          content: attr(data-browse);
        }

        .backstrap-file-label {
          position: absolute;
          top: 0;
          right: 0;
          left: 0;
          z-index: 1;
          height: calc(1.5em + 0.75rem + 2px);
          padding: 0.375rem 0.75rem;
          font-weight: 400;
          line-height: 1.5;
          color: #5c6873;
          background-color: #fff;
          border: 1px solid #e4e7ea;
          border-radius: 0.25rem;
          font-weight: 400!important;
        }

        .backstrap-file-label::after {
          position: absolute;
          top: 0;
          right: 0;
          bottom: 0;
          z-index: 3;
          display: block;
          height: calc(1.5em + 0.75rem);
          padding: 0.375rem 0.75rem;
          line-height: 1.5;
          color: #5c6873;
          content: "Browse";
          background-color: #f0f3f9;
          border-left: inherit;
          border-radius: 0 0.25rem 0.25rem 0;
        }
      </style>
    @endLoadOnce
  @endpush

  @push('crud_fields_scripts')
    @loadOnce('bpFieldInitUploadMultipleElement')
      <script>
        function bpFieldInitUploadMultipleElement(element) {
          var fieldName = element.attr('data-field-name');
          var clearFileButton = element.find(".file-clear-button");
          var fileInput = element.find("input[type=file]");
          var inputLabel = element.find("label.backstrap-file-label");

          clearFileButton.click(function(e) {
            e.preventDefault();
            var container = $(this).parent().parent();
            var parent = $(this).parent();
            // remove the filename and button
            parent.remove();
            // if the file container is empty, remove it
            if ($.trim(container.html())=='') {
              container.remove();
            }
            $("<input type='hidden' name='clear_"+fieldName+"[]' value='"+$(this).data('filename')+"'>").insertAfter(fileInput);
          });

          fileInput.change(function() {
                inputLabel.html("{{trans('backpack::crud.upload_multiple_files_selected')}}");
        let selectedFiles = [];

        Array.from($(this)[0].files).forEach(file => {
          selectedFiles.push({name: file.name, type: file.type})
        });

        element.find('input').first().val(JSON.stringify(selectedFiles)).trigger('change');
            // remove the hidden input, so that the setXAttribute method is no longer triggered
        $(this).next("input[type=hidden]:not([name='clear_"+fieldName+"[]'])").remove();
          });

      element.find('input').on('CrudField:disable', function(e) {
        element.children('.backstrap-file').find('input').prop('disabled', 'disabled');
        element.children('.existing-file').find('.file-preview').each(function(i, el) {

          let $deleteButton = $(el).find('a.file-clear-button');

          if(deleteButton.length > 0) {
            $deleteButton.on('click.prevent', function(e) {
              e.stopImmediatePropagation();
              return false;
            });
            // make the event we just registered, the first to be triggered
            $._data($deleteButton.get(0), "events").click.reverse();
          }
        });
      });

      element.on('CrudField:enable', function(e) {
        element.children('.backstrap-file').find('input').removeAttr('disabled');
        element.children('.existing-file').find('.file-preview').each(function(i, el) {
          $(el).find('a.file-clear-button').unbind('click.prevent');
        });
      });
        }
      </script>
    @endLoadOnce
  @endpush