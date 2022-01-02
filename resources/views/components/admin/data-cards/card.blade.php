@props(['name'])

<div class="flex justify-between bg-white shadow overflow-hidden border border-gray-200 sm:rounded-lg mb-4">
    <div class="px-6 py-4 whitespace-nowrap">
        <h5 class="text-sm text-gray-900">{{ $name }}</h5>
    </div>
    <div class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
        {{ $slot }}
    </div>
</div>
