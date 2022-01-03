@props(['action'])

<form
    action="{{ $action }}"
    method="post"
    class="ml-12"
    onsubmit="return confirm('Are you sure')"
>
    @csrf
    @method('delete')

    <button type="submit">Delete</button>
</form>
