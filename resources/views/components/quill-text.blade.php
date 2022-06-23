@props(['initialValue' => ''])

@push('styles')

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/quill/1.3.7/quill.snow.min.css" integrity="sha512-/FHUK/LsH78K9XTqsR9hbzr21J8B8RwHR/r8Jv9fzry6NVAOVIGFKQCNINsbhK7a1xubVu2r5QZcz2T9cKpubw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

@endpush

<div
    wire:ignore
    {{ $attributes }}
    x-data
    x-ref="quillEditor"
    x-init="
        quill = new Quill($refs.quillEditor, {theme: 'snow'});
        quill.on('text-change', function () {
            $dispatch('quill-input', quill.root.innerHTML);
        });
        this.addEventListener('quill-get', $e => {
            setTimeout(() => {
                quill.setSelection(999999);
              },100)
        })
    "
    x-on:quill-input.debounce.1000ms="@this.set('{{ $attributes['wire:model'] }}', $event.detail)"
>

    {!! $initialValue !!}

</div>

@push('scripts')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/quill/1.3.7/quill.min.js" integrity="sha512-P2W2rr8ikUPfa31PLBo5bcBQrsa+TNj8jiKadtaIrHQGMo6hQM6RdPjQYxlNguwHz8AwSQ28VkBK6kHBLgd/8g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

@endpush
