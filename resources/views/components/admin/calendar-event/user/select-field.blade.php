@props(['name', 'value', 'options', 'route'])

<div {!! $attributes->merge(['class' => 'cal-event-user-status']) !!}>
    <form action="{{ $route }}" method="post">
        @csrf
        @method('patch')

        <select
            name="{{ $name }}"
            id="{{ $name }}"
            onchange="this.parentElement.submit();"
            required
        >
            @foreach($options as $optionValue => $optionName)
                @php
                    $selected = $optionValue === $value ? 'selected' : '';
                @endphp

                <option
                    value="{{ $optionValue }}"
                    {{ $selected }}
                >{{ $optionName }}</option>
            @endforeach
        </select>
    </form>
</div>
