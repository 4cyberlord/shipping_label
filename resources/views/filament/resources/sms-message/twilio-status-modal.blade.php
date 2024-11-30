<div class="p-4">
    <div class="bg-gray-100 p-4 rounded-lg">
        <pre class="whitespace-pre-wrap text-sm font-mono text-gray-900">{{ $response }}</pre>
    </div>

    @if(isset($error))
        <div class="mt-4 p-4 bg-danger-50 text-danger-700 rounded-lg">
            {{ $error }}
        </div>
    @endif
</div>
