<?php

namespace App\Models;

use App\Services\TwilioService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SmsMessage extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'delivery_log' => 'array',
        'scheduled_for' => 'datetime',
    ];

    public function template(): BelongsTo
    {
        return $this->belongsTo(SmsTemplate::class, 'template_id');
    }

    public function send()
    {
        if ($this->status !== 'pending') {
            return false;
        }

        // If message is scheduled for future, don't send now
        if ($this->scheduled_for && $this->scheduled_for->isFuture()) {
            return false;
        }

        return app(TwilioService::class)->sendMessage($this);
    }

    protected static function booted()
    {
        // Auto-send message after creation if not scheduled
        static::created(function ($message) {
            if (!$message->scheduled_for) {
                $message->send();
            }
        });
    }
}
