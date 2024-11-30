<div class="space-y-4">
    <div class="grid grid-cols-2 gap-4">
        <div>
            <span class="text-sm font-medium text-gray-500">Message SID</span>
            <p class="mt-1 text-sm">{{ $message->twilio_message_sid }}</p>
        </div>
        <div>
            <span class="text-sm font-medium text-gray-500">Status</span>
            <p class="mt-1">
                <span @class([
                    'px-2 py-1 text-xs rounded-full',
                    'bg-success-50 text-success-700' => $message->status === 'sent',
                    'bg-warning-50 text-warning-700' => $message->status === 'pending',
                    'bg-danger-50 text-danger-700' => $message->status === 'failed',
                ])>
                    {{ ucfirst($message->status) }}
                </span>
            </p>
        </div>
    </div>

    @if($message->delivery_log)
        <div class="mt-4">
            <span class="text-sm font-medium text-gray-500">Delivery Log</span>
            <div class="mt-2 space-y-2">
                @foreach($message->delivery_log as $log)
                    <div class="bg-gray-50 p-2 rounded text-sm">
                        <div class="flex justify-between">
                            <span class="font-medium">{{ $log['status'] }}</span>
                            <span class="text-gray-500">{{ $log['timestamp'] }}</span>
                        </div>
                        @if(isset($log['message']))
                            <p class="mt-1 text-gray-600">{{ $log['message'] }}</p>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
