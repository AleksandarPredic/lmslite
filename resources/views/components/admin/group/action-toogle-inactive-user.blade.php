@props(['group', 'user'])

{{-- # Toggle inactive user button --}}
{{-- # Set inactive button --}}
<form
    method="POST"
    action="{{ route('admin.groups.users.toggle-inactive', [$group, $user]) }}"
    class="inline"
    onsubmit="return confirm('{{ $user->pivot->inactive ? __('Are you sure you want to set :name as active?', ['name' => $user->name]) : __('Are you sure you want to set :name as inactive?', ['name' => $user->name]) }}');"
>
    @csrf
    @method('PATCH')
    <button type="submit"
            class="px-2 py-1 text-sm hover:bg-gray-100 hover:text-gray-800 {{ $user->pivot->inactive ? 'bg-indigo-100 border border-indigo-400 text-base' : 'bg-gray-800 text-white' }} rounded">
        {{ $user->pivot->inactive ? __('Set Active') : __('Set Inactive') }}
    </button>
</form>
