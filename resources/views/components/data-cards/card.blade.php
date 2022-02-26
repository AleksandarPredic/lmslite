@props(['name', 'imageSrc' => null, 'svg' => null])

<div class="data-cards_card sm:flex sm:justify-between bg-white shadow overflow-hidden border border-gray-200 sm:rounded-lg mb-4">
    <div class="px-6 py-4">
        <div class="flex items-center mb-4">
            @if($imageSrc)
                <img class="mr-2" src="{{ $imageSrc }}" alt="User image" />
            @endif
            @if($svg)
                <div class="mr-2">
                    {!! $svg !!}
                </div>
            @endif
            <h5 class="data-cards_card__name text-gray-900 font-semibold">{{ $name }}</h5>
        </div>

        <div class="flex data-cards_properties">
            {{ $properties ?? null }}
        </div>
    </div>
    <div class="data-cards_card-links flex justify-end px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
        {{ $slot }}
    </div>
</div>
