{{--
# Use this for any item you need. Use it as a wrapper
--}}
<div {!! $attributes->merge() !!}>
    {{ $slot }}
</div>
