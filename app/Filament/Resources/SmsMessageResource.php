<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SmsMessageResource\Pages;
use App\Models\SmsMessage;
use App\Models\SmsTemplate;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Services\TwilioService;
use Filament\Tables\Actions\Action;
use Filament\Support\Colors\Color;
use Twilio\Rest\Client;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\ActionGroup;
use Exception;

class SmsMessageResource extends Resource
{
    protected static ?string $model = SmsMessage::class;
    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left';
    protected static ?string $navigationGroup = 'Communication';
    protected static ?string $navigationLabel = 'SMS Messages';
    protected static ?int $navigationSort = 1;
    protected static ?string $modelLabel = 'SMS Message';
    protected static ?string $pluralModelLabel = 'SMS Messages';
    protected static ?string $createButtonLabel = 'Send New Message';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('recipient_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('message_content')
                    ->limit(50),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'sent',
                        'danger' => 'failed',
                    ]),
                Tables\Columns\TextColumn::make('scheduled_for')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'sent' => 'Sent',
                        'failed' => 'Failed',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make()
                ->schema([
                    Forms\Components\TextInput::make('recipient_number')
                        ->label('Recipient Phone Number')
                        ->tel()
                        ->required()
                        ->prefix('+233')
                        ->helperText('Enter the number without the leading zero (e.g., 24XXXXXXX)')
                        ->beforeStateDehydrated(function ($state) {
                            try {
                                $twilioService = app(TwilioService::class);
                                return $twilioService->formatPhoneNumber($state);
                            } catch (Exception $e) {
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

                                return $state;
                            }
                        }),

                    Forms\Components\Select::make('template_id')
                        ->label('Message Template')
                        ->relationship('template', 'name')
                        ->reactive()
                        ->afterStateUpdated(fn ($state, callable $set) =>
                            $set('message_content', SmsTemplate::find($state)?->content ?? '')),

                    Forms\Components\Textarea::make('message_content')
                        ->label('Message Content')
                        ->required()
                        ->columnSpanFull(),

                    Forms\Components\DateTimePicker::make('scheduled_for')
                        ->label('Schedule For')
                        ->nullable()
                        ->minDate(now())
                        ->rules(['nullable', 'date', 'after:now']),
                ])->columns(2)
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSmsMessages::route('/'),
            'create' => Pages\CreateSmsMessage::route('/create'),
            'edit' => Pages\EditSmsMessage::route('/{record}/edit'),
            'view' => Pages\ViewSmsMessage::route('/{record}'),
        ];
    }
}
