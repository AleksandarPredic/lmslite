@if(session()->has('admin.message.success'))
    <div class="flash-message-admin flash-message-admin--success bg-indigo-50 py-2 px-4 rounded border border-indigo-400 text-sm mb-4">
        <p>
            {!! strip_tags(session()->get('admin.message.success'), '<a>') !!}
        </p>
    </div>
@endif
