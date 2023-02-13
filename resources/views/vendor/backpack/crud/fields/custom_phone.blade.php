{{-- text input --}}

@include('crud::fields.inc.wrapper_start')
    <label>{!! $field['label'] !!}</label>
    @include('crud::fields.inc.translatable_icon')

    @if(isset($field['prefix']) || isset($field['suffix'])) <div class="input-group"> @endif
        @if(isset($field['prefix'])) <div class="input-group-prepend"><span class="input-group-text">{!! $field['prefix'] !!}</span></div> @endif
        <input
            type="text"
            data-customphone="input"
            name="{{ $field['name'] }}"
            value="{{ old_empty_or_null($field['name'], '') ??  $field['value'] ?? $field['default'] ?? '' }}"
            @include('crud::fields.inc.attributes')
        >
        @if(isset($field['suffix'])) <div class="input-group-append"><span class="input-group-text">{!! $field['suffix'] !!}</span></div> @endif
    @if(isset($field['prefix']) || isset($field['suffix'])) </div> @endif

    {{-- HINT --}}
    @if (isset($field['hint']))
        <p class="help-block">{!! $field['hint'] !!}</p>
    @endif
@include('crud::fields.inc.wrapper_end')

<script>
  const $custom_phone_input = document.querySelector('[data-customphone="input"]')
  $custom_phone_input.addEventListener('input', handleCustomPhoneNumberInput, false)

  function handleCustomPhoneNumberInput (e) {
    e.target.value = customPhoneNumberMask(e.target.value)
  }

  function customPhoneNumberMask (phone) {
  return phone.replace(/\D/g, '')
    .replace(/(\d{3})(\d)/, '$1 $2')
    .replace(/(\d{3}\s\d{2})(\d{1,2})/, '$1 $2')
    .replace(/(\d{3}\s\d{2}\s\d{3})(\d{1,2})/, '$1 $2')
    .replace(/(\d{3}\s\d{2}\s\d{3}\s\d{4})\d+?$/, '$1')
  }
</script>