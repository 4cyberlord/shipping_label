<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SettingResource\Pages;
use App\Models\Setting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SettingResource extends Resource
{
    protected static ?string $model = Setting::class;
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationGroup = 'System';
    protected static ?int $navigationSort = 100;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('Settings')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('General Settings')
                            ->schema([
                                Forms\Components\TextInput::make('app_name')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\FileUpload::make('logo_path')
                                    ->image()
                                    ->directory('logos'),
                                Forms\Components\Select::make('default_language')
                                    ->options([
                                        'en' => 'English',
                                    ])
                                    ->default('en')
                                    ->disabled()
                                    ->required(),
                                Forms\Components\Select::make('timezone')
                                    ->options([
                                        'Africa/Accra' => 'Africa/Accra (GMT)',
                                    ])
                                    ->default('Africa/Accra')
                                    ->disabled()
                                    ->required(),
                                Forms\Components\Select::make('currency')
                                    ->options([
                                        'GHS' => 'Ghana Cedis (GHS)',
                                    ])
                                    ->default('GHS')
                                    ->disabled()
                                    ->required(),
                            ])->columns(2),

                        Forms\Components\Tabs\Tab::make('Notification Settings')
                            ->schema([
                                Forms\Components\Toggle::make('email_notifications_enabled')
                                    ->label('Enable Email Notifications'),
                                Forms\Components\Toggle::make('sms_notifications_enabled')
                                    ->label('Enable SMS Notifications'),
                                Forms\Components\TextInput::make('default_sms_sender')
                                    ->tel()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('default_email_sender')
                                    ->email()
                                    ->maxLength(255),
                            ])->columns(2),

                        Forms\Components\Tabs\Tab::make('Delivery Settings')
                            ->schema([
                                Forms\Components\TextInput::make('default_delivery_time')
                                    ->numeric()
                                    ->label('Default Delivery Time (hours)'),
                                Forms\Components\TextInput::make('maximum_parcel_weight')
                                    ->numeric()
                                    ->step(0.01),
                                Forms\Components\TagsInput::make('allowed_delivery_areas')
                                    ->separator(','),
                            ])->columns(2),

                        Forms\Components\Tabs\Tab::make('Payment Settings')
                            ->schema([
                                Forms\Components\Select::make('payment_gateway')
                                    ->options([
                                        'stripe' => 'Stripe',
                                        'paypal' => 'PayPal',
                                        'twilio' => 'Twilio',
                                    ]),
                                Forms\Components\TextInput::make('tax_rate')
                                    ->numeric()
                                    ->step(0.01)
                                    ->suffix('%'),
                                Forms\Components\TextInput::make('refund_policy_link')
                                    ->url(),
                            ])->columns(2),

                        Forms\Components\Tabs\Tab::make('Twilio Settings')
                            ->schema([
                                Forms\Components\TextInput::make('twilio_account_sid')
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('twilio_auth_token')
                                    ->password()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('twilio_phone_number')
                                    ->tel()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('messaging_service_sid')
                                    ->label('Messaging Service SID')
                                    ->required()
                                    ->maxLength(255),
                            ])->columns(2),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('app_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('default_language')
                    ->searchable(),
                Tables\Columns\TextColumn::make('timezone')
                    ->searchable(),
                Tables\Columns\TextColumn::make('currency')
                    ->searchable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('updated_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ViewSettings::route('/'),
            'edit' => Pages\EditSettings::route('/edit'),
        ];
    }
}
