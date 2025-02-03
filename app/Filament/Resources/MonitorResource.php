<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MonitorResource\Pages;
use App\Filament\Resources\MonitorResource\RelationManagers;
use App\Models\Monitor;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class MonitorResource extends Resource
{
    protected static ?string $model = Monitor::class;

    protected static ?string $navigationIcon = 'heroicon-o-magnifying-glass';

    public static function getNavigationGroup(): ?string
    {
        return 'Application Resources';
    }

    public static function getNavigationSort(): ?int
    {
        return 0;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('url')
                    ->required()
                    ->url()
                    ->afterStateHydrated(
                    // required or filament edit UI breaks on page load
                        fn ($state, $set) => $set('url', (string)$state)
                    ),
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'email')
                    ->searchable()
                    ->required(),
                Forms\Components\TextInput::make('look_for_string')->nullable(),
                Forms\Components\TextInput::make('uptime_check_interval_in_minutes')->required()->rules([
                    'numeric',
                    'min:1'
                ])->default(1),
                Forms\Components\Select::make('uptime_check_method')->required()->options([
                    'get' => 'get',
                    'post' => 'post',
                    'put' => 'put',
                    'patch' => 'patch',
                    'delete' => 'delete',
                    'options' => 'options',
                    'head' => 'head',
                    'connect' => 'connect',
                    'trace' => 'trace',
                ]),
                Forms\Components\Toggle::make('uptime_check_enabled')->rules([
                    'boolean'
                ])->default(1),
                Forms\Components\Toggle::make('certificate_check_enabled')->rules([
                    'boolean',
                ])->default(0),
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                $query->with('user');
            })
            ->columns([
                TextColumn::make('id')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('user.email')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('url'),
                TextColumn::make('uptime_check_enabled'),
                TextColumn::make('certificate_check_enabled'),
                TextColumn::make('look_for_string'),
                TextColumn::make('uptime_check_interval_in_minutes'),
                TextColumn::make('uptime_status'),
                TextColumn::make('uptime_check_failure_reason'),
                TextColumn::make('uptime_check_times_failed_in_a_row'),
                TextColumn::make('uptime_status_last_change_date'),
                TextColumn::make('uptime_last_check_date'),
                TextColumn::make('uptime_check_failed_event_fired_on_date'),
                TextColumn::make('uptime_check_method'),
                TextColumn::make('certificate_status'),
                TextColumn::make('certificate_expiration_date'),
                TextColumn::make('certificate_issuer'),
                TextColumn::make('certificate_check_failure_reason'),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
//                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [

        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMonitors::route('/'),
            'create' => Pages\CreateMonitor::route('/create'),
            'edit' => Pages\EditMonitor::route('/{record}/edit'),
        ];
    }
}
