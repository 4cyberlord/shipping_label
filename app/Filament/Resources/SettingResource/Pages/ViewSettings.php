<?php

namespace App\Filament\Resources\SettingResource\Pages;

use App\Filament\Resources\SettingResource;
use App\Models\Setting;
use Filament\Actions;
use Filament\Resources\Pages\Page;

class ViewSettings extends Page
{
    protected static string $resource = SettingResource::class;

    protected static string $view = 'filament.resources.setting-resource.pages.view-settings';

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('edit')
                ->label('Edit Settings')
                ->url(fn () => SettingResource::getUrl('edit'))
                ->icon('heroicon-o-pencil'),
        ];
    }

    public function mount(): void
    {
        $settings = Setting::first();

        if (!$settings) {
            Setting::create([
                'app_name' => config('app.name'),
                'default_language' => 'en',
                'timezone' => 'Africa/Accra',
                'currency' => 'GHS',
            ]);
        }
    }
}
