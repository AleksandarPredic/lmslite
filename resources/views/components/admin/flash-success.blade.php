@if(session()->has('form.message.success'))
    <div class="bg-indigo-50 py-2 px-4 rounded border border-indigo-400 text-sm mb-4">
        <p>
            {{ session()->get('form.message.success') }}
        </p>
    </div>
@endif
