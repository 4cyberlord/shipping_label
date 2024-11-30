<?php

namespace App\Filament\Resources\PackageResource\Pages;

use App\Filament\Resources\PackageResource;
use App\Models\ShippingAddress;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreatePackage extends CreateRecord
{
    protected static string $resource = PackageResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Create From Address
        $fromAddress = ShippingAddress::create([
            'type' => 'from',
            'company_name' => $data['fromAddress']['company_name'] ?? null,
            'contact_name' => $data['fromAddress']['contact_name'],
            'phone' => $data['fromAddress']['phone'],
            'email' => $data['fromAddress']['email'] ?? null,
            'address_line_1' => $data['fromAddress']['address_line_1'],
            'address_line_2' => $data['fromAddress']['address_line_2'] ?? null,
            'city' => $data['fromAddress']['city'],
            'state' => $data['fromAddress']['state'],
            'zip_code' => $data['fromAddress']['zip_code'],
            'country' => $data['fromAddress']['country'],
        ]);

        // Create To Address
        $toAddress = ShippingAddress::create([
            'type' => 'to',
            'contact_name' => $data['toAddress']['contact_name'],
            'phone' => $data['toAddress']['phone'],
            'email' => $data['toAddress']['email'] ?? null,
            'address_line_1' => $data['toAddress']['address_line_1'],
            'address_line_2' => $data['toAddress']['address_line_2'] ?? null,
            'city' => $data['toAddress']['city'],
            'state' => $data['toAddress']['state'],
            'zip_code' => $data['toAddress']['zip_code'],
            'country' => $data['toAddress']['country'],
            'delivery_instructions' => $data['toAddress']['delivery_instructions'] ?? null,
        ]);

        // Add address IDs to package data
        return array_merge($data, [
            'from_address_id' => $fromAddress->id,
            'to_address_id' => $toAddress->id,
        ]);
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function afterCreate(): void
    {
        Notification::make()
            ->title('Package created successfully')
            ->success()
            ->send();
    }
}
