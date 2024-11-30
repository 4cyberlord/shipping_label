<?php

namespace App\Filament\Resources\SmsMessageResource\Pages;

use App\Filament\Resources\SmsMessageResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;
use App\Services\TwilioService;
use Exception;

class CreateSmsMessage extends CreateRecord
{
    protected static string $resource = SmsMessageResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function afterCreate(): void
    {
        try {
            // Send the SMS
            $twilioService = app(TwilioService::class);
            $response = $twilioService->sendMessage($this->record);

            if ($response) {
                Notification::make()
                    ->title('Message Sent Successfully')
                    ->success()
                    ->send();
            }
        } catch (Exception $e) {
            // Delete the created record since sending failed
            $this->record->delete();

            // Show error notification with modal
            if (str_contains($e->getMessage(), 'Twilio credentials are not configured')) {
                Notification::make()
                    ->title('Configuration Error')
                    ->body('Please configure Twilio credentials in settings before sending messages.')
                    ->danger()
                    ->persistent()
                    ->actions([
                        \Filament\Notifications\Actions\Action::make('configure')
                            ->label('Go to Settings')
                            ->url(route('filament.admin.resources.settings.index'))
                            ->button(),
                    ])
                    ->send();
            } else {
                Notification::make()
                    ->title('Error')
                    ->body($e->getMessage())
                    ->danger()
                    ->send();
            }

            $this->halt();
        }
    }
}
