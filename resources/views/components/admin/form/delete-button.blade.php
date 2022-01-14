@props(['action'])

<form
    action="{{ $action }}"
    method="post"
    class="delete-model-form"
    onsubmit="return confirm('Are you sure')"
>
    @csrf
    @method('delete')

    <button type="submit">Delete</button>
</form>
