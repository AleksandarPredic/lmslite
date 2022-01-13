@if(session()->has('admin.message.error'))
    <div class="flash-message-admin flash-message-admin--error bg-gray-800 py-2 px-4 rounded border border-gray-100 text-sm mb-4">
        <p>
            {!! strip_tags(session()->get('admin.message.error'), '<a>') !!}
        </p>
    </div>
@endif
