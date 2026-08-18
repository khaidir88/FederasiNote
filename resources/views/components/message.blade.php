@if (Session::get('success'))
    <div class="bg-green-200 border-green-600 overflow-hidden shadow-sm sm:rounded-sm p-4 mb-3">
                {{ Session::get('success') }}
    </div>
            @endif

@if (Session::get('error'))
    <div class="bg-red-200 border-red-600 overflow-hidden shadow-sm sm:rounded-sm p-4 mb-3">
                {{ Session::get('error') }}
    </div>
@endif