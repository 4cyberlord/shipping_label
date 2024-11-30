<?php

namespace App\Filament\Widgets;

use App\Models\Package;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestPackages extends BaseWidget
{
    protected static ?int $sort = 3;
    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(Package::latest()->limit(5))
            ->columns([
                Tables\Columns\TextColumn::make('tracking_number')
                    ->label('Tracking #')
                    ->searchable(),
                Tables\Columns\TextColumn::make('toAddress.contact_name')
                    ->label('Recipient'),
                Tables\Columns\TextColumn::make('weight')
                    ->suffix(' kg'),
                Tables\Columns\TextColumn::make('shipping_cost')
                    ->money('GHS'),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'pending',
                        'primary' => ['accepted', 'queued'],
                        'info' => ['sending', 'in_transit'],
                        'success' => ['sent', 'delivered'],
                        'danger' => ['failed', 'undelivered', 'cancelled'],
                    ]),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->url(fn (Package $record): string => route('filament.admin.resources.packages.view', $record))
                    ->icon('heroicon-m-eye'),
            ]);
    }
}
