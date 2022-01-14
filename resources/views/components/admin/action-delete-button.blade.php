@props(['action'])

<form
    action="{{ $action }}"
    method="post"
    class="delete-model-form"
    onsubmit="return confirm('Are you sure')"
>
    @csrf
    @method('delete')

    <button
        {!! $attributes->merge(['class' => 'border border-gray-400 text-gray-600 hover:bg-gray-100 hover:border-gray-300 hover:text-gray-400 px-6 py-2 rounded ml-4']) !!}
        type="submit"
    >
        Delete
    </button>
</form>
