{{-- # https://tailwindui.com/components/application-ui/data-display/description-lists --}}
<div class="bg-white shadow overflow-hidden sm:rounded-lg event-preview">

    {{-- # Header --}}
    <div class="px-4 py-6 sm:px-6">
        {{ $info ?? null }}
    </div>

    {{-- # Properties --}}
    @if($properties ?? null)
        <div class="border-t border-gray-200 pb-3 px-2">
            <dl>
                {{ $properties }}
            </dl>
        </div>
    @endif

    {{-- # Meta --}}
    @if($metalist ?? null)
        <div class="border-t border-gray-200 py-6 px-4 event-preview__calendar_events">
            {{ $metalistname ?? null }}

            <ul role="list" class="border border-gray-200 rounded-md">
                {{ $metalist }}
            </ul>
        </div>
    @endif
</div>
