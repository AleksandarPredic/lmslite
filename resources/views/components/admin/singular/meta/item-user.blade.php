{{-- # Use in any show model view to display list of users and a button to perform some destroy model action --}}
@props(['user', 'removeRoute' => false])

@php
/**
 * @var \App\Models\User $user
 */
@endphp

<x-admin.singular.meta.item-wrapper>
    <x-admin.singular.meta.item-icon>
      <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#000000"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zM7.07 18.28c.43-.9 3.05-1.78 4.93-1.78s4.51.88 4.93 1.78C15.57 19.36 13.86 20 12 20s-3.57-.64-4.93-1.72zm11.29-1.45c-1.43-1.74-4.9-2.33-6.36-2.33s-4.93.59-6.36 2.33C4.62 15.49 4 13.82 4 12c0-4.41 3.59-8 8-8s8 3.59 8 8c0 1.82-.62 3.49-1.64 4.83zM12 6c-1.94 0-3.5 1.56-3.5 3.5S10.06 13 12 13s3.5-1.56 3.5-3.5S13.94 6 12 6zm0 5c-.83 0-1.5-.67-1.5-1.5S11.17 8 12 8s1.5.67 1.5 1.5S12.83 11 12 11z"/></svg>
    </x-admin.singular.meta.item-icon>

    <x-admin.singular.meta.item-properties-wrapper>
        <x-admin.data-property>
            {{ $user->name }}
        </x-admin.data-property>
    </x-admin.singular.meta.item-properties-wrapper>

    <x-admin.singular.meta.item-links-wrapper>
        @if ($removeRoute)
            <x-admin.action-delete-button class="px-2 py-1"
              action="{{ route('admin.groups.users.destroy', [$user->pivot->id, $user]) }}"
              button-text="{{ __('Remove')}}"
            />
        @endif

            {{-- // TODO: Add show link to user profile --}}
    </x-admin.singular.meta.item-links-wrapper>
</x-admin.singular.meta.item-wrapper>