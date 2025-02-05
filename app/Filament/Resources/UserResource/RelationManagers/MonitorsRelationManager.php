<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class MonitorsRelationManager extends RelationManager
{
    protected static string $relationship = 'monitors';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('url')
            ->modifyQueryUsing(function (Builder $query) {
                $query->with('user');
            })
            ->columns([
                TextColumn::make('id')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('user_id'),
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
            ->headerActions([
                //                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                //                Tables\Actions\EditAction::make(),
                //                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    //                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
