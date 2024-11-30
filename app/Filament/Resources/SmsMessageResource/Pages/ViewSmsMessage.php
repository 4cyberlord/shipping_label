<?php

namespace App\Filament\Resources\SmsMessageResource\Pages;

use App\Filament\Resources\SmsMessageResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Twilio\Rest\Client;

class ViewSmsMessage extends ViewRecord
{
    protected static string $resource = SmsMessageResource::class;

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Message Details')
                    ->schema([
                        TextEntry::make('recipient_number')
                            ->label('Recipient'),
                        TextEntry::make('message_content')
                            ->label('Message'),
                        TextEntry::make('status')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'sent' => 'success',
                                'failed' => 'danger',
                                default => 'warning',
                            }),
                        TextEntry::make('created_at')
                            ->dateTime(),
                    ])->columns(2),

                Section::make('Twilio Response')
                    ->schema([
                        TextEntry::make('twilio_response')
                            ->label('API Response')
                            ->formatStateUsing(function ($record) {
                                if (!$record->twilio_message_sid) {
                                    return 'No Twilio message ID available';
                                }

                                try {
                                    $settings = \App\Models\Setting::first();
                                    $client = new Client(
                                        $settings->twilio_account_sid,
                                        $settings->twilio_auth_token
                                    );

                                    $twilioMessage = $client->messages($record->twilio_message_sid)->fetch();
                                    return json_encode($twilioMessage->toArray(), JSON_PRETTY_PRINT);
                                } catch (\Exception $e) {
                                    return "Error fetching status: " . $e->getMessage();
                                }
                            })
                            ->html()
                            ->prose()
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
