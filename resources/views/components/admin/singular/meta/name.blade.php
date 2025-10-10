@props(['name'])

<h3 {!! $attributes->merge(['class' => 'mb-4 px-2']) !!}>{{ $name }}</h3>
