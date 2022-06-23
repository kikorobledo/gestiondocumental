@props(['initialValue' => ''])

<div
    class="w-full"
    wire:ignore
    {{ $attributes }}
    x-data
    @trix-blur="$dispatch('change', $event.target.value)"
>

    @push('styles')

            <link href="https://unpkg.com/trix@1.2.3/dist/trix.css" rel="stylesheet" />

    @endpush

    <style>

        .trix-button-row .trix-button-group--file-tools{
            display: none;
        }

        .trix-button-row .trix-button-group .trix-button--icon-link{
            display: none;
        }

        .trix-button-row .trix-button-group .trix-button--icon-code{
            display: none;
        }

    </style>

    <div>

        <input type="hidden" id="x" value="{{ $initialValue }}">

        <trix-editor input="x" class='bg-white rounded text-sm w-full focus:border-black focus:ring-0 cursor-pointer mb-4 md:mb-0'></trix-editor>

    </div>

    @push('scripts')

        <script src="https://unpkg.com/trix@1.2.3/dist/trix.js"></script>

    @endpush

</div>
