@if(session()->has('admin.message.success'))
    <div class="bg-indigo-50 py-2 px-4 rounded border border-indigo-400 text-sm mb-4">
        <p>
            {{ session()->get('admin.message.success') }}
        </p>
    </div>
@endif
