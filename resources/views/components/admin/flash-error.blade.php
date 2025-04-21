@if(session()->has('admin.message.error') || $errors->any())
    <div class="flash-message-admin flash-message-admin--error bg-gray-800 py-2 px-4 rounded border border-gray-100 text-sm mb-4">
        @if($errors->any())
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        @endif

            @if(session()->has('admin.message.error'))
                <p>
                    {!! strip_tags(session()->get('admin.message.error'), '<a>') !!}
                </p>
            @endif
    </div>
@endif
