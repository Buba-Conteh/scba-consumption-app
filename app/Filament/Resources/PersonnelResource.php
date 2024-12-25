<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PersonnelResource\Pages;
use App\Filament\Resources\PersonnelResource\RelationManagers;
use App\Models\Batch;
use App\Models\Country;
use App\Models\Personnel;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class PersonnelResource extends Resource
{
    protected static ?string $model = Personnel::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')->required(),
                Forms\Components\TextInput::make('last_name')->required(),
                Forms\Components\TextInput::make('email')->email()->nullable(),
                Forms\Components\TextInput::make('rank')->nullable(),
                Forms\Components\TextInput::make('age')->numeric()->nullable(),
                Select::make('country_id')
                ->label('Country')
                ->options(Country::all()->pluck('name', 'id'))
               ->searchable(),
                Forms\Components\TextInput::make('airport')->required(),
                Select::make('batch_id')
                ->label('batch')
                ->options(Batch::all()->pluck('name', 'id'))
               ->searchable(),
                Forms\Components\TextInput::make('status')->nullable()->hidden('create'),
               
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
              ->columns([
            Tables\Columns\TextColumn::make('name'),
            Tables\Columns\TextColumn::make('last_name'),
            Tables\Columns\TextColumn::make('batch.name'),
            Tables\Columns\TextColumn::make('country.name'),
            Tables\Columns\TextColumn::make('rank'),
            Tables\Columns\TextColumn::make('age'),
            Tables\Columns\TextColumn::make('airport'),
            // Tables\Columns\TextColumn::make('badge'),
            Tables\Columns\TextColumn::make('status')
            ->badge()
            ->color(fn (string $state): string => match ($state) {
                'active' => 'warning',
                'graduated' => 'success'
            })
            // Tables\Columns\TextColumn::make('consumption'),
            // Tables\Columns\TextColumn::make('observation'),
            // ...
        ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                // SelectFilter::make('personnel')
                // ->relationship('personnel.country', 'name')
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                ExportBulkAction::make(),
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
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
            'index' => Pages\ListPersonnels::route('/'),
            'create' => Pages\CreatePersonnel::route('/create'),
            'edit' => Pages\EditPersonnel::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
