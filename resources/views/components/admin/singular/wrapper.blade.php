{{-- # https://tailwindui.com/components/application-ui/data-display/description-lists --}}
<div class="singular-meta bg-white shadow overflow-hidden sm:rounded-lg event-preview">

    {{-- # Header --}}
    <header class="px-4 py-6 sm:px-6">
        {{ $info ?? null }}
    </header>

    {{-- # Properties --}}
    @if($properties ?? null)
        <main class="border-t border-gray-200 pb-3 px-2">
            <dl>
                {{ $properties }}
            </dl>
        </main>
    @endif

    {{-- # Anything else we need between --}}
    @if($slot->isNotEmpty())
        <div class="border-t border-gray-200 py-6 px-6">
            {{ $slot }}
        </div>
    @endif

    {{-- # Meta --}}
    @if($meta ?? null)
        <div class="py-6 px-4">
            {{ $meta }}
        </div>
    @endif
</div>
