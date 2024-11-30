<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PackageResource\Pages;
use App\Models\Package;
use App\Models\Setting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class PackageResource extends Resource
{
    protected static ?string $model = Package::class;
    protected static ?string $navigationIcon = 'heroicon-o-cube';
    protected static ?string $navigationGroup = 'Shipping';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        // Only show full form on create
        if (!$form->getRecord()) {
            return static::getCreateForm($form);
        }

        // Show only status field on edit
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

    // Move the original form to a separate method
    private static function getCreateForm(Form $form): Form
    {
        $settings = Setting::first();

        // Original form schema for create
        return $form->schema([
            Forms\Components\Wizard::make([
                Forms\Components\Wizard\Step::make('Sender Information')
                    ->schema([
                        Forms\Components\Section::make('From Address')
                            ->schema([
                                Forms\Components\TextInput::make('fromAddress.company_name')
                                    ->label('Company Name')
                                    ->default('Ryyhub')
                                    ->disabled()
                                    ->dehydrated(),
                                Forms\Components\TextInput::make('fromAddress.contact_name')
                                    ->label('Contact Name')
                                    ->default('Ryyhub Admin')
                                    ->disabled()
                                    ->dehydrated()
                                    ->required(),
                                Forms\Components\TextInput::make('fromAddress.phone')
                                    ->label('Phone')
                                    ->tel()
                                    ->default('0535429781')
                                    ->disabled()
                                    ->dehydrated()
                                    ->required(),
                                Forms\Components\TextInput::make('fromAddress.email')
                                    ->label('Email')
                                    ->email()
                                    ->default('support@admin.com')
                                    ->disabled()
                                    ->dehydrated(),
                                Forms\Components\TextInput::make('fromAddress.address_line_1')
                                    ->label('Address Line 1')
                                    ->default('Adum, Kumasi')
                                    ->disabled()
                                    ->dehydrated()
                                    ->required(),
                                Forms\Components\TextInput::make('fromAddress.address_line_2')
                                    ->label('Address Line 2')
                                    ->disabled()
                                    ->dehydrated(),
                                Forms\Components\TextInput::make('fromAddress.city')
                                    ->label('City')
                                    ->default('Kumasi')
                                    ->disabled()
                                    ->dehydrated()
                                    ->required(),
                                Forms\Components\TextInput::make('fromAddress.state')
                                    ->label('State')
                                    ->default('Adum')
                                    ->disabled()
                                    ->dehydrated()
                                    ->required(),
                                Forms\Components\TextInput::make('fromAddress.zip_code')
                                    ->label('ZIP Code')
                                    ->default('00233')
                                    ->disabled()
                                    ->dehydrated()
                                    ->required(),
                                Forms\Components\TextInput::make('fromAddress.country')
                                    ->label('Country')
                                    ->default('Ghana')
                                    ->disabled()
                                    ->dehydrated()
                                    ->required(),
                            ])->columns(2),
                    ]),

                Forms\Components\Wizard\Step::make('Recipient Information')
                    ->schema([
                        Forms\Components\Section::make('To Address')
                            ->schema([
                                Forms\Components\TextInput::make('toAddress.contact_name')
                                    ->label('Recipient Name')
                                    ->required(),
                                Forms\Components\TextInput::make('toAddress.phone')
                                    ->label('Contact Phone')
                                    ->tel()
                                    ->required(),
                                Forms\Components\TextInput::make('toAddress.email')
                                    ->label('Email')
                                    ->email(),
                                Forms\Components\TextInput::make('toAddress.address_line_1')
                                    ->label('Address Line 1')
                                    ->required(),
                                Forms\Components\TextInput::make('toAddress.address_line_2')
                                    ->label('Address Line 2'),
                                Forms\Components\TextInput::make('toAddress.city')
                                    ->label('City')
                                    ->required(),
                                Forms\Components\TextInput::make('toAddress.state')
                                    ->label('State')
                                    ->required(),
                                Forms\Components\TextInput::make('toAddress.zip_code')
                                    ->label('ZIP Code')
                                    ->required(),
                                Forms\Components\TextInput::make('toAddress.country')
                                    ->label('Country')
                                    ->default('Ghana')
                                    ->required(),
                                Forms\Components\Textarea::make('toAddress.delivery_instructions')
                                    ->label('Delivery Instructions')
                                    ->columnSpanFull(),
                            ])->columns(2),
                    ]),

                Forms\Components\Wizard\Step::make('Package Details')
                    ->schema([
                        Forms\Components\Section::make('Package Information')
                            ->schema([
                                Forms\Components\TextInput::make('weight')
                                    ->label('Weight (kg)')
                                    ->numeric()
                                    ->required()
                                    ->rules(['max:' . ($settings->maximum_parcel_weight ?? 100)]),
                                Forms\Components\TextInput::make('length')
                                    ->label('Length (cm)')
                                    ->numeric(),
                                Forms\Components\TextInput::make('width')
                                    ->label('Width (cm)')
                                    ->numeric(),
                                Forms\Components\TextInput::make('height')
                                    ->label('Height (cm)')
                                    ->numeric(),
                                Forms\Components\Select::make('status')
                                    ->options([
                                        'pending' => 'Pending',
                                        'processing' => 'Processing',
                                        'in_transit' => 'In Transit',
                                        'delivered' => 'Delivered',
                                        'failed' => 'Failed',
                                        'cancelled' => 'Cancelled',
                                    ])
                                    ->default('pending')
                                    ->required(),
                                Forms\Components\TextInput::make('shipping_cost')
                                    ->label('Shipping Cost')
                                    ->numeric()
                                    ->prefix($settings->currency ?? 'GHS'),
                            ])->columns(2),
                    ]),
            ])->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tracking_number')
                    ->label('Tracking #')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->copyMessage('Tracking number copied')
                    ->copyMessageDuration(1500),
                Tables\Columns\TextColumn::make('toAddress.contact_name')
                    ->label('Recipient')
                    ->searchable(),
                Tables\Columns\TextColumn::make('weight')
                    ->suffix(' kg')
                    ->sortable(),
                Tables\Columns\TextColumn::make('shipping_cost')
                    ->money('GHS')
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'pending',
                        'primary' => ['accepted', 'queued'],
                        'info' => ['sending', 'in_transit'],
                        'success' => ['sent', 'delivered'],
                        'danger' => ['failed', 'undelivered', 'cancelled'],
                    ]),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
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
                    ]),
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from'),
                        Forms\Components\DatePicker::make('created_until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    })
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('print_label')
                    ->label('Print Label')
                    ->icon('heroicon-o-printer')
                    ->url(fn (Package $record) => route('package.print-label', $record))
                    ->openUrlInNewTab()
                    ->visible(fn (Package $record) => $record->status !== 'cancelled'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPackages::route('/'),
            'create' => Pages\CreatePackage::route('/create'),
            'edit' => Pages\EditPackage::route('/{record}/edit'),
            'view' => Pages\ViewPackage::route('/{record}'),
        ];
    }
}
