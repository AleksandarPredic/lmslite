@props(['cards', 'pagination' => null])

<div class="data-cards mb-4">
    <div class="data-cards_items">
        {{ $cards }}
    </div>

    @if($pagination)
        <div class="data-cards__pagination mt-6">
            {{ $pagination }}
        </div>
    @endif
</div>
