<?php

namespace App\Filament\Resources\PackageResource\Pages;

use App\Filament\Resources\PackageResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Forms;
use Filament\Notifications\Notification;

class EditPackage extends EditRecord
{
    protected static string $resource = PackageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    public function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Forms\Components\Section::make('Update Status')
                ->description('Update the current status of this package')
                ->schema([
                    Forms\Components\Select::make('status')
                        ->label('Package Status')
                        ->options([
                            'pending' => 'Pending',
                            'accepted' => 'Accepted',
                            'queued' => 'Queued',
                            'sending' => 'Sending',
                            'sent' => 'Sent',
                            'in_transit' => 'In Transit',
                            'delivered' => 'Delivered',
                            'failed' => 'Failed',
                            'undelivered' => 'Undelivered',
                            'cancelled' => 'Cancelled',
                        ])
                        ->required()
                        ->native(false)
                        ->columnSpanFull(),
                ])
                ->columns(1),
        ]);
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->record]);
    }

    protected function afterSave(): void
    {
        Notification::make()
            ->title('Package status updated successfully')
            ->success()
            ->send();
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Update timestamps based on status
        if ($data['status'] === 'delivered' && !$this->record->delivered_at) {
            $data['delivered_at'] = now();
        }

        if (in_array($data['status'], ['sent', 'in_transit']) && !$this->record->shipped_at) {
            $data['shipped_at'] = now();
        }

        return $data;
    }
}
