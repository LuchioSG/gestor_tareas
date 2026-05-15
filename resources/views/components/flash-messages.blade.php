@if(session('success'))
    <div class="mb-4 px-4 py-3 bg-green-100 border border-green-400 text-green-700 rounded-lg">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="mb-4 px-4 py-3 bg-red-100 border border-red-400 text-red-700 rounded-lg">
        {{ session('error') }}
    </div>
@endif