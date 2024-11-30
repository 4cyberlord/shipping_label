<?php

namespace App\Filament\Resources\SettingResource\Pages;

use App\Filament\Resources\SettingResource;
use App\Models\Setting;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;

class EditSettings extends EditRecord
{
    protected static string $resource = SettingResource::class;

    public function mount(string|int $record = null): void
    {
        $settings = Setting::first() ?? Setting::create([
            'app_name' => config('app.name'),
            'default_language' => 'en',
            'timezone' => 'Africa/Accra',
            'currency' => 'GHS',
        ]);

        $record = $settings->id;
        parent::mount($record);
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function afterSave(): void
    {
        Notification::make()
            ->title('Settings updated successfully')
            ->success()
            ->send();
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('back')
                ->label('Back to Settings')
                ->url($this->getResource()::getUrl('index'))
                ->color('gray'),
        ];
    }
}
