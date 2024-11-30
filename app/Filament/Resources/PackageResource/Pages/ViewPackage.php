<?php

namespace App\Filament\Resources\PackageResource\Pages;

use App\Filament\Resources\PackageResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\Split;

class ViewPackage extends ViewRecord
{
    protected static string $resource = PackageResource::class;

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Package Information')
                    ->schema([
                        Split::make([
                            Grid::make(2)
                                ->schema([
                                    TextEntry::make('tracking_number')
                                        ->label('Tracking Number')
                                        ->copyable()
                                        ->weight('bold')
                                        ->size('lg'),
                                    TextEntry::make('status')
                                        ->badge()
                                        ->color(fn (string $state): string => match ($state) {
                                            'pending' => 'warning',
                                            'accepted', 'queued' => 'primary',
                                            'sending', 'in_transit' => 'info',
                                            'sent', 'delivered' => 'success',
                                            default => 'danger',
                                        }),
                                ]),
                            Grid::make(3)
                                ->schema([
                                    TextEntry::make('weight')
                                        ->label('Weight')
                                        ->suffix(' kg'),
                                    TextEntry::make('shipping_cost')
                                        ->label('Cost')
                                        ->money('GHS'),
                                    TextEntry::make('created_at')
                                        ->label('Created')
                                        ->dateTime(),
                                ]),
                        ])->from('md'),
                    ])
                    ->collapsible(),

                Grid::make(2)
                    ->schema([
                        Section::make('From Address')
                            ->description('Sender Information')
                            ->schema([
                                TextEntry::make('fromAddress.company_name')
                                    ->label('Company')
                                    ->weight('bold'),
                                TextEntry::make('fromAddress.contact_name')
                                    ->label('Contact Name'),
                                TextEntry::make('fromAddress.phone')
                                    ->label('Phone')
                                    ->icon('heroicon-m-phone'),
                                TextEntry::make('fromAddress.email')
                                    ->label('Email')
                                    ->icon('heroicon-m-envelope'),
                                TextEntry::make('fromAddress.address_line_1')
                                    ->label('Address Line 1'),
                                TextEntry::make('fromAddress.address_line_2')
                                    ->label('Address Line 2'),
                                TextEntry::make('fromAddress.city')
                                    ->label('City'),
                                TextEntry::make('fromAddress.state')
                                    ->label('State'),
                                TextEntry::make('fromAddress.zip_code')
                                    ->label('ZIP Code'),
                                TextEntry::make('fromAddress.country')
                                    ->label('Country'),
                            ])
                            ->columns(2)
                            ->collapsible(),

                        Section::make('To Address')
                            ->description('Recipient Information')
                            ->schema([
                                TextEntry::make('toAddress.contact_name')
                                    ->label('Recipient Name')
                                    ->weight('bold'),
                                TextEntry::make('toAddress.phone')
                                    ->label('Phone')
                                    ->icon('heroicon-m-phone'),
                                TextEntry::make('toAddress.email')
                                    ->label('Email')
                                    ->icon('heroicon-m-envelope'),
                                TextEntry::make('toAddress.address_line_1')
                                    ->label('Address Line 1'),
                                TextEntry::make('toAddress.address_line_2')
                                    ->label('Address Line 2'),
                                TextEntry::make('toAddress.city')
                                    ->label('City'),
                                TextEntry::make('toAddress.state')
                                    ->label('State'),
                                TextEntry::make('toAddress.zip_code')
                                    ->label('ZIP Code'),
                                TextEntry::make('toAddress.country')
                                    ->label('Country'),
                                TextEntry::make('toAddress.delivery_instructions')
                                    ->label('Delivery Instructions')
                                    ->columnSpanFull(),
                            ])
                            ->columns(2)
                            ->collapsible(),
                    ]),

                Section::make('Package Details')
                    ->schema([
                        Grid::make(4)
                            ->schema([
                                TextEntry::make('length')
                                    ->label('Length')
                                    ->suffix(' cm'),
                                TextEntry::make('width')
                                    ->label('Width')
                                    ->suffix(' cm'),
                                TextEntry::make('height')
                                    ->label('Height')
                                    ->suffix(' cm'),
                                TextEntry::make('weight')
                                    ->label('Weight')
                                    ->suffix(' kg'),
                            ]),
                    ])
                    ->collapsible(),

                Section::make('Tracking History')
                    ->schema([
                        TextEntry::make('created_at')
                            ->label('Created')
                            ->dateTime(),
                        TextEntry::make('shipped_at')
                            ->label('Shipped')
                            ->dateTime(),
                        TextEntry::make('delivered_at')
                            ->label('Delivered')
                            ->dateTime(),
                    ])
                    ->columns(3)
                    ->collapsible(),
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('print_label')
                ->label('Print Label')
                ->icon('heroicon-m-printer')
                ->url(fn () => route('package.print-label', $this->record))
                ->openUrlInNewTab()
                ->visible(fn () => $this->record->status !== 'cancelled'),
            \Filament\Actions\EditAction::make(),
        ];
    }
}
